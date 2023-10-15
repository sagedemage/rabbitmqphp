<?php
ini_set('display_errors', 1); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $error = false;

    // Check if the email is valid (you should add your email validation code here)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        echo "Invalid email address.";
    }

    if (!$error) {
        // Database connection parameters
        $host = "localhost";
        $db_user = "admin";
        $db_pass = "adminPass";
        $db_name = "ProjectDB";

        // Create a database connection
        $db = new mysqli($host, $db_user, $db_pass, $db_name);

        // Generate a random token
        $tokenLength = 32; // You can adjust the length as needed
        $randomBytes = random_bytes($tokenLength);
        $token = bin2hex($randomBytes);

        // Store the token, email, and timestamp in the database
        // Insert this information into the PasswordResetTokens table
        $timestamp = date('Y-m-d H:i:s');
        $sql = "INSERT INTO PasswordResetTokens (email, token, timestamp) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sss", $email, $token, $timestamp);
            if ($stmt->execute()) {
                // Send the token to the user via email
                $resetLink = "https://electrik.com/resetpassword.php?email=" . urlencode($email) . "&token=" . urlencode($token);

                $to = $email;
                $subject = 'Password Reset';
                $message = 'Click the following link to reset your password: ' . $resetLink; 
                $headers = 'From: electrik@google.com' . "\r\n" .
                    'Reply-To: electrik@google.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

                if (mail($to, $subject, $message, $headers)) {
                    echo "A password reset link has been sent to your email.";
                } else {
                    echo "Email sending failed. Please try again later.";
                }
            } else {
                echo "Error storing token in the database. Please try again later.";
            }
            $stmt->close();
        } else {
            echo "Failed to prepare the SQL statement.";
        }

        // Close the database connection
        $db->close();
    }
}
?>
