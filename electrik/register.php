<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register Account</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css"> <!-- Link to your CSS stylesheet for additional styling -->
</head>
<body>

<!-- Include the common header and navbar -->
<?php include('navbar.php'); ?>

<div class="container mt-5 d-flex justify-content-center align-items-center">
  <div class="card">
     <div class="card-body">
        <h2 class="card-title text-center">Register Account</h2>

        <form action="/user_register.php" method="post" accept-charset="utf-8">
           <div class="mb-3">
              <label for="id" class="form-label"><b>Username</b></label>
              <input id="id" type="text" class="form-control" placeholder="Enter Username" name="id" required>
           </div>

           <div class="mb-3">
              <label for="email" class="form-label"><b>Email</b></label>
              <input id="email" type="text" class="form-control" placeholder="Enter Email" name="email" required>
           </div>

           <div class="mb-3">
              <label for="pwd" class="form-label"><b>Password</b></label>
              <input id="pwd" type="password" class="form-control" placeholder="Enter Password" name="pwd" required>
           </div>

           <div class="mb-3">
              <label for="cfrmpwd" class="form-label"><b>Confirm Password</b></label>
              <input id="cfrmpwd" type="password" class="form-control" placeholder="Confirm Password" name="cfrmpwd" required>
           </div>

           <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary" name="confirm">Submit</button>
              <a href="./index.php" class="btn btn-secondary">Cancel</a>
           </div>
        </form>
     </div>
  </div>
</div>

<!-- Include the common footer -->
<?php include('footer.php'); ?>
<div class="footer">
  &copy; 2023 Electrik.com. All rights reserved. <a class="terms-link" href="terms.php">Terms of Service</a>
</div>

<!-- Bootstrap JS (optional, but required for some features) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


