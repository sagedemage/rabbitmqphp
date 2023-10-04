<?php

    if (isset($id) && isset($pwd)) {
        $user = $_POST['id'];
        $pwd = $_POST['pwd'];
        $error = false;
        // Validate username or email (allowing alphanumeric characters, hyphens, and spaces)
        if (empty($user)){ # MAKE AN ERROR MSG LOG HAVE TEMPLATE
            $error = true;
            $errorMsg = "Username is empty.";
        }
        if (empty($pwd)){
            $error = true;
            $errorMsg = "Password is empty.";
        }
        if (!preg_match("/^[a-zA-Z-' ]*$/", $user)){
            $error = true;
            $errorMsg = "Invalid user id format.";
        }
        if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,}$/', $pwd)){
            $error = true;
            $errorMsg = "Invalid password format.";
        }
    }
    if (!$error) {
        $passHash = password_hash($pwd, PASSWORD_DEFAULT);
        $db = new mysqli('127.0.0.1', 'testUser', 'test', 'testdb');

        if ($db->connect_error) {
            echo "Failed to connect to the database: " . $db->connect_error;
            exit(0);
        }

        // Use prepared statement to avoid SQL injection
        $request = "SELECT (username, passHash) FROM Users WHERE (?, ?)";
        $stmt = $db->prepare($request);
        $stmt->bind_param($user, $passHash);
        if ($stmt->execute()) {
            echo "Login successful!";
        }
        else {
            echo "Login failed. Please try again later.";
        }
        if ($stmt->errno != 0) {
            echo "Failed to execute query:" . PHP_EOL;
            echo __FILE__ . ':' . __LINE__ . ": error: " . $stmt->error . PHP_EOL;
            exit(0);
        }

        // Fetch the result
        $stmt->bind_result($userId, $passHash);
        $stmt->fetch();
        $stmt->close();

        // Verify the password (assuming you have stored passwords securely using password_hash)
        if (password_verify($pwd, $passHash)) {
            // Authentication successful
            echo "Authentication successful for user: " . $userId;
        } else {
            // Authentication failed
            echo "Authentication failed. Invalid username or password.";
        }

        // Close the database connection
        $db->close();
    }
?>
