<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $user = $_POST['id'];
    $pwd = $_POST['pwd'];
    $error = false;
    $errorMsgs = array();

    // Validation and sanitization code here...

    if (!isset($user) || empty($user)) {
        $error = true;
        $errorMsgs[] = "Username is empty.";
    } 
    else {
        $user = htmlspecialchars($user, ENT_QUOTES, 'UTF-8'); 
    }

    if (!isset($pwd) || empty($pwd)) {
        $error = true;
        $errorMsgs[] = "Password is empty.";
    }

    if (!$error) {
        $host = "localhost";
        $db_user = "admin";
        $db_pass = "adminPass";
        $db_name = "ProjectDB";
        $db = new mysqli($host, $db_user, $db_pass, $db_name);

        if ($db->connect_error) {
            echo "Failed to connect to the database: " . $db->connect_error;
            exit(0);
        }

        // Use prepared statement to avoid SQL injection
        $request = "SELECT username, passHash FROM Users WHERE username=? OR email=?";
        $stmt = $db->prepare($request);
        $stmt->bind_param("ss", $user, $user);
        if ($stmt->execute()) {
            // Fetch the result
            $stmt->bind_result($userId, $passHash);
            $stmt->fetch();
            $stmt->close();
            if (password_verify($pwd, $passHash)) {
                echo "Authentication successful for user: " . $userId;
                //session_start(); // Start a session
                //$_SESSION['user_id'] = $userId; // Store user information in the session
                //header("Location: home/html"); // MAKE WELCOME PAGE FOR SESSION
            } else {
                $errorMsg = "Authentication failed. Invalid username or password.";
                
            }
        } 
        else {
            $errorMsg = "Login failed. Please try again later.";
        }
        $db->close();
    }
    if ($error) {
        foreach ($errorMsgs as $error) {
            echo $error . '<br>';
            error_log($error, 3, "error.log");
        }
    }
}
?>
