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

    <?php include('navbar.php'); ?>

    <?php
    ini_set('display_errors', 1);

	require __DIR__ . '/get_user_id.php';

    $appId = isset($_GET['appid']) ? $_GET['appid'] : null;
    $userId = isset($_GET['userid']) ? $_GET['userid'] : null;
    $gameName = isset($_GET['name']) ? urldecode($_GET['name']) : "Unknown Game";
	$imageUrl = isset($appId) ? "https://steamcdn-a.akamaihd.net/steam/apps/{$appId}/header.jpg" : "path/to/default/image.jpg";

	if (!isset($_GET['userid'])) {
		$userId = get_user_id();

		//$host  = $_SERVER['HTTP_HOST'];
		//$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = '/review_form.php?appid=' . $appId . '&name=' . urlencode($gameName) . '&userid=' . $userId . '';
		header("Location: $extra");

		//echo "<script>";
		//echo "window.location.href = \"/review_form.php?appid=" . $appId . "&name=" . urlencode($gameName) . "&userid=" . $userId . "\"";
		//echo "</script>";
	}
    ?>

    <div>
        <img src="<?php echo $imageUrl; ?>" alt="<?php echo $gameName; ?>" style="width: 100%; height: auto;">
        <h1><?php echo $gameName; ?></h1>
	</div>

	<!-- Game Review Form -->

    <h1>Game Review Form</h1>

    <form action="submit_review.php" method="post" id="reviewForm">
        <input type="hidden" id="appId" name="appId" value="<?php echo $appId; ?>">
        <input type="hidden" id="userId" name="userId" value="<?php echo $userId; ?>">

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
</body>
</html>
