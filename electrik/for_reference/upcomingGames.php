<?php
header("content-type: application/json");
ini_set('display_errors', 1);

require_once('../rabbitmq_lib/path.inc');
require_once('../rabbitmq_lib/get_host_info.inc');
require_once('../rabbitmq_lib/rabbitMQLib.inc');


$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

/* Send register request to server */
$request = array();
$request['type'] = "GetAppList";
$response = $client->send_request($request);
echo $response;
?>