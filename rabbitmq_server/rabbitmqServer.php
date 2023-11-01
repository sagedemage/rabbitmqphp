#!/usr/bin/php
<?php
ini_set('display_errors', 1);

require_once('../rabbitmq_lib/path.inc');
require_once('../rabbitmq_lib/get_host_info.inc');
require_once('../rabbitmq_lib/rabbitMQLib.inc');

function requestProcessor($request) {
	// send request to receiver
	$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");


	$response = $client->send_request($request);
	
	return $response;
}

$server = new rabbitMQServer("testRabbitMQ.ini", "testServer");
echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;

?>
