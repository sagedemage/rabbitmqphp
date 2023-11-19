<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS stylesheet for additional styling -->
</head>

<body>

    <!-- Include the common header and navbar -->
    <?php include('dashnav.php'); ?>
	
    <div class="container mt-5">
        <h1 class="text-center">Game Recommendation Portal</h1>
        <p>This is the dashboard page</p>
    </div>

    <!-- Include the common footer -->
    <?php include('footer.php'); ?>
	<div class="footer">
    &copy; 2023 Electrik.com. All rights reserved. <a class="terms-link" href="terms.php">Terms of Service</a>
</div>


    <!-- Bootstrap JS (optional, but required for some features) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Your existing script for user session verification -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function get_cookie_value(cookie_name) {
            let decodedCookie = decodeURIComponent(document.cookie);
            const cookieValue = decodedCookie
                .split("; ")
                .find((row) => row.startsWith(cookie_name + "="))
                ?.split("=")[1];
            return cookieValue;
        }
        let user_id = get_cookie_value("user_id");
        axios.post('/verify_user_session.php', {
                user_id: user_id,
            })
            .then(function(response) {
                if (response.data !== true) {
                    location.href = "/login.php";
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    </script>

</body>

</html>


