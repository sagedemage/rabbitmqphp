<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... [rest of the head section] ... -->
</head>
<body>
    <!-- Include the common header and navbar -->
    <?php include('navbar.php'); ?>

    <?php
    session_start();
    require_once('../rabbitmq_lib/path.inc');
    require_once('../rabbitmq_lib/get_host_info.inc');
    require_once('../rabbitmq_lib/rabbitMQLib.inc'); // Include RabbitMQ required files

    $errorMsg = '';

    // Check if the user ID is passed and valid
    if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
        header('Location: login.php');
        exit;
    }

    $userId = $_GET['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['twoFactorCode'])) {
        $userCode = $_POST['twoFactorCode'];

        // Create a client instance and send request to RabbitMQ server
        $client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

        $request = array();
        $request['type'] = "Verify2FACode";
        $request['username'] = $userId;
        $request['twoFactorCode'] = $userCode;

        $response = $client->send_request($request);
        
        // Check the response message for success
        if (isset($response['msg']) && $response['msg'] === "2FA verification successful") {
            // Set cookie and redirect to dashboard
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
            $errorMsg = $response['msg'] ?? 'Invalid or expired code. Please try again.';
        }
    }
    ?>

    <div class="container">
        <h2>Enter your 2FA Code</h2>
        <form action="twoFactorVerify.php?user_id=<?php echo htmlspecialchars($userId); ?>" method="post">
            <label for="twoFactorCode">2FA Code:</label>
            <input type="text" id="twoFactorCode" name="twoFactorCode" required>
            <button type="submit" class="btn btn-primary">Verify</button>
        </form>
        <?php if (!empty($errorMsg)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($errorMsg); ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include('footer.php'); ?>

</body>
</html>
