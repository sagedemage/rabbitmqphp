<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Two-Factor Authentication</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS stylesheet for additional styling -->
</head>

<body>
    <!-- Include the common header and navbar -->
    <?php include('navbar.php'); ?>

    <?php
    // twoFactorVerify.php
    session_start();
    require_once('path/to/db_connection_file.php'); // Include your DB connection file

    // Check if the user ID is passed
    if (!isset($_GET['user_id'])) {
        header('Location: login.php');
        exit;
    }

    $userId = $_GET['user_id'];
    $errorMsg = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userCode = $_POST['twoFactorCode'];

        // Fetch the stored code and expiry from the database
        $query = "SELECT two_factor_code, code_expiry FROM Users WHERE username = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $currentDateTime = new DateTime();

        if ($row && $userCode === $row['two_factor_code'] && $currentDateTime < new DateTime($row['code_expiry'])) {
            // Code is correct and not expired, set cookie and redirect to dashboard
            $name = "user_id";
            $value = $userId; // Consider encrypting this value
            $expires_or_options = time() + 3600; // 1 hour
            $path = "/";
            $secure = false; // Set to true if using HTTPS
            $http_only = true; // Set to true to make cookie accessible only through the HTTP protocol

            setcookie($name, $value, $expires_or_options, $path, "", $secure, $http_only);

            header("Location: dashboard.php");
            exit;
        } else {
            $errorMsg = 'Invalid or expired code. Please try again.';
        }
    }
    ?>

    <h2>Enter your 2FA Code</h2>
    <form action="twoFactorVerify.php?user_id=<?php echo htmlspecialchars($userId); ?>" method="post">
        <label for="twoFactorCode">2FA Code:</label>
        <input type="text" id="twoFactorCode" name="twoFactorCode" required>
        <button type="submit">Verify</button>
    </form>
    <?php if ($errorMsg): ?>
        <p><?php echo htmlspecialchars($errorMsg); ?></p>
    <?php endif; ?>

    <?php include('footer.php'); ?>

</body>

</html>
