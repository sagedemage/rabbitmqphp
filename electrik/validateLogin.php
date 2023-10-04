<?php
$id = $_POST['id'];
$pwd = $_POST['pwd'];
$error = false;

if (isset($id) && isset($pwd)) {
    // Validate username or email (allowing alphanumeric characters, hyphens, and spaces)
    if (!preg_match("/^[a-zA-Z0-9-' ]*$/", $id)) {
        $error = true;
    }

    if (!$error) {
        $db = new mysqli('127.0.0.1', 'testUser', 'test', 'testdb');

        if ($db->connect_error) {
            echo "Failed to connect to the database: " . $db->connect_error;
            exit(0);
        }

        // Use prepared statement to avoid SQL injection
        $stmt = $db->prepare("SELECT id, passHash FROM Users WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();

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
}
?>
