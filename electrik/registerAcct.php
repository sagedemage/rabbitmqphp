<?php
    if (isset($id) && isset($pwd) && isset($email)){
        $id = $_POST['id'];
        $email = $_post['email'];
        $pwd = $_POST['pwd'];
        $cfrmpwd = $_POST['cfrmpwd'];
        $error = false;
        if (empty($id)){
            $error = true;
            $errorMsg = "Username is empty.";
        }
        if (empty($email)){
            $error = true;
            $errorMsg = "Email is empty.";
        }
        if (empty($pwd)){
            $error = true;
            $errorMsg = "Password is empty.";
        }
        if (empty($pwd) || empty($cfrmpwd)){
            $error = true;
            $errorMsg = "Password is empty.";
        }
        if (!preg_match("/^[a-zA-Z-' ]*$/", $id)){
            $error = true;
            $errorMsg = "Invalid user id format.";
        }
        if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,}$/', $pwd)){
            $error = true;
            $errorMsg = "Invalid password format.";
        }
        if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)){
            $error = true;
            $errorMsg = "Invalid email format.";
        }
        if (!preg_match("/^[a-zA-Z-' ]*$/", $id)){
            $error = true;
            $errorMsg = "Invalid user id format.";
        }
        if ($pwd != $cfrmpwd){
            $error = true;
            $errorMsg = "Passwords do not match.";
        }
    }
    if(!$error){
        $db = new mysqli('127.0.0.1', 'testUser', 'test', 'testdb');

        if ($db->errno != 0) {
            echo "failed to connect database: ". $mydb->error . PHP_EOL;
            exit(0);
        }
        
        $request = "INSERT INTO Users VALUES id, email, passHash";
        $response = $mydb->query($query);
        if ($db->errno != 0) {

            echo "failed to execute query:".PHP_EOL;
            echo __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
            exit(0);
        }	
    }
?>
        