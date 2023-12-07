<!DOCTYPE html>

<html lang="en">
<head>
	<title>Page Title</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="style.css">
</head>
<body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!--
<script src="upcomingGames.js"></script>
-->
<?php include('navbar.php'); ?>

<!-- Bootstrap Carousel -->
<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
	<div class="carousel-inner" id="carouselInner">
<?php

ini_set('display_errors', 1);

require_once('../rabbitmq_lib/path.inc');
require_once('../rabbitmq_lib/get_host_info.inc');
require_once('../rabbitmq_lib/rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");
$request = array();
$request['type'] = "GetAppList";
$response = $client->send_request($request);

echo '<pre>';
var_dump($response);
echo '</pre>';

$response_json = json_encode($response);
$jsonResponse = json_decode($response_json, true);

echo '<pre>';
var_dump($jsonResponse);
echo '</pre>';

if (isset($jsonResponse['response']['apps']) && is_array($jsonResponse['response']['apps'])) {
    echo '<table>';
    foreach ($jsonResponse['response']['apps'] as $app) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($app['appid']) . '</td>';
        echo '<td>' . htmlspecialchars($app['last_modified']) . '</td>';
        echo '<td>' . htmlspecialchars($app['name']) . '</td>';
        echo '<td>' . htmlspecialchars($app['price_change_number']) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo "No apps found in the response.";
}}
?>
	</div>
	<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Previous</span>
	</button>
	<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Next</span>
	</button>
</div>

<!-- Cards below the carousel -->
<div class="container mt-5">
	<div class="card-group">
		<div class="card">
			<img src="images/image1.jpg" class="card-img-top" alt="Card 1" style="height: 18rem;">
			<div class="card-body">
				<h5 class="card-title">Card 1</h5>
				<p class="card-text">Some text for Card 1.</p>
			</div>
		</div>
		<div class="card">
			<img src="images/image2.jpg" class="card-img-top" alt="Card 2" style="height: 18rem;">
			<div class="card-body">
				<h5 class="card-title">Card 2</h5>
				<p class="card-text">Some text for Card 2.</p>
			</div>
		</div>
		<div class="card">
			<img src="images/image4.jpg" class="card-img-top" alt="Card 3" style="height: 18rem;">
			<div class="card-body">
				<h5 class="card-title">Card 3</h5>
				<p class="card-text">Some text for Card 3.</p>
			</div>
		</div>
	</div>
</div>

<div class="footer">
   &copy; 2023 Electrik.com. All rights reserved. <a class="terms-link" href="terms.php">Terms of Service</a>
</div>

</body>
</html>
