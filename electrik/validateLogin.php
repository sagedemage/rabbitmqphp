<?php
ini_set('display_errors', 1);

require_once('../rabbitmq_lib/path.inc');
require_once('../rabbitmq_lib/get_host_info.inc');
require_once('../rabbitmq_lib/rabbitMQLib.inc');

/* Client */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
	$user = $_POST['id'];
	$pwd = $_POST['pwd'];
	$error = false;
	$errorMsgs = array();

	// Validation and sanitization code here...

	if (!isset($user) || empty($user)) {
		$error = true;
		$errorMsgs[] = "Username is empty.";
	} 
	else {
		$user = htmlspecialchars($user, ENT_QUOTES, 'UTF-8'); 
	}

	if (!isset($pwd) || empty($pwd)) {
		$error = true;
		$errorMsgs[] = "Password is empty.";
	}

	if (!$error) {
		$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

		$pwdHash = password_hash($pwd, PASSWORD_BCRYPT);
		/* Send login request to server */
		$request = array();
		$request['type'] = "Login";
		$request['username'] = $user;
		$request['password'] = $pwdHash;
		$response = $client->send_request($request);

		echo $response;
	}
	else if ($error) {
		foreach ($errorMsgs as $error) {
			echo $error . '<br>';
			error_log($error, 3, "error.log");
		}
	}
}
?>
