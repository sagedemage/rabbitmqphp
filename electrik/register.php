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
          <div class="form-group mb-3">
            <label for="exampleInputEmail1">Email address</label>
            <input type="email" class="form-control" id="email" aria-describedby="email" placeholder="Enter email" name="email" required>
		  </div>
		 <div class="form-group mb-3">
            <label for="exampleInputEmail1">Username</label>
            <input type="text" class="form-control" id="id" aria-describedby="id" placeholder="Enter Username" name="id" required>
          </div>
          <div class="form-group mb-3">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Enter Password" name="pwd" required>
		  </div>
		  <div class="form-group mb-3">
            <label for="exampleInputPassword1">Confirm Password</label>
            <input id="cfrmpwd" type="password" class="form-control" placeholder="Confirm Password" name="cfrmpwd" required>
          </div>
          <button type="submit" class="btn btn-primary" name="confirm">Submit</button>
          <a href="./index.php" class="btn btn-secondary">Cancel</a>
        </form>
      </div>
    </div>
  </div>

  <!-- Include the common footer -->
  <?php include('footer.php'); ?>
</body>

</html>
