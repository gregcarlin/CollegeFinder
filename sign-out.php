<?php

require_once "util/util.php";
require_once "util/get-db.php";

$id = authenticate();
$stmt = $mysql->prepare("DELETE FROM `sessions` WHERE `id` = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: index.php");
die();

?>