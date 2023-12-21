<?php

ini_set('display_errors', 1);

require_once('../rabbitmq_lib/path.inc');
require_once('../rabbitmq_lib/get_host_info.inc');
require_once('../rabbitmq_lib/rabbitMQLib.inc');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$data = json_decode(file_get_contents('php://input'), true);
	if (isset($data["user_id"])) {
		$user_id = $data["user_id"];
		try {
			$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");
		} catch (Exception $e) {
			$client = new rabbitMQClient("testRabbitMQ.ini", "secondaryServer");
		}
		
		/* Send login request to server */
		$request = array();
		$request['type'] = "Session";
		$request['username_cipher_text'] = $user_id;

		$response = $client->send_request($request);

		if ($response === false) {
			echo "Unable to decrypt cipher: " . $user_id;
		}

		else {
			echo "true";
		}
	} 
	else {
		echo "User id not defined";
	}
}
?>
