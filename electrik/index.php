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

<?php include('navbar.php'); ?>

<div class="container mt-5">
   <h1 class="text-center display-4 fw-bold text-primary">Review Games on Steam</h1>
</div>

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

if (is_string($response)) {
    $jsonResponse = json_decode($response);
    if (json_last_error() === JSON_ERROR_NONE && isset($jsonResponse->response->apps)) {
        foreach ($jsonResponse->response->apps as $index => $app) {
            $appId = $app->appid;
            $gameName = $app->name;
            $imageUrl = "https://steamcdn-a.akamaihd.net/steam/apps/{$appId}/header.jpg";
            $activeClass = ($index === 0) ? 'active' : '';
            echo '<div class="carousel-item ' . $activeClass . '">';
            echo '<img src="' . $imageUrl . '" class="d-block w-100" alt="' . $gameName . '" style="height: 25rem;">';
            echo '</div>';
        }
    } else {
        echo 'Response is not a valid JSON string.';
    }
} else {
    echo '<script>';
    echo 'console.error("Response is not a string: ", ' . json_encode($response) . ');';
    echo '</script>';
}


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

<?php include('footer.php'); ?>

</body>
</html>
