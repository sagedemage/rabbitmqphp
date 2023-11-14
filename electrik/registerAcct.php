<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include('navbar.php'); ?>

    <h2>Register Account</h2>

    <form action="/register.php" method="post" accept-charset="utf-8">
        <!-- Route to validateLogin.php for checking -->

        <div class="container form-group">
            <label for="id"><b>Username</b></label>
            <input id="id" type="text" placeholder="Enter Username" name="id" required>
        </div>

        <div class="container form-group">
            <label for="email"><b>Email</b></label>
            <input id="email" type="text" placeholder="Enter Email" name="email" required>
        </div>

        <div class="container form-group">
            <label for="pwd"><b>Password</b></label>
            <input id="pwd" type="password" placeholder="Enter Password" name="pwd" required>
        </div>

        <div class="container form-group">
            <label for="cfrmpwd"><b>Confirm Password</b></label>
            <input id="cfrmpwd" type="password" placeholder="Confirm Password" name="cfrmpwd" required>
        </div>

        <div class="container" style="background-color:#f1f1f1">
            <button type="submit" name="confirm">Submit</button>
            <button type="button" class="cancelbtn" onclick="window.location.href='./index.php'">Cancel</button>
        </div>

    </form>

    <?php include('footer.php'); ?>
</body>

</html>



