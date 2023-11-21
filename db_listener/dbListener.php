#!/usr/bin/php
<?php
ini_set('display_errors', 1);

require_once('rabbitmq_lib/path.inc');
require_once('rabbitmq_lib/get_host_info.inc');
require_once('rabbitmq_lib/rabbitMQLib.inc');

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
			// Cookie attributes
			$cipher = "AES-128-CBC";
			$env = parse_ini_file('env.ini');

			$key = $env["OPENSSL_KEY"]; 

			// Encrypt data
			$ivlen = openssl_cipher_iv_length($cipher);

			$iv = openssl_random_pseudo_bytes($ivlen);

			$cipher_text_raw = openssl_encrypt($username, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);

			$hmac = hash_hmac('sha256', $cipher_text_raw, $key, $as_binary=true);

			$cipher_text = base64_encode($iv.$hmac.$cipher_text_raw);

			$data = [ "msg" => "Authentication successful.", "cipher_text" => $cipher_text ];

			header('Content-type: application/json');

			$db->close();
			return json_encode($data);
		} 
		else {
			$db->close();
			$data = [ "msg" => "Authentication failed. Invalid username or password." ];
			header('Content-type: application/json');
			return json_encode($data);
		}
	} 
	else {
		$db->close();
		return "Login failed. Please try again later.";
	}
}

function doRegister($email, $username, $password) {
	/* Register a User */
	$passHash = password_hash($password, PASSWORD_DEFAULT);
	$host = "localhost";
	$db_user = "admin";
	$db_pass = "adminPass";
	$db_name = "ProjectDB";
	$db = new mysqli($host, $db_user, $db_pass, $db_name);

	if ($db->connect_errno != 0) {
		echo "Failed to connect to the database: " . $db->connect_error;
		$db->close();
		exit(0);
	}

	// Username exist in database
	$request = "select * from Users where username = '$username'";
	$result = mysqli_query($db, $request);
	$count = mysqli_num_rows($result);

	if ($count > 0) {
		return "Username is already taken";
	}

	// Email exist in database
	$request = "select * from Users where email = '$email'";
	$result = mysqli_query($db, $request);
	$count = mysqli_num_rows($result);

	if ($count > 0) {
		return "Email is already taken";
	}

	$request = "INSERT INTO Users (username, email, passHash) VALUES (?, ?, ?)";
	$stmt = $db->prepare($request);
	$stmt->bind_param("sss", $username, $email, $passHash);

	if ($stmt->execute()) {
		// Redirect the user to the home page after successful registration
		$stmt->close();
		$db->close();
		return "Registration success.";
	} 

	else {
		$db->close();
		return "Registration failed. Please try again later.";
	}
}

function verifyCookieSession($username_cipher_text) {
	// Cookie attributes
	$cipher = "AES-128-CBC";

	$env = parse_ini_file('env.ini');
	$key = $env["OPENSSL_KEY"]; 

	$c = base64_decode($username_cipher_text);

	$ivlen = openssl_cipher_iv_length($cipher);
	$iv = substr($c, 0, $ivlen);
	$hmac = substr($c, $ivlen, $sha2len=32);
	$ciphertext_raw = substr($c, $ivlen+$sha2len);
	$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
	$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);

	if (hash_equals($hmac, $calcmac)) {
		return $original_plaintext;
	}

	return false;
}


function getAppList() {
	// Steam API to get App List
	$env = parse_ini_file('env.ini');
	$apiKey = $env["STEAM_WEB_API_KEY"];
	$apiUrl = "https://api.steampowered.com/IStoreService/GetAppList/v1/?key=$apiKey";

	$curl = curl_init($apiUrl);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($curl);
	curl_close($curl);

	return $response;
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
	case "Register":
		return doRegister($request['email'], $request['username'], $request['password']);
	case "Session":
		return verifyCookieSession($request['username_cipher_text']);
	case "GetAppList":
		return getAppList();
	}
	return array("returnCode" => '0', 'message'=>"server received request and processed");
}

$server = new rabbitMQServer("testRabbitMQ.ini", "testServer");
echo "DB Listener BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "DB Listener END".PHP_EOL;
?>
