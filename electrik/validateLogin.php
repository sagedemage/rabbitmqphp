<?php
ini_set('display_errors', 1);

require_once('../rabbitmq_lib/path.inc');
require_once('../rabbitmq_lib/get_host_info.inc');
require_once('../rabbitmq_lib/rabbitMQLib.inc');


/* Client */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
	echo '<script>console.log("Processing POST request for login");</script>';
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
		echo '<script>console.log("No errors found in user input, proceeding with RabbitMQ client setup");</script>';
        $client = new rabbitMQClient("testRabbitMQ.ini", "testServer");
        echo '<script>console.log("RabbitMQ client initialized");</script>';

		/* Send login request to server */
		$request = array();
		$request['type'] = "Login";
		$request['username'] = $user;
		$request['password'] = $pwd;

        $response = $client->send_request($request);

		// Only encrypt the value of the cookie
		$data = json_decode($response);

		// Check if the login response is successful, and then set a session cookie
		if ($data->{"msg"} === "Authentication successful.") {

			header("Location: twoFactorVerify.php?user_id=" . urlencode($user));
			// Redirect to the 2FA verification page
			exit;
		}
		else if ($data->{"msg"} === "Authentication failed. Invalid username or password.") {
			// Display a popup message for invalid username or password
			echo '<script>alert("Invalid Username or Password. Please Try Again.");</script>';
			echo '<script>window.location.href = "./login.php";</script>';
		}
	} 

} else if ($error) {
	foreach ($errorMsgs as $error) {
		echo $error . '<br>';
		error_log($error, 3, "error.log");
	}
}
?>
