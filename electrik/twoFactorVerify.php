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
        $expires_or_options = time() + 3600;
        $path = "/";
        $domain = "";
        $secure = false;
        $http_only = false;

        setcookie($name, $value, $expires_or_options, $path, $domain, $secure, $http_only);

        header("Location: dashboard.php");
        exit;
    } else {
        $errorMsg = 'Invalid or expired code. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Two-Factor Authentication</title>
    <!-- Include any required CSS -->
</head>
<body>
    <h2>Enter your 2FA Code</h2>
    <form action="twoFactorVerify.php?user_id=<?php echo htmlspecialchars($userId); ?>" method="post">
        <label for="twoFactorCode">2FA Code:</label>
        <input type="text" id="twoFactorCode" name="twoFactorCode" required>
        <button type="submit">Verify</button>
    </form>
    <?php if ($errorMsg): ?>
        <p><?php echo htmlspecialchars($errorMsg); ?></p>
    <?php endif; ?>
</body>
</html>
git 