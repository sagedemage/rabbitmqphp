<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cfm_pwd = $_POST['confirm_password'];
    $error = false;
    $errorMsg = "";

    // email validation
    if (empty($email)) {
        $error = true;
        $errorMsg = "Email is empty.";
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $errorMsg = "Invalid email format.";
    }

    // username validation
    if (empty($username)) {
        $error = true;
        $errorMsg = "Username is empty.";
    }

    // password validation
    if (empty($password) || empty($cfm_pwd)) {
        $error = true;
        $errorMsg = "Password is empty.";
    }

    // check if password match
    else if ($password !== $cfm_pwd) {
        $error = true;
        $errorMsg = "Passwords do not match.";
    }

    if (!$error) {
	$passHash = password_hash($pwd, PASSWORD_DEFAULT);
	$host = "localhost";
	$user = "admin";
	$pass = "adminPass";
	$db_name = "ProjectDB";
        $db = new mysqli($host, $user, $pass, $db_name);

        if ($db->connect_error) {
            echo "Failed to connect to the database: " . $db->connect_error;
            exit(0);
        }

        $request = "INSERT INTO Users (username, email, passHash) VALUES (?, ?, ?)";
        $stmt = $db->prepare($request);

        if ($stmt) {
            $stmt->bind_param("sss", $username, $email, $passHash);
            if ($stmt->execute()) {
                echo "Registration successful!";
            } else {
                echo "Registration failed. Please try again later.";
            }

            if ($stmt->errno != 0) {
                echo "Failed to execute query:" . PHP_EOL;
                echo __FILE__ . ':' . __LINE__ . ": error: " . $stmt->error . PHP_EOL;
                exit(0);
            }
        } else {
            echo "Error in preparing the statement: " . $db->error;
        }

        $stmt->close();
        $db->close();
    } else {
        echo $errorMsg;
    }
}
?>
