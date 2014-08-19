<?php

class StoredObject {
  protected static $UNKNOWN = "Unknown";

  private $result; // sql result

  function __construct($result) {
    $this->result = $result;
  }

  protected function has($name) {
    return isset($this->result[$name]) && !is_null($this->result[$name]);
  }

  protected function get($name) {
    return $this->has($name) ? $this->result[$name] : StoredObject::$UNKNOWN;
  }

  protected function getTagged($name, $tag) {
    return $this->has($name) ? $this->result[$name] : ($tag . ' ' . StoredObject::$UNKNOWN);
  }

  protected function getMoney($name) {
    return $this->has($name) ? ('$' . $this->result[$name]) : StoredObject::$UNKNOWN;
  }

  protected function getPhone($name) {
    if(!$this->has($name)) return StoredObject::$UNKNOWN;
    $num = $this->get($name);
    $len = strlen($num);
    $rt = substr($num, $len - 4);
    $rt = substr($num, $len - 7, 3) . "-" . $rt;
    if($len > 7) {
      $rt = "(" . substr($num, $len - 10, 3) . ") " . $rt;
      if($len > 10) {
        $rt = substr($num, $len - 10, 1) . " " . $rt;
      }
    }
    return $rt;
  }

  protected function getURL($name, $tag) {
    return $this->has($name) ? ('<a href="http://' . $this->get($name) . '" />' . $tag . '</a>') : ($tag . ' ' . StoredObject::$UNKNOWN);
  }

  function getOther($name) {
    return $this->get($name);
  }

  protected function getWithBackup() { // pass name, then components
    $args = func_num_args();
    assert($args % 2 == 1); // odd number of arguments (backups come in pairs)
    $name = func_get_arg(0);
    if($this->has($name)) {
      return $this->get($name);
    } else {
      for($i = 1; $i < $args; $i+=2) {
        $first = func_get_arg($i);
        $second = func_get_arg($i + 1);
        if(StoredObject::isKnown($first, $second)) {
          return $first + $second;
        }
      }
      return StoredObject::$UNKNOWN;
    }
  }

  protected function getMoneyWithBackup() {
    $args = func_get_args();
    $rt = call_user_func_array("StoredObject::getWithBackup", $args);
    return StoredObject::isKnown($rt) ? ('$' . $rt) : StoredObject::$UNKNOWN;
  }

  // returns true if all args are known
  protected static function isKnown() {
    foreach(func_get_args() as $item) {
      if(is_null($item) || $item == StoredObject::$UNKNOWN) return false;
    }
    return true;
  }

  protected static function getFrac($top, $bot) {
    return StoredObject::isKnown($top, $bot) ? ($top / $bot) : -1.0;
  }

  protected static function formatFrac($frac) {
    return $frac >= 0.0 ? (round($frac * 100) . '%') : StoredObject::$UNKNOWN;
  }

  protected static function formatRange($min, $max) {
    return StoredObject::isKnown($min, $max) ? ($min . ' - ' . $max) : StoredObject::$UNKNOWN;
  }

  protected static function getAvg($lwr, $upr) {
    return StoredObject::isKnown($lwr, $upr) ? (($lwr + $upr) / 2) : -1;
  }
}

?>