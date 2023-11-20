<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Navbar</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="style.css"> <!-- Link to your CSS stylesheet for additional styling -->
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-custom">
  <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Electrik</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                  <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'index.php') echo 'active'; ?>" href="index.php">Home</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'aboutus.php') echo 'active'; ?>" href="aboutus.php">About Us</a>
              </li>
          </ul>
          <form class="d-flex ms-auto"> <!-- Use "ms-auto" to push the form to the right -->
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success" type="submit">Search</button>
          </form>
          <ul class="navbar-nav"> <!-- Add a new unordered list for "Login" and "Register" links -->
              <li class="nav-item">
                  <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'register.php') echo 'active'; ?>" href="register.php">Register</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'login.php') echo 'active no-border'; ?>" href="login.php">Login</a>
              </li>
              
          </ul>
      </div>
  </div>
</nav>

<!-- Bootstrap JS (optional, but required for some features) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


