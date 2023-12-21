<?php
ini_set('display_errors', 1);

// Include RabbitMQ client and other required files
require_once('tryConnectRabbitMQ.php');
require_once('../rabbitmq_lib/path.inc');
require_once('../rabbitmq_lib/get_host_info.inc');
require_once('../rabbitmq_lib/rabbitMQLib.inc');

function get_user_id() {
	/* Get User ID */
	// Prepare RabbitMQ client
	$client = tryConnectRabbitMQ("testServer", "secondaryServer", 5);

	$cookie_value = $_COOKIE['user_id'];

	// Construct request
	$request = array();
	$request['type'] = "Session";
	$request['username_cipher_text'] = $cookie_value;

	$userId = $client->send_request($request);
	return $userId;
}

?>
