<?php
ini_set('display_errors', 1);
require_once('../rabbitmq_lib/path.inc');
require_once('../rabbitmq_lib/get_host_info.inc');
require_once('../rabbitmq_lib/rabbitMQLib.inc');

function tryConnectRabbitMQ($primaryServer, $secondaryServer, $timeoutSeconds) {
    $startTime = time();
    while (time() - $startTime < $timeoutSeconds) {
        try {
            return new rabbitMQClient("testRabbitMQ.ini", $primaryServer);
        } catch (Exception $e) {
            usleep(100000); // Wait for 100 milliseconds
        }
    }
    // If primary server connection fails, try secondary server
    try {
        return new rabbitMQClient("testRabbitMQ.ini", $secondaryServer);
    } catch (Exception $e) {
        return null; // Both servers failed
    }
}
?>