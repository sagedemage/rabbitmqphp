#!/usr/bin/php
<?php
ini_set('display_errors', 1);

require_once('rabbitmq_lib/path.inc');
require_once('rabbitmq_lib/get_host_info.inc');
require_once('rabbitmq_lib/rabbitMQLib.inc');

/* Server */
function doLogin($username, $password) {
	/* Connect to Database */
	$env = parse_ini_file('env.ini');
	$host = $env["HOST"];
	$db_user = $env["MYSQL_USERNAME"];
	$db_pass = $env["MYSQL_PASSWORD"];
	$db_name = $env["DATABASE_NAME"];
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

	/* Connect to Database */
	$env = parse_ini_file('env.ini');
	$host = $env["HOST"];
	$db_user = $env["MYSQL_USERNAME"];
	$db_pass = $env["MYSQL_PASSWORD"];
	$db_name = $env["DATABASE_NAME"];
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

function getReviews($appId) {
    // Connect to Database
    // [Use your existing database connection logic]
	/* Connect to Database */
	$env = parse_ini_file('env.ini');
	$host = $env["HOST"];
	$db_user = $env["MYSQL_USERNAME"];
	$db_pass = $env["MYSQL_PASSWORD"];
	$db_name = $env["DATABASE_NAME"];
	$db = new mysqli($host, $db_user, $db_pass, $db_name);

	if ($db->connect_error) {
		echo "Failed to connect to the database: " . $db->connect_error;
		$db->close();
		exit(0);
	}

    $request = "SELECT userId, gameRating, reviewText FROM Reviews WHERE appId = ?";
    $stmt = $db->prepare($request);
    $stmt->bind_param("i", $appId);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $reviews = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $db->close();
        return json_encode($reviews);
    } else {
        $db->close();
        return json_encode([]);
    }
}

function submitReview($userId, $appId, $gameRating, $reviewText) {
    // Connect to Database
    // [Use your existing database connection logic]
	$env = parse_ini_file('env.ini');
	$host = $env["HOST"];
	$db_user = $env["MYSQL_USERNAME"];
	$db_pass = $env["MYSQL_PASSWORD"];
	$db_name = $env["DATABASE_NAME"];
	$db = new mysqli($host, $db_user, $db_pass, $db_name);

	if ($db->connect_error) {
		echo "Failed to connect to the database: " . $db->connect_error;
		$db->close();
		exit(0);
	}

    $request = "INSERT INTO Reviews (userId, appId, gameRating, reviewText) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($request);
    $stmt->bind_param("iiss", $userId, $appId, $gameRating, $reviewText);
    if ($stmt->execute()) {
        $stmt->close();
        $db->close();
        return "Review submission success.";
    } else {
        $db->close();
        return "Review submission failed.";
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

function fetchUserEmail($username) {
	$env = parse_ini_file('env.ini');
	$host = $env["HOST"];
	$db_user = $env["MYSQL_USERNAME"];
	$db_pass = $env["MYSQL_PASSWORD"];
	$db_name = $env["DATABASE_NAME"];
	$db = new mysqli($host, $db_user, $db_pass, $db_name);

	if ($db->connect_error) {
		echo "Failed to connect to the database: " . $db->connect_error;
		$db->close();
		exit(0);
	}
    $query = "SELECT email FROM Users WHERE username = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return json_encode(array('email' => $row['email']));
    } else {
        return json_encode(array('error' => 'Email not found.'));
    }
}

function update2FACode($username, $code, $expiry) {
	$env = parse_ini_file('env.ini');
	$host = $env["HOST"];
	$db_user = $env["MYSQL_USERNAME"];
	$db_pass = $env["MYSQL_PASSWORD"];
	$db_name = $env["DATABASE_NAME"];
	$db = new mysqli($host, $db_user, $db_pass, $db_name);

	if ($db->connect_error) {
		echo "Failed to connect to the database: " . $db->connect_error;
		$db->close();
		exit(0);
	}
    $query = "UPDATE Users SET two_factor_code = ?, code_expiry = ? WHERE username = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("sss", $code, $expiry, $username);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        return json_encode(array('success' => '2FA code updated.'));
    } else {
        return json_encode(array('error' => 'Failed to update 2FA code.'));
    }
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

function getMostPopularTags() {
	// Steam API to get App List
	$env = parse_ini_file('env.ini');
	$apiKey = $env["STEAM_WEB_API_KEY"];
	$apiUrl = "https://api.steampowered.com/IStoreService/GetMostPopularTags/v1/?key=$apiKey";

	$curl = curl_init($apiUrl);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($curl);
	curl_close($curl);

	return $response;
}

function getPlayerSummaries($steamid) {
	// Steam API to get App List
	$env = parse_ini_file('env.ini');
	$apiKey = $env["STEAM_WEB_API_KEY"];
	$apiUrl = "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2/?key=$apiKey&steamids=$steamid";

	$curl = curl_init($apiUrl);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($curl);
	curl_close($curl);

	return $response;
}

function getOwnedGames($steamid) {
	// Steam API to get App List
	$env = parse_ini_file('env.ini');
	$apiKey = $env["STEAM_WEB_API_KEY"];
	$apiUrl = "https://api.steampowered.com/IPlayerService/GetOwnedGames/v1/?key=$apiKey&steamids=$steamid&include_played_free_games=true";

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
	case "GetMostPopularTags":
		return getMostPopularTags();
	case "GetPlayerSummaries":
		return getPlayerSummaries($request['steamID']);
	case "GetOwnedGames":
		return getOwnedGames($request['steamID']);
	case "GetReviews":
        return getReviews($request['appId']);
    case "SubmitReview":
        return submitReview($request['userId'], $request['appId'], $request['gameRating'], $request['reviewText']);
	case "FetchEmail":
        return fetchUserEmail($request['username']);
    case "Update2FACode":
        return update2FACode($request['username'], $request['two_factor_code'], $request['code_expiry']);
	}
	return array("returnCode" => '0', 'message'=>"server received request and processed");
}

$server = new rabbitMQServer("testRabbitMQ.ini", "testServer");
echo "DB Listener BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "DB Listener END".PHP_EOL;
?>
