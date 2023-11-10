<!DOCTYPE html>
<html lang="en">

<head>
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.2/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-lhQfs5gIvKGp8r+8Em+RmA8w2/3cGLCnUc9/7IeDJdzmfa/iRs2t5u8Zn6QAr4p" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.2/js/bootstrap.min.js" integrity="sha384-e3r0CxPrMZl2UcKc6lAt/koyToT5FSAu4b7/WFg3IkKBAfxyFsXcAnO9DDCpHEhL" crossorigin="anonymous"></script>

  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
      background-color: rgba(0, 47, 255, 0.61);
      align-items: center;
      justify-content: center;
    }

    form {
      border: 3px solid #f1f1f1;
      width: 50%;
      margin: 0 auto;
      margin-top: 50px;
      background-color: #ffffff;
      padding: 20px;
    }

    input[type=text],
    input[type=password] {
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

    /* Add styles for the custom navbar */
    nav {
      background-color: #343a40; /* Set the background color */
      padding: 10px; /* Add padding to the navbar */
      margin-bottom: 20px; /* Add margin to separate from the content */
    }

    .navbar-brand {
      color: #ffffff !important; /* Set the text color to white */
      margin-right: 20px; /* Add margin to the right of each navbar item */
    }

    .ms-auto {
      margin-left: auto !important;
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


