<?php

    //Clear the session cookie
    $name = "user_id";
    $expires_or_options = time() - 3600;
    setcookie($name, "", $expires_or_options, "/");


     //Redirect the user to the homepage after logging out
     header("Location: login.html");
     exit;

?>