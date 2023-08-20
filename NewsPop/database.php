<?php
// Content of database.php

$mysqli = new mysqli('localhost', 'newspop-admin', 'dijkstra', 'newspop');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>