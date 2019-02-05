<?php
$servername="localhost:3306";
$server_username="root";
$server_password="Wwy1234567890";
$db="note";

$mysqli = new mysqli($servername, $server_username, $server_password, $db);
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}
?>