<?php
ini_set('display_errors', 1);

// Include RabbitMQ client and other required files
require_once('../rabbitmq_lib/path.inc');
require_once('../rabbitmq_lib/get_host_info.inc');
require_once('../rabbitmq_lib/rabbitMQLib.inc');

$gameName = isset($_GET['name']) ? urldecode($_GET['name']) : "Unknown Game";

/* Get User ID */
// Prepare RabbitMQ client
$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

/* Game Review Post Request */

// Form submission handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	//$userId = 1; // Replace with actual user ID from your session or user management system
	$gameRating = $_POST['rating'];
	$reviewText = $_POST['review'];
	$userId = $_POST['userId'];
	$appId = $_POST['appId'];

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
		echo '<script>window.location.href = "./review.php?appid=' . $appId . '&name=' . urlencode($gameName) . '";</script>';
	} else {
		echo "<script>alert(\"$response\");</script>";
	}
}

?>
