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

			$cipher_text_raw = openssl_encrypt($userId, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);

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

        // Fetch userName for each review
		foreach ($reviews as $key => $review) {
			$userId = $review['userId'];
		
			// Debugging
			echo "Processing userID: $userId<br>";
		
			$userRequest = "SELECT username FROM Users WHERE id = ?";
			$userStmt = $db->prepare($userRequest);
		
			if (!$userStmt) {
				echo "Prepare failed: (" . $db->errno . ") " . $db->error . "<br>";
				continue;
			}
		
			$userStmt->bind_param("i", $userId);
			if ($userStmt->execute()) {
				$userResult = $userStmt->get_result();
				if ($userRow = $userResult->fetch_assoc()) {
					$reviews[$key]['userName'] = $userRow['username'];
					echo "Found user: " . $userRow['username'] . "<br>";
				} else {
					$reviews[$key]['userName'] = 'Unknown User';
					echo "No user found for userID: " . $userId . "<br>";
				}
				$userStmt->close();
			} else {
				echo "Execute failed: (" . $userStmt->errno . ") " . $userStmt->error . "<br>";
				$reviews[$key]['userName'] = 'Unknown User';
			}
		}
		


        $stmt->close();
        $db->close();
        return json_encode($reviews);
    } else {
        $stmt->close();
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

function updateSteamID($userId, $steamID) {
    // Connect to Database
    $env = parse_ini_file('env.ini');
    $host = $env["HOST"];
    $db_user = $env["MYSQL_USERNAME"];
    $db_pass = $env["MYSQL_PASSWORD"];
    $db_name = $env["DATABASE_NAME"];
    $db = new mysqli($host, $db_user, $db_pass, $db_name);

    if ($db->connect_error) {
        echo "Failed to connect to the database: " . $db->connect_error;
        $db->close();
        return "Failed to connect to the database.";
    }

    $request = "UPDATE Users SET steamID = ? WHERE id = ?";
    $stmt = $db->prepare($request);
    $stmt->bind_param("ii", $steamID, $userId);
    
    if ($stmt->execute()) {
        $stmt->close();
        $db->close();
        return "SteamID updated successfully.";
    } else {
        $db->close();
        return "Failed to update SteamID.";
    }
}

function getUserSteamID($userId) {
    $env = parse_ini_file('env.ini');
    $host = $env["HOST"];
    $db_user = $env["MYSQL_USERNAME"];
    $db_pass = $env["MYSQL_PASSWORD"];
    $db_name = $env["DATABASE_NAME"];
    $db = new mysqli($host, $db_user, $db_pass, $db_name);

    if ($db->connect_error) {
        echo "Failed to connect to the database: " . $db->connect_error;
        $db->close();
        return "Database connection failed.";
    }

    $request = "SELECT steamID FROM Users WHERE id = ?";
    $stmt = $db->prepare($request);
    $stmt->bind_param("i", $userId);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $steamID = $row['steamID'];
            $stmt->close();
            $db->close();
            return $steamID;
        }
    } else {
        $stmt->close();
        $db->close();
        return "Failed to retrieve SteamID.";
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
	$apiUrl = "https://api.steampowered.com/IStoreService/GetAppList/v1/?key=$apiKey&max_results=100";

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
	$apiUrl = "https://api.steampowered.com/IPlayerService/GetOwnedGames/v1/?key=$apiKey&steamid=$steamid&include_played_free_games=true";

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
	case "UpdateSteamID":
		return updateSteamID($request['userId'], $request['steamID']);
	case "GetUserSteamID":
		return getUserSteamID($request['userId']);
	}
	return array("returnCode" => '0', 'message'=>"server received request and processed");
}

$server = new rabbitMQServer("testRabbitMQ.ini", "testServer");
echo "DB Listener BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "DB Listener END".PHP_EOL;
?>
