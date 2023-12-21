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

<?php include('dashnav.php'); ?>

<?php
ini_set('display_errors', 1);

require_once('../rabbitmq_lib/path.inc');
require_once('../rabbitmq_lib/get_host_info.inc');
require_once('../rabbitmq_lib/rabbitMQLib.inc');

$ownedGames = [];

if (isset($_COOKIE['steam_id'])) {
	$steam_id = $_COOKIE['steam_id'];

	// Fetch user's most-played games using the associated SteamID
	try {
		$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");
	} catch (Exception $e) {
		$client = new rabbitMQClient("testRabbitMQ.ini", "secondaryServer");
	}
	
	$request = array();
	$request['type'] = "GetOwnedGames";
	$request['steamID'] = $steam_id;
	$response = $client->send_request($request);

	if (is_string($response)) {
		$jsonResponse = json_decode($response, true);
		if (json_last_error() === JSON_ERROR_NONE && isset($jsonResponse['response']['games'])) {
			usort($jsonResponse['response']['games'], function($a, $b) {
				return $b['playtime_forever'] - $a['playtime_forever'];
			});
			//$ownedGames = array_slice($jsonResponse['response']['games'], 0, 10);
			$ownedGames = $jsonResponse['response']['games'];
		}
	}
}

if(isset($_POST['updateSteamID'])) {
	$steamID = $_POST['steamID'];

	$cookie_name = "steam_id";
	setcookie($cookie_name, $steamID, time() + (10 * 365 * 24 * 60 * 60), "/");

	// Handle the response (e.g., display a message to the user)
} 
?>

<!-- Update SteamID Form -->
<form action="yourgames.php" method="post" class="mt-3 mb-3">
	<div class="input-group mb-3">
		<input type="hidden" name="formType" value="updateSteamID">
		<input type="text" class="form-control" placeholder="Enter SteamID" name="steamID">
		<button class="btn btn-outline-secondary" type="submit" name="updateSteamID">Update SteamID</button>
	</div>
</form>

<!-- Table of Owned Games -->
<div class="container mt-5">
	<h3>Your Owned Games</h3>
	<table class="table table-bordered table-light">
		<thead class="table-dark">
			<tr>
				<th>Name of Game</th>
				<th>Hours Played</th>
			</tr>
		</thead>
		<tbody>
			<?php if (!empty($ownedGames)): ?>
				<?php foreach ($ownedGames as $game): ?>
					<tr>
						<td>
							<a href="review.php?appid=<?php echo $game['appid']; ?>&name=<?php echo $game['name']; ?>&hoursPlayed=<?php echo round($game['playtime_forever'] / 60, 1); ?>&steamID=<?php echo $steam_id; ?>">
								<?php echo $game['name']; ?>
							</a>
						</td>
						<td><?php echo round($game['playtime_forever'] / 60, 1); ?> hours</td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan="2">No games to display. Please submit your SteamID.</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<?php include('footer.php'); ?>

<!-- Your existing script for user session verification -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="verify_user_session.js"></script>

</body>
</html>
