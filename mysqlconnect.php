#!/usr/bin/php
<?php

// Connect to MySQL Database
$mydb = new mysqli('127.0.0.1', 'testUser', 'test', 'testdb');

if ($mydb->errno != 0) {
	echo "failed to connect database: ". $mydb->error . PHP_EOL;
	exit(0);
}

echo "successfully connected to the database".PHP_EOL;

$query = "select * from students;";

$response = $mydb->query($query);
if ($mydb->errno != 0) {

	echo "failed to execute query:".PHP_EOL;
	echo __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
	exit(0);
}	

?>
