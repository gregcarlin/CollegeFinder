<?php

function error($code) {
  echo $code;
  die();
}

if(!isset($_GET['action'], $_GET['school'], $_GET['list'])) {
  error(1); // required parameters not set
}

$action = intval($_GET['action']);
$school = intval($_GET['school']);
$list = intval($_GET['list']);

if(is_nan($action) || is_nan($school) || is_nan($list) || $list < 0 || $list > 2 || $school < 0 || $action < 0) {
  error(2); // parameters not valid
}

require_once "util/util.php";
require_once "util/get-db.php";

$id = authenticate();
if($id < 0) {
  error(3); // not logged in
}

$stmt = $mysql->prepare("SELECT * FROM `schools` WHERE `id` = ?");
$stmt->bind_param("i", $school);
$stmt->execute();
if(!$stmt->fetch()) {
  $stmt->close();
  error(4); // not a valid school id
}
$stmt->close();

$stmt = $mysql->prepare("SELECT * FROM `lists` WHERE `student_id` = ? AND `school_id` = ? AND `list_id` = ?");
$stmt->bind_param("iii", $id, $school, $list);
$stmt->execute();
$present = $stmt->fetch();
$stmt->close();

switch($action) {
  default:
    error(5); // unknown action
  case 0: // add item to list
    if($present) {
      error(6); // item already in list
    }
    $stmt = $mysql->prepare("INSERT INTO `lists` VALUES(?,?,?,0)");
    $stmt->bind_param("iii", $id, $school, $list);
    $stmt->execute();
    $stmt->close();
    break;
  case 1: // remove item from list
    if(!$present) {
      error(7); // item not in list
    }
    $stmt = $mysql->prepare("DELETE FROM `lists` WHERE `student_id` = ? AND `school_id` = ? AND `list_id` = ?");
    $stmt->bind_param("iii", $id, $school, $list);
    $stmt->execute();
    $stmt->close();
    break;
  case 2: // set rank
    if(!$present) error(7);
    if(!isset($_GET['data'])) error(1);
    $rank = intval($_GET['data']);
    if(is_nan($rank)) error(2);
    $stmt = $mysql->prepare("UPDATE `lists` SET `rank` = ? WHERE `student_id` = ? AND `school_id` = ? AND `list_id` = ?");
    $stmt->bind_param("iiii", $rank, $id, $school, $list);
    $stmt->execute();
    $stmt->close();
    break;
}

echo 0;

?>