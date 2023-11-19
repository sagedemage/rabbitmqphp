<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS stylesheet for additional styling -->
</head>
<body>

<!-- Include the common header and navbar -->
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2 style="text-align: center;>Login</h2>

    <form action="/validateLogin.php" method="post">
        <div class="mb-3">
            <label for="id" class="form-label"><b>Username</b></label>
            <input type="text" class="form-control" placeholder="Enter Username" name="id" required>
        </div>

        <div class="mb-3">
            <label for="pwd" class="form-label"><b>Password</b></label>
            <input type="password" class="form-control" placeholder="Enter Password" name="pwd" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" checked="checked" name="remember">
            <label class="form-check-label">Remember me</label>
        </div>

        <button type="submit" class="btn btn-primary" name="login">Login</button>
    </form>

    <div class="mt-3">
        <a class="btn btn-secondary" href="./index.html">Back</a>
        <span class="psw"><a href="./forgotpassword.html">Forgot password?</a></span>
        <span class="register">Don't have an account? <a href="./register.php">Sign Up</a></span>
    </div>
</div>

<!-- Include the common footer -->
<?php include('footer.php'); ?>

<!-- Bootstrap JS (optional, but required for some features) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


