#!/usr/bin/php
<?php

function logging($errorType, $errorMsg, $lineNum, $fileName) {
	$rabbitMQLogCli = get_rabbitMQLogCli();
	date_default_timezone_set('US/Eastern'); // sets timezone to EST
	$time=time();
	$currTime = ("\n" . date("m-d-Y", $time) . " " . date('h:i:s') . "\n"); //date and time
	$errorFileName = "ERROR IN FILE " . $fileName . ": "; //file name where error
	$line = "ON LINE: " . $lineNum . " \n"; // line number of error
	$errorMsgFMT = $errorMsg . "\n";
	$message = $errorFileName . "\n" . $line;
	$label = "ERROR: \n";
	$error = array();
	$error['separator1'] = "------------------------------------------------------------------------------------"
	$error['type'] = $errorType;
	$error['dt'] = $currTime;
	$error['fileLine'] = $message;
	$error['label'] = $label;
	$error['error'] = $errorMsgFMT;
	$error['separator2'] = "------------------------------------------------------------------------------------"
	$rabbitMQLogCli->publish($error);
}

function logError($error, $fileName) {
	$file = fopen( $fileName . '.txt', "a" );
	foreach ($error as $errors) {
		fwrite($file, $errors);
	}
}

?>
