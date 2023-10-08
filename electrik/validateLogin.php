<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['id'];
    $pwd = $_POST['pwd'];
    $error = false;
    $errorMsg = "";

    // Validate username or email (allowing alphanumeric characters, hyphens, and spaces)
    if (empty($user)) {
        $error = true;
        $errorMsg = "Username is empty.";
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $user)) {
        $error = true;
        $errorMsg = "Invalid user id format.";
    }

    if (empty($pwd)) {
        $error = true;
        $errorMsg = "Password is empty.";
    } elseif (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,}$/', $pwd)) {
        $error = true;
        $errorMsg = "Invalid password format.";
    }

    if (!$error) {
        $db = new mysqli('127.0.0.1', 'testUser', 'test', 'testdb');

        if ($db->connect_error) {
            echo "Failed to connect to the database: " . $db->connect_error;
            exit(0);
        }

        // Use prepared statement to avoid SQL injection
        $request = "SELECT username, passHash FROM Users WHERE username = ?";

        $stmt = $db->prepare($request);

        if ($stmt) {
            $stmt->bind_param("s", $user);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows === 1) {
                    $stmt->bind_result($username, $passHash);
                    $stmt->fetch();
                    $stmt->close();

                    // Verify the password (assuming you have stored passwords securely using password_hash)
                    if (password_verify($pwd, $passHash)) {
                        // Authentication successful
                        echo "Authentication successful for user: " . $username;
                    } else {
                        // Authentication failed
                        echo "Authentication failed. Invalid username or password.";
                    }
                } else {
                    echo "User not found.";
                }
            } else {
                echo "Login failed. Please try again later.";
            }
        } else {
            echo "Error in preparing the statement.";
        }

        // Close the database connection
        $db->close();
    } else {
        echo $errorMsg;
    }
}
?>
