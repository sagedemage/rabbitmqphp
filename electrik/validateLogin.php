<?php
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $error = false;
    $errorMsg = "";

    // Validate username or email (allowing alphanumeric characters, hyphens, and spaces)
    if (empty($username)) {
        $error = true;
        $errorMsg = "Username is empty.";
    } 
    
    if (empty($password)) {
        $error = true;
        $errorMsg = "Password is empty.";
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

	// SELECT username, password FROM users WHERE username = ?
	// SELECT username, passHash from Users WHERE username = ? OR email = ?

        $sql = "SELECT username, passHash from Users WHERE username = ?";

        // Use prepared statement to avoid SQL injection
        $stmt = $db->prepare($sql);

	if ($stmt) {
	    // bind question marks
	    $stmt->bind_param("s", $username);
	    //$stmt->bind_param("ss", $username, $username);

	    // execute statement
	    if ($stmt->execute()) {
		// bind result variables
		$stmt->bind_result($username, $passHash);

                //$stmt->store_result();
		// fetch values
                if ($stmt->fetch()) {
                    //$stmt->bind_result($username, $passHash);

                    // Verify the password (assuming you have stored passwords securely using password_hash)
                    if (password_verify($password, $passHash)) {
                        // Authentication successful
                        echo "Authentication successful for user: " . $username;
		    } 
		    else {
                        // Authentication failed
			echo "Authentication failed. Invalid username or password."; 
                    }
		} 
		else {
                    echo "User not found.";
                }
            } else {
                echo "Login failed. Please try again later.";
            }
        } else {
            echo "Error in preparing the statement.";
	}

	// close statement
        $stmt->close();

        // Close the database connection
        $db->close();
    } else {
	    echo $errorMsg;
    }
}
?>
