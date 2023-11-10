<!DOCTYPE html>
<html>
<head>
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
      
      align-items: center;
      justify-content: center;
    }

    form {
      border: 3px solid #f1f1f1;
      width: 50%; /* Adjust the width as needed */
      margin: 0 auto; /* Center the form horizontally */
      margin-top: 50px; /* Add margin at the top */
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

<?php include('navbar.php'); ?>

<div id="header"></div>
<div id="navbar"></div>

<h2 style="text-align: center;">LOGIN</h2>

<form action="/validateLogin.php" method="post">
  <div class="container">
    <label for="id"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="id" required>

    <label for="pwd"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="pwd" required>
        
    <button type="submit" name="login">Login</button>
    <label>
      <input type="checkbox" checked="checked" name="remember"> Remember me
    </label>
  </div>

  <div class="container" style="background-color:#f1f1f1">
    <button type="button" class="cancelbtn"><a href="./index.php">Back</a></button>
    <span class="psw"><a href="./forgotpassword.html">Forgot password?</a></span>
    <span class="register">Don't have an account? <a href="./register.html">Sign Up</a></span>
  </div>
</form>

<?php include('footer.php'); ?>
</body>
</html>


