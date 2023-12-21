<?php
ini_set('display_errors', 1);

require_once('../rabbitmq_lib/path.inc');
require_once('../rabbitmq_lib/get_host_info.inc');
require_once('../rabbitmq_lib/rabbitMQLib.inc');

$connection = new rabbitMQClient("testRabbitMQ.ini", "testServer");
$channel = $connection->channel();

$exchange = 'RabbitMQ Messaging';
$queue = 'Content';

$channel->exchange_declare($exchange, 'direct', false, true, false);
$channel->queue_declare($queue, false, true, false, false);

$dataToSend = "Hello, RabbitMQ!"; // Your data to send

$channel->basic_publish(new AMQPMessage($dataToSend), $exchange);

echo "Message sent: $dataToSend\n";

$channel->close();
$connection->close();
?>
