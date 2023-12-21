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
		try {
            $client = new rabbitMQClient("testRabbitMQ.ini", "testServer");
        } catch (Exception $e) {
            $client = new rabbitMQClient("testRabbitMQ.ini", "secondaryServer");
        }
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
			// Cookie attributes
			$name = "user_id";
			$value = $data->{"cipher_text"};
			$expires_or_options = time() + 3600;
			$path = "/";
			$domain = "";
			$secure = false;
			$http_only = false;

			// Set a session cookie to persist authentication
			setcookie($name, $value, $expires_or_options, $path, $domain, $secure, $http_only);

			// Redirect to the dashboard
			header("Location: dashboard.php");
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
