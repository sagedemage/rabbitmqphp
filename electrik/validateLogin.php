<?php
ini_set('display_errors', 1);

require_once('../rabbitmq_lib/path.inc');
require_once('../rabbitmq_lib/get_host_info.inc');
require_once('../rabbitmq_lib/rabbitMQLib.inc');

function generateRandomCode($length = 6) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomCode = '';
    for ($i = 0; $i < $length; $i++) {
        $randomCode .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomCode;
}

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

		echo '<script>console.log("Sending login request to server via RabbitMQ");</script>';
        $response = $client->send_request($request);
        echo '<script>console.log("Received response from server: ' . htmlspecialchars(json_encode($response)) . '");</script>';

		// Only encrypt the value of the cookie
		$data = json_decode($response);

		// Check if the login response is successful, and then set a session cookie
		if ($data->{"msg"} === "Authentication successful.") {
			echo '<script>console.log("Authentication successful, generating 2FA code");</script>';
			// Generate 2FA code
			$twoFactorCode = generateRandomCode();
			$expiryTime = new DateTime();
			$expiryTime->add(new DateInterval('PT10M')); // Code valid for 10 minutes

			// Fetch user's email via RabbitMQ
			$emailRequest = array(
				'type' => 'FetchEmail',
				'username' => $user
			);
			echo '<script>console.log("Fetching email");</script>';
			$userEmail = $client->send_request($emailRequest);
			echo '<script>console.log("Fetched now updating 2FA");</script>';
			// Update 2FA code in database via RabbitMQ
			$update2FARequest = array(
				'type' => 'Update2FACode',
				'username' => $user,
				'two_factor_code' => $twoFactorCode,
				'code_expiry' => $expiryTime->format('Y-m-d H:i:s')
			);

			echo '<script>console.log("Updated 2FA");</script>';
			$client->send_request($update2FARequest);
			echo '<script>console.log("Done, now preparing email");</script>';
			// Send 2FA code via email using PHPMailer
			$mail = new PHPMailer\PHPMailer\PHPMailer();

			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = 'ssl';
			$mail->Host = "smtp.gmail.com";
			$mail->Port = 465;
			$mail->Username = "electrik135@gmail.com"; // Your Gmail address
			$mail->Password = "xudm dhdd nvjx wacl"; // Your Gmail app password

			$mail->SetFrom("electrik135@gmail.com"); // Email shown in "From" field.
			$mail->AddAddress($userEmail); // User's email address

			$mail->Subject = "Your 2FA Code";
			$mail->Body = "Your 2FA code is: $twoFactorCode";

			echo '<script>console.log("Sending");</script>';
			if(!$mail->Send()) {
				echo "Mailer Error: " . $mail->ErrorInfo;
			} else {
				echo "2FA code sent to your email.";
			}

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
