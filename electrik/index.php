<!DOCTYPE html>
<html lang="en">
<head>
  <title>Page Title</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include('navbar.php'); ?>

<div class="header">
  
  <h1>ELECTRIK</h1>
  <p>Welcome to Group Electrik</p>

</div>




  <?php
  session_start();

  if (isset($_SESSION['USER_ID'])) {
    
    echo "You are logged in as: " . $_SESSION['user_id'];
  } else {
    echo "You are not logged in.";
  }
  ?>

<?php include('footer.php'); ?>

</body>
</html>
