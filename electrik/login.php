<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="style.css"> <!-- Link to your CSS stylesheet for additional styling -->
</head>

<body>
	<!-- Include the common header and navbar -->
	<?php include('navbar.php'); ?>

	<div class="container mt-5 d-flex justify-content-center align-items-center">
		<div class="card">
			<div class="card-body">
				<h2 class="card-title text-center">Login</h2>
				<form action="/validateLogin.php" method="post" accept-charset="utf-8">
					<div class="form-group mb-3">
						<label for="id">Username</label>
						<input id="id" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter email" name="id" required>
					</div>
					<div class="form-group mb-3">
						<label for="pwd">Password</label>
						<input id="pwd" type="password" class="form-control" placeholder="Password" name="pwd" required>
					</div>
					<div class="form-check mb-3">
						<input type="checkbox" class="form-check-input" id="exampleCheck1">
						<label class="form-check-label" for="exampleCheck1">Remember Me</label>
					</div>
					<button type="submit" class="btn btn-primary" name="login">Submit</button>
					<a href="./index.php" class="btn btn-secondary">Cancel</a>
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
