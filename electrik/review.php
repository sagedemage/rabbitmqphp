<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Reviews</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        form, #reviews {
            max-width: 500px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .review {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <?php include('navbar.php'); ?>

    <?php
    ini_set('display_errors', 1);

    // Include RabbitMQ client and other required files
    require_once('../rabbitmq_lib/path.inc');
    require_once('../rabbitmq_lib/get_host_info.inc');
    require_once('../rabbitmq_lib/rabbitMQLib.inc');

    $appId = isset($_GET['appid']) ? $_GET['appid'] : null;
    $gameName = isset($_GET['name']) ? urldecode($_GET['name']) : "Unknown Game";
    $imageUrl = isset($appId) ? "https://steamcdn-a.akamaihd.net/steam/apps/{$appId}/header.jpg" : "path/to/default/image.jpg";

    // Form submission handling
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userId = 1; // Replace with actual user ID from your session or user management system
        $gameRating = $_POST['rating'];
        $reviewText = $_POST['review'];

        // Prepare RabbitMQ client
        $client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

        // Construct request
        $request = array();
        $request['type'] = "SubmitReview";
        $request['userId'] = $userId;
        $request['appId'] = $appId;
        $request['gameRating'] = $gameRating;
        $request['reviewText'] = $reviewText;

        // Send request to server
        $response = $client->send_request($request);

        if ($response === "Review submission success.") {
            echo '<script>alert("Review submitted successfully!");</script>';
        } else {
            echo "<script>alert(\"$response\");</script>";
        }
    }

    // Request reviews for the current game
    $client = new rabbitMQClient("testRabbitMQ.ini", "testServer");
    $request = array();
    $request['type'] = "GetReviews";
    $request['appId'] = $appId;
    $reviews = $client->send_request($request);
    ?>

    <div>
        <img src="<?php echo $imageUrl; ?>" alt="<?php echo $gameName; ?>" style="width: 100%; height: auto;">
        <h1><?php echo $gameName; ?></h1>
    </div>

    <h1>Game Review Form</h1>

    <form action="#" method="post" id="reviewForm">
        <input type="hidden" id="gameId" name="gameId" value="<?php echo $appId; ?>">

        <label for="rating">Rating:</label>
        <select id="rating" name="rating" required>
            <option value="1">1 - Awful</option>
            <option value="2">2 - Poor</option>
            <option value="3">3 - Average</option>
            <option value="4">4 - Good</option>
            <option value="5">5 - Excellent</option>
        </select>

        <label for="review">Review:</label>
        <textarea id="review" name="review" rows="4" required></textarea>

        <button type="submit">Submit Review</button>
    </form>

    <h1>Game Reviews</h1>
    <div id="reviews">
        <h2>Reviews for <span id="currentGame"><?php echo $gameName; ?></span>:</h2>
        <?php
        echo "Current appId: " . $_GET['appid'];

        if ($reviews) {
            foreach ($reviews as $review) {
                echo '<div class="review">';
                echo '<strong>' . htmlspecialchars($review['userName']) . '</strong> - Rating: ' . htmlspecialchars($review['gameRating']) . '<br>';
                echo htmlspecialchars($review['reviewText']);
                echo '</div>';
            }
        } else {
            echo '<p>No reviews available for this game.</p>';
        }
        ?>
    </div>

</body>
</html>
