<!DOCTYPE html>
<html lang="en">
<head>
    <title>Page Title</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">
    <h1>ELECTRIK</h1>
    <p>Welcome to Group Electrik</p>
</div>

<?php include('navbar.php'); ?>

<?php
session_start();
if (isset($_SESSION['USER_ID'])) {
    echo "You are logged in as: " . $_SESSION['user_id'];
} else {
    echo "You are not logged in.";
}
?>

<?php include('footer.php'); ?>

<!-- Bootstrap JS (optional, but required for some features) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



