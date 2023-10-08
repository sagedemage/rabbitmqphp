<?php
if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    if (empty($email || !isset($email))){
        $error = true;
        $errorMsgs[] = "Email is empty.";
    }
    else{
        $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    }

    // Check the validity of the token and email combination
    $valid_token = false; // Initialize as invalid

    // Assuming you have established a database connection
    $db = new mysqli('127.0.0.1', 'testUser', 'test', 'testdb');

    if ($db->connect_errno != 0) {
        echo "Failed to connect to the database: " . $db->connect_error;
        exit(0);
    }

    // Query the database to check if the token and email combination is valid
    $sql = "SELECT email, timestamp FROM PasswordResetTokens WHERE email = ? AND token = ? AND timestamp >= NOW() - INTERVAL 1 HOUR";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $stmt->bind_result($emailResult, $timestampResult);
    $stmt->fetch();

    if ($emailResult === $email && $timestampResult) {
        // Token and email combination is valid and not expired
        $valid_token = true;
    }

    $stmt->close();
    $db->close();

    if ($valid_token) {
        // The token is valid, allow the user to reset their password
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = $_POST['new_password'];

            if (!isset($pwd) || empty($pwd)) {
                $error = true;
                $errorMsgs[] = "Password is empty.";
            }
            else {
                if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,}$/', $pwd)) {
                    $error = true;
                    $errorMsgs[] = "Invalid password format.";
                }    
            }
            // Validate and sanitize the new password (similar to what you've done before)

            // Update the user's password in your database
            // Make sure to hash the new password before storing it

            // Optionally, invalidate or delete the token to prevent further use

            echo "Your password has been successfully reset. You can now log in with your new password.";
            exit; // Stop script execution
        }

        // Display the password reset form
        // You might want to use HTML to create the form
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Reset Password</title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
        </head>
        <body>
            <h2>Reset Password</h2>
            <form action="" method="post">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
                <button type="submit">Reset Password</button>
            </form>
        </body>
        </html>
        <?php
    } else {
        echo "Invalgiid or expired password reset link.";
    }
} else {
    echo "Invalid request.";
}
?>
