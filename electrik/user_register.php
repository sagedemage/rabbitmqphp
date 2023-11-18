<?php
ini_set('display_errors', 1);

require_once('../rabbitmq_lib/path.inc');
require_once('../rabbitmq_lib/get_host_info.inc');
require_once('../rabbitmq_lib/rabbitMQLib.inc');

function showError($errorMsg) {
	echo "<script>alert(\"$errorMsg\");</script>";
	echo '<script>window.location.href = "./register.php";</script>'; 
}

/* Client */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
	$user = $_POST['id'];
	$email = $_POST['email'];
	$pwd = $_POST['pwd'];
	$cfrmpwd = $_POST['cfrmpwd'];
	$error = false;
	$errorMsgs = array();

	// Validation
	if (isset($user) && isset($pwd) && isset($email)) {

		if (empty($user) || !isset($user)) { # MAKE AN ERROR MSG LOG HAVE TEMPLATE
			$error = true;
			$errorMsg = "Username is empty.";
			showError($errorMsg);
			
		} else {
			$user = htmlspecialchars($user, ENT_QUOTES, 'UTF-8'); # SANITIZE
		}
		if (empty($email) || !isset($email)) {
			$error = true;
			$errorMsg = "Email is empty.";
			showError($errorMsg);

		} else {
			$email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
		}
		if (empty($pwd) || !isset($pwd)) {
			$error = true;
			$errorMsg = "Password is empty.";
			showError($errorMsg);
		}
		if (empty($cfrmpwd) || !isset($cfrmpwd)) {
			$error = true;
			$errorMsg = "Confirmation Password is empty.";
			showError($errorMsg);
		}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error = true;
			$errorMsg = "Invalid email format.";
			showError($errorMsg);
		}
		if ($pwd != $cfrmpwd) {
			$error = true;
			$errorMsg = "Passwords do not match.";
			showError($errorMsg);
		}
	}
	if (!$error) {
		$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

		/* Send register request to server */
		$request = array();
		$request['type'] = "Register";
		$request['email'] = $email;
		$request['username'] = $user;
		$request['password'] = $pwd;
		$response = $client->send_request($request);

		if ($response === "Registration success.") {
			echo '<script>alert("Registration success.");</script>';
			echo '<script>window.location.href = "./login.php";</script>';
		}
		else {
			echo "<script>alert(\"$response\");</script>";
			echo '<script>window.location.href = "./register.php";</script>';
		}
	} 
}
?>
