<?php
//start or resume the session
session_start();

//check if the user is logged in (a session variable is set)
if (isset($_SESSION['username'])) {
    //unset all of the session variables
    $_SESSION = array();

    //Destroy the session
    session_destroy();

    //Clear the session cookie
    $name = "user_id";
    $expires_or_options = time() - 3600;
    setcookie($name, "", $expires_or_options, "/");


     //Redirect the user to the homepage after logging out
     header("Location: login.html");
     exit;

}

?>