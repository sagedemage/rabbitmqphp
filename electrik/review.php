<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Reviews</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="review.css">
</head>
<body>

    <?php include('dashnav.php'); ?>

    <?php
    ini_set('display_errors', 1);

    // Include RabbitMQ client and other required files
    require_once('../rabbitmq_lib/path.inc');
    require_once('../rabbitmq_lib/get_host_info.inc');
    require_once('../rabbitmq_lib/rabbitMQLib.inc');

    $appId = isset($_GET['appid']) ? $_GET['appid'] : null;
    $gameName = isset($_GET['name']) ? urldecode($_GET['name']) : "Unknown Game";
    $imageUrl = isset($appId) ? "https://steamcdn-a.akamaihd.net/steam/apps/{$appId}/header.jpg" : "path/to/default/image.jpg";

    // Request reviews for the current game
    try {
        $client = new rabbitMQClient("testRabbitMQ.ini", "testServer");
    } catch (Exception $e) {
        $client = new rabbitMQClient("testRabbitMQ.ini", "secondaryServer");
    }
    $request = array();
    $request['type'] = "GetReviews";
    $request['appId'] = $appId;
    $reviews = $client->send_request($request);
    ?>

    <div>
        <img src="<?php echo $imageUrl; ?>" alt="<?php echo $gameName; ?>" style="width: 100%; height: auto;">
        <h1><?php echo $gameName; ?></h1>
	</div>

    <h1>Game Reviews</h1>
    <div id="reviews">
		<h2>Reviews for <span id="currentGame"><?php echo $gameName; ?></span>:</h2>

	<?php
	echo '<a href="review_form.php?appid=' . $appId . '&name=' . urlencode($gameName) . '">';
	echo 'Submit Review';
	echo '</a>';
        //echo "Current appId: " . $_GET['appid'];
        $reviews = json_decode($reviews, true);
        if (is_array($reviews)) {
            foreach ($reviews as $review) {
                $userName = isset($review['userName']) ? htmlspecialchars($review['userName']) : 'Unknown User';
                echo '<div class="review">';
                echo '<strong>' . $userName . '</strong> - Rating: ' . htmlspecialchars($review['gameRating']) . '<br>';
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
