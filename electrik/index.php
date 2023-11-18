<!DOCTYPE html>
<html lang="en">
<head>
  <title>Page Title</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.2/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-oVf3yoD1Q81a1PyRZXf+VdCmbso9g2U5MWZtUwnlfxI5cUJVOFl5WKIbShc6BbQ" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-lhQfs5gIvKGp8r+8Em+RmA8w2/3cGLCnUc9/7IeDJdzmfa/iRs2t5u8Zn6QAr4p" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.2/js/bootstrap.min.js" integrity="sha384-e3r0CxPrMZl2UcKc6lAt/koyToT5FSAu4b7/WFg3IkKBAfxyFsXcAnO9DDCpHEhL" crossorigin="anonymous"></script>

  
  <script>
  $(document).ready(function () {
    $('#carouselExample').carousel();
  });
</script>


</head>
<body>

<?php include('navbar.php'); ?>





<?php
session_start();

if (isset($_SESSION['USER_ID'])) {
  echo "You are logged in as: " . $_SESSION['USER_ID'];
} else {
  echo "You are not logged in.";
}
?>

<?php include('footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>




</body>
</html>



