<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST['id'];
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
    $cfrmpwd = $_POST['cfrmpwd'];
    $error = false;
    $errorMsg = "";

    if (empty($user)) {
        $error = true;
        $errorMsg = "Username is empty.";
    }
    if (empty($email)) {
        $error = true;
        $errorMsg = "Email is empty.";
    }
    if (empty($pwd) || empty($cfrmpwd)) {
        $error = true;
        $errorMsg = "Password is empty.";
    }
    if (!preg_match("/^[a-zA-Z-' ]*$/", $user)) {
        $error = true;
        $errorMsg = "Invalid user id format.";
    }
    if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,}$/', $pwd)) {
        $error = true;
        $errorMsg = "Invalid password format.";
    }
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
        $error = true;
        $errorMsg = "Invalid email format.";
    }
    if ($pwd !== $cfrmpwd) {
        $error = true;
        $errorMsg = "Passwords do not match.";
    }

    if (!$error) {
        $passHash = password_hash($pwd, PASSWORD_DEFAULT);
        $db = new mysqli('127.0.0.1', 'testUser', 'test', 'testdb');

        if ($db->connect_error) {
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
