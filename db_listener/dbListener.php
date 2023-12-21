#!/usr/bin/php
<?php
ini_set('display_errors', 1);

require_once('rabbitmq_lib/path.inc');
require_once('rabbitmq_lib/get_host_info.inc');
require_once('rabbitmq_lib/rabbitMQLib.inc');

require_once "PHPMailer/src/PHPMailer.php";
require_once "PHPMailer/src/SMTP.php";
require_once "PHPMailer/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
/* Server */

function generateRandomCode($length = 6) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomCode = '';
    for ($i = 0; $i < $length; $i++) {
        $randomCode .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomCode;
}

function doLogin($username, $password) {
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

    // Login the user
    $stmt = $db->prepare("SELECT id, username, passHash FROM Users WHERE username=? OR email=?");
    $stmt->bind_param("ss", $username, $username);
    if (!$stmt->execute()) {
        $db->close();
        return json_encode(["msg" => "Login failed. Please try again later."]);
    }

    $stmt->bind_result($id, $userId, $passHash);
    if (!$stmt->fetch()) {
        $stmt->close();
        $db->close();
        return json_encode(["msg" => "Authentication failed. Invalid username or password."]);
    }
    $stmt->close();

    // Password Validation
    if (!password_verify($password, $passHash)) {
        $db->close();
        return json_encode(["msg" => "Authentication failed. Invalid username or password."]);
    }

    // User is authenticated, proceed with 2FA process
    $twoFactorCode = generateRandomCode();
    $expiryTime = new DateTime();
    $expiryTime->add(new DateInterval('PT10M')); // Code valid for 10 minutes

    // Fetch user's email
    $emailQuery = "SELECT email FROM Users WHERE username = ?";
    $emailStmt = $db->prepare($emailQuery);
    $emailStmt->bind_param("s", $username);
    $emailStmt->execute();
    $emailResult = $emailStmt->get_result();
    if ($emailRow = $emailResult->fetch_assoc()) {
        $userEmail = $emailRow['email'];
    } else {
        // Handle case where email is not found
        $emailStmt->close();
        $db->close();
        return json_encode(["msg" => "Email not found for user."]);
    }
    $emailStmt->close();

    // Update 2FA code in the database
    $updateQuery = "UPDATE Users SET two_factor_code = ?, code_expiry = ? WHERE username = ?";
    $updateStmt = $db->prepare($updateQuery);
    $expiryFormatted = $expiryTime->format('Y-m-d H:i:s');
    $updateStmt->bind_param("sss", $twoFactorCode, $expiryFormatted, $username);
    if (!$updateStmt->execute()) {
        // Handle update failure
        $updateStmt->close();
        $db->close();
        return json_encode(["msg" => "Failed to update 2FA code."]);
    }
    $updateStmt->close();

	// Send 2FA code via email
	$mail = new PHPMailer(true);
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'ssl';
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 465;
	$mail->Username = "electrik135@gmail.com"; // Your Gmail address
	$mail->Password = "oxwlopjxckcdbrqq"; // Your Gmail app password

	$mail->SetFrom("electrik135@gmail.com"); // Email shown in "From" field.
	$mail->AddAddress($userEmail); // User's email address

	$mail->Subject = "Your 2FA Code";
	$mail->Body = "Your 2FA code is: $twoFactorCode";

	if (!$mail->Send()) {
		// Handle email sending failure
		return json_encode(["msg" => "Failed to send 2FA code. Mailer Error: " . $mail->ErrorInfo]);
	}

    // Encrypt data for secure transmission
    $cipher = "AES-128-CBC";
    $key = $env["OPENSSL_KEY"];
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $cipher_text_raw = openssl_encrypt($id, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $cipher_text_raw, $key, $as_binary=true);
    $cipher_text = base64_encode($iv.$hmac.$cipher_text_raw);

    // Prepare response
    $data = ["msg" => "Authentication successful.", "cipher_text" => $cipher_text];
    header('Content-type: application/json');
    $db->close();
    return json_encode($data);
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

		/* Set username for each review */
		foreach ($reviews as $key => $review) {
			$userId = $review['userId'];

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

function verify2FACode($username, $code) {
    $env = parse_ini_file('env.ini');
    $host = $env["HOST"];
    $db_user = $env["MYSQL_USERNAME"];
    $db_pass = $env["MYSQL_PASSWORD"];
    $db_name = $env["DATABASE_NAME"];
    $db = new mysqli($host, $db_user, $db_pass, $db_name);

	
    // Fetch the stored 2FA code and expiry from the database
    $stmt = $db->prepare("SELECT id, two_factor_code, code_expiry FROM Users WHERE username = ?");
	$stmt->bind_param("s", $username);
    $stmt->execute();
	$stmt->bind_result($id, $two_factor_code, $code_expiry);
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Inside the verification section
		$currentDateTime = new DateTime();
		$expiryDateTime = new DateTime($row['code_expiry']);

		// Debugging: Remove these lines after debugging
		echo 'Current Time: ' . $currentDateTime->format('Y-m-d H:i:s') . '<br>';
		echo 'Expiry Time: ' . $expiryDateTime->format('Y-m-d H:i:s') . '<br>';
		echo 'Database Code: ' . $row['two_factor_code'] . '<br>';
		echo 'User Code: ' . $code . '<br>';
		
        if ($code === trim($row['two_factor_code']) && $currentDateTime < new DateTime($row['code_expiry'])) {
			// Code is correct and not expired
			// Encrypt data for secure transmission
    		$cipher = "AES-128-CBC";
    		$key = $env["OPENSSL_KEY"];
    		$ivlen = openssl_cipher_iv_length($cipher);
    		$iv = openssl_random_pseudo_bytes($ivlen);
    		$cipher_text_raw = openssl_encrypt($id, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
    		$hmac = hash_hmac('sha256', $cipher_text_raw, $key, $as_binary=true);
			$cipher_text = base64_encode($iv.$hmac.$cipher_text_raw);

			echo 'Success!';
			$data = ["msg" => "2FA verification successful", "cipher_text" => $cipher_text];
			header('Content-type: application/json');
			$db->close();
			return json_encode($data);

        } else {
			echo 'Error!';
            return json_encode(["success" => false, "msg" => "Invalid or expired code."]);
        }
    } else {
        return json_encode(["success" => false, "msg" => "User not found."]);
    }
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
        return $row['email'];
    } else {
        return null;
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
	$apiUrl = "https://api.steampowered.com/IPlayerService/GetOwnedGames/v1/?key=$apiKey&steamid=$steamid&include_played_free_games=true&include_appinfo=true";

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
	case "UpdateSteamID":
		return updateSteamID($request['userId'], $request['steamID']);
	case "GetUserSteamID":
		return getUserSteamID($request['userId']);
	case "Verify2FACode":
        return verify2FACode($request['username'], $request['twoFactorCode']);
	}
	return array("returnCode" => '0', 'message'=>"server received request and processed");
}

$server = new rabbitMQServer("testRabbitMQ.ini", "testServer");
echo "DB Listener BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "DB Listener END".PHP_EOL;
?>
