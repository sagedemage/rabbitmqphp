<?php
ini_set('display_errors', 1);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    $user = $_POST['id'];
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
    $cfrmpwd = $_POST['cfrmpwd'];
        
    $error = false;
    $errorMsgs = array();
    if (isset($user) && isset($pwd) && isset($email)){

        if (empty($user) || !isset($user)){ # MAKE AN ERROR MSG LOG HAVE TEMPLATE
            $error = true;
            $errorMsgs[] = "Username is empty.";
        }
        else{
            $user = htmlspecialchars($user, ENT_QUOTES, 'UTF-8'); # SANITIZE
        }
        if (empty($email || !isset($email))){
            $error = true;
            $errorMsgs[] = "Email is empty.";
        }
        else{
            $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        }
        if (empty($pwd) || !isset($pwd)){
            $error = true;
            $errorMsgs[] = "Password is empty.";
        }
        if (empty($cfrmpwd) || !isset($cfrmpwd)){
            $error = true;
            $errorMsgs[] = "Confirmation Password is empty.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $error = true;
            $errorMsgs[] = "Invalid email format.";
        }
        if ($pwd != $cfrmpwd){
            $error = true;
            $errorMsgs[] = "Passwords do not match.";
        }
    }
    if (!$error) {
        $passHash = password_hash($pwd, PASSWORD_DEFAULT);
        $host = "localhost";
        $db_user = "admin";
        $db_pass = "adminPass";
        $db_name = "ProjectDB";
        $db = new mysqli($host, $db_user, $db_pass, $db_name);

        if ($db->connect_errno != 0) {
            echo "Failed to connect to the database: " . $db->connect_error;
            exit(0);
        }

        $request = "INSERT INTO Users (username, email, passHash) VALUES (?, ?, ?)";
        $stmt = $db->prepare($request);
        
        if ($stmt) {
            $stmt->bind_param("sss", $user, $email, $passHash);
            if ($stmt->execute()) {
                echo "Registration successful!";
            } else {
                echo "Registration failed. Please try again later.";
            }
            $stmt->close();
        } else {
            echo "Failed to prepare the SQL statement.";
        }

        $db->close();
    }
    else {
        foreach ($errorMsgs as $error) {
            echo $error . '<br>';
            error_log($error, 3, "error.log");
        }
    }
}
?>
        
