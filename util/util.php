<?php

// returns true if any of the parameters passed to it are set in the POST environment
function anySet() {
  foreach(func_get_args() as $item) {
    if(isset($_POST[$item]) && strlen($_POST[$item]) > 0) {
      return true;
    }
  }
  return false;
}

// returns true if all of the parameters passed to it are set in the POST environment
function allSet() {
  foreach(func_get_args() as $item) {
    if(!isset($_POST[$item]) || strlen($_POST[$item]) <= 0) {
      return false;
    }
  }
  return true;
}

// echos the value of the given variable in POST if and only if it is set
function e($var) {
  if(isset($_POST[$var])) {
    echo ' value="' . $_POST[$var] . '"';
  }
}

// returns the value of the given variable in POST if it is set and has a length > 0, otherwise returns NULL
function n($name) {
  if(isset($_POST[$name]) && strlen($_POST[$name]) > 0) {
    return $_POST[$name];
  } else {
    return NULL;
  }
}

// returns the value of the given variable in POST if it is set, otherwise returns an empty string
function b($name) {
  if(isset($_POST[$name])) {
    return $_POST[$name];
  } else {
    return "";
  }
}

// returns the value of the given variable in POST if it is set, otherwise returns an empty array
function bArr($name) {
  if(isset($_POST[$name])) {
    return $_POST[$name];
  } else {
    return array();
  }
}

// returns the integer value of the given variable in POST if it is set to a valid integer, otherwise returns 0
function bInt($name) {
  if(isset($_POST[$name])) {
    $i = intval($_POST[$name]);
    return is_nan($i) ? 0 : $i;
  } else {
    return 0;
  }
}

// interprets a checkbox sent via POST
function isChecked($name) {
  return isset($_POST[$name]);
}

// authenticates user. must include get-db.php before using
function authenticate() {
  if(!isset($_COOKIE['hash'])) return -1;
  global $mysql;
  $stmt = $mysql->prepare("SELECT `id` FROM `sessions` WHERE `hash` = ?");
  $stmt->bind_param("s", $_COOKIE['hash']);
  $stmt->execute();
  $id = NULL;
  $stmt->bind_result($id);
  if(!$stmt->fetch()) return -1;
  return $id;
}

// signs the current user in. must include get-db.php before using. returns true on success, false on failure
function signIn($email, $pass) {
  global $mysql;
  $stmt = $mysql->prepare("SELECT `id` FROM `students` WHERE `email` = ? AND `pass` = AES_ENCRYPT(?, 'supersecretkey')");
  $stmt->bind_param("ss", $email, $pass);
  $stmt->execute();
  $id = NULL;
  $stmt->bind_result($id);
  if(!$stmt->fetch()) {
    $stmt->close();
    return false;
  } else {

    $stmt->close();

    $stmt = $mysql->prepare("INSERT INTO `sessions` VALUES(?, ?, NULL)");
    $hash = bin2hex(openssl_random_pseudo_bytes(23));
    $expiration = isChecked("remember") ? time() + (60 * 60 * 24 * 30) : 0;
    $stmt->bind_param("is", $id, $hash);
    $stmt->execute();
    $stmt->close();

    setcookie('hash', $hash, $expiration, "/");

    return true;
  }
}

?>