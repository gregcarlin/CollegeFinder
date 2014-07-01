<?php

$mysql = new mysqli('127.0.0.1', 'root', 'codium7a', 'college_finder');

if($mysql->connect_error) {
    // TODO
    die();
}

?>