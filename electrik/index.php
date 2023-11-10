

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>



