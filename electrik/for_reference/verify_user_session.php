<?php

ini_set('display_errors', 1);

require_once('logger.php');
require_once('../rabbitmq_lib/path.inc');
require_once('../rabbitmq_lib/get_host_info.inc');
require_once('../rabbitmq_lib/rabbitMQLib.inc');

$logMessages = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$data = json_decode(file_get_contents('php://input'), true);
	if (isset($data["user_id"])) {
		$user_id = $data["user_id"];
		$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");
		
		/* Send login request to server */
		$request = array();
		$request['type'] = "Session";
		$request['username_cipher_text'] = $user_id;

		$response = $client->send_request($request);

		if ($response === false) {
			$logMessages .= "Unable to decrypt cipher: " . $user_id . "\n";
			echo "Unable to decrypt cipher: " . $user_id;
		}

		else {
			$logMessages .= "Session verification success for user_id: " . $user_id . "\n";
            echo "true";
		}
	} 
	else {
		$logMessages .= "User id not defined\n";
		echo "User id not defined";
	}
}
sendLogMessage($logMessages);
?>
