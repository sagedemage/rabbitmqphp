<?php
require_once('../rabbitmq_lib/path.inc');
require_once('../rabbitmq_lib/get_host_info.inc');
require_once('../rabbitmq_lib/rabbitMQLib.inc');
function sendLogMessage($message) {
    $logClient = new rabbitMQClient("testRabbitMQ.ini", "loggingServer");
    $logRequest = array("type" => "SendLog", "message" => $message);
    $logClient->send_request($logRequest);
}
?>