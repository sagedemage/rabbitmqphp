<?php
ini_set('display_errors', 0); // Turn off error reporting in production
require_once('path/to/logger.php');

if (isset($_GET['email'], $_GET['token'])) {
    $email = htmlspecialchars($_GET['email'], ENT_QUOTES, 'UTF-8');
    $token = $_GET['token'];

    // Check if the email and token are not empty
    if (empty($email) || empty($token)) {
        echo "Email or token is empty or invalid.";
    } else {
        // Database connection parameters
        $host = "localhost";
        $db_user = "admin";
        $db_pass = "adminPass";
        $db_name = "ProjectDB";

        // Create a database connection
        $db = new mysqli($host, $db_user, $db_pass, $db_name);

        if ($db->connect_errno != 0) {
            echo "Failed to connect to the database: " . $db->connect_error;
            exit(0);
        }

        // Query the database to check if the token and email combination is valid
        $sql = "SELECT email, timestamp FROM PasswordResetTokens WHERE email = ? AND token = ? AND timestamp >= NOW() - INTERVAL 1 HOUR";
        $stmt = $db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $email, $token);
            if ($stmt->execute()) {
                $stmt->bind_result($emailResult, $timestampResult);
                $stmt->fetch();
                $stmt->close();

                if ($emailResult === $email && $timestampResult) {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $new_password = $_POST['new_password'];
                        $cfrm_new_password = $_POST['cfrm_new_password'];

                        if (empty($new_password) || empty($cfrm_new_password)) {
                            echo "Password or confirmation password is empty.";
                        } elseif ($new_password !== $cfrm_new_password) {
                            echo "Passwords do not match.";
                        } else {
                            $passHash = password_hash($new_password, PASSWORD_DEFAULT);


                            $request = "UPDATE Users SET passHash = ? WHERE email = ?";
                            $stmt = $db->prepare($request);

                            if ($stmt) {
                                $stmt->bind_param("ss", $passHash, $email);
                                if ($stmt->execute()) {

                                    // Delete the used token
                                    $deleteTokenSql = "DELETE FROM PasswordResetTokens WHERE email = ? AND token = ?";
                                    $deleteTokenStmt = $db->prepare($deleteTokenSql);

                                    if ($deleteTokenStmt) {
                                        $deleteTokenStmt->bind_param("ss", $email, $token);
                                        if ($deleteTokenStmt->execute()) {
                                            echo "Token deleted successfully.";
                                        } else {
                                            echo "Failed to delete the token.";
                                        }
                                        $deleteTokenStmt->close();
                                    } else {
                                        echo "Failed to prepare the SQL statement for deleting the token.";
                                    }

                                    $db->close();

                                    echo "Password reset successful!";
                                } else {
                                    echo "Password reset failed. Please try again later.";
                                }
                                $stmt->close();
                            } else {
                                echo "Failed to prepare the SQL statement.";
                            }

                            $db->close();
                        }
                    }
                } else {
                    echo "Invalid or expired password reset link.";
                }
            } else {
                echo "Failed to execute the SQL statement.";
            }
        } else {
            echo "Failed to prepare the SQL statement.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        form {
            border: 3px solid #f1f1f1;
        }

        input[type=text], input[type=password] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button {
            background-color: #04AA6D;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            opacity: 0.8;
        }

        .cancelbtn {
            width: auto;
            padding: 10px 18px;
            background-color: #f44336;
        }

        .container {
            padding: 16px;
        }

        span.psw {
            float: right;
            padding-top: 16px;
        }

        @media screen and (max-width: 300px) {
            span.psw {
                display: block;
                float: none;
            }

            .cancelbtn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<h2>Reset Password</h2>
<form action="" method="post">
    <label for="new_password"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" id="new_password" name="new_password" required>

    <label for="cfrm_new_password"><b>Confirm Password</b></label>
    <input type="password" placeholder="Confirm Password" id="cfrm_new_password" name="cfrm_new_password" required>
    <button type="submit">Reset Password</button>
</form>
</body>
</html>
