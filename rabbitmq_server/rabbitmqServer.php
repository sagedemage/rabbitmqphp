#!/usr/bin/php
<?php
ini_set('display_errors', 1);

require_once('../rabbitmq_lib/path.inc');
require_once('../rabbitmq_lib/get_host_info.inc');
require_once('../rabbitmq_lib/rabbitMQLib.inc');

/* Server */
function doLogin($username, $password) {
	/* Connect to Database */
	$host = "localhost";
	$db_user = "admin";
	$db_pass = "adminPass";
	$db_name = "ProjectDB";
	$db = new mysqli($host, $db_user, $db_pass, $db_name);

	if ($db->connect_error) {
		echo "Failed to connect to the database: " . $db->connect_error;

		$db->close();
		exit(0);
	}

	/* Login the user */
	// Use prepared statement to avoid SQL injection
	$request = "SELECT username, passHash FROM Users WHERE username=? OR email=?";
	$stmt = $db->prepare($request);
	$stmt->bind_param("ss", $username, $username);
	if ($stmt->execute()) {
		// Fetch the result
		$stmt->bind_result($userId, $passHash);
		$stmt->fetch();
		$stmt->close();

		/* Password Validation */
		if (password_verify($password, $passHash)) {
			session_start(); // Start a session
			$_SESSION['user_id'] = $userId; // Store user information in the session
			header("Location: home.html"); // Redirect the user to the home page

			$db->close();
			return "Authentication successful for user: " . $userId;
			//exit; // Make sure to exit to stop further script execution
		} else {
			$db->close();
			return "Authentication failed. Invalid username or password.";
		}
	} 
	else {
		$db->close();
		return "Login failed. Please try again later.";
	}
}

function requestProcessor($request) {
	echo "received request".PHP_EOL;
	var_dump($request);
	if(!isset($request['type'])) {
		return "ERROR: unsupported message type";
	}
	switch($request['type'])
	{
	case "Login":
		return doLogin($request['username'], $request['password']);
	}
	return array("returnCode" => '0', 'message'=>"server received request and processed");
}

$server = new rabbitMQServer("testRabbitMQ.ini", "testServer");
echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
?>
