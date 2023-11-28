<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Dashboard</title>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
   <link rel="stylesheet" href="style.css"> <!-- Link to your CSS stylesheet for additional styling -->
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Electrik</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                  <a class="nav-link" href="index.php">Home</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="aboutus.php">About</a>
              </li>
          </ul>

          

          <ul class="navbar-nav ms-auto"> <!-- Move the "Account" links to the far right -->
            <li class="nav-item">
               <a class="nav-link" href="logout.php">Logout</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="resetpassword.php">Reset Password</a>
            </li>
         </ul>

      </div>
  </div>
</nav>

<!-- Bootstrap JS (optional, but required for some features) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>



</body>
</html>


