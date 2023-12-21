<!DOCTYPE html>
<html lang="en">
<head>
    <title>Your Games</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include('navbar.php'); ?>

<!-- Form to Submit or Clear SteamID is here-->
<form action="your_games.php" method="post" class="mt-3 mb-3">
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Enter SteamID" name="steamID">
        <button class="btn btn-outline-secondary" type="submit" name="submitSteamID">Submit</button>
        <button class="btn btn-outline-danger" type="submit" name="clearSteamID">Clear</button>
    </div>
</form>

<?php
ini_set('display_errors', 1);

require_once('rabbitmq_lib/path.inc');
require_once('rabbitmq_lib/get_host_info.inc');
require_once('rabbitmq_lib/rabbitMQLib.inc');

$ownedGames = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submitSteamID']) && !empty($_POST['steamID'])) {
        $steamID = $_POST['steamID'];

        // Fetch user's most-played games using the submitted SteamID
        $client = new rabbitMQClient("testRabbitMQ.ini", "testServer");
        $request = array();
        $request['type'] = "GetOwnedGames";
        $request['steamID'] = $steamID;
        $response = $client->send_request($request);

        if (is_string($response)) {
            $jsonResponse = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($jsonResponse['response']['games'])) {
                $ownedGames = array_slice($jsonResponse['response']['games'], 0, 5);
            }
        }
    } elseif (isset($_POST['clearSteamID'])) {
        // Clear SteamID logic
        $ownedGames = []; // Reset the owned games array
    }
}
?>

<div class="container mt-5">
    <h3>Your Owned Games</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name of Game</th>
                <th>Hours Played</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ownedGames as $game): ?>
                <tr>
                    <td>
                        <a href="review.php?appid=<?php echo $game['appid']; ?>&name=<?php echo urlencode($game['name']); ?>">
                            <img src="https://steamcdn-a.akamaihd.net/steam/apps/<?php echo $game['appid']; ?>/header.jpg" alt="Game Image" style="height: 50px;">
                        </a>
                    </td>
                    <td><?php echo $game['name']; ?></td>
                    <td><?php echo round($game['playtime_forever'] / 60, 1); ?> hours</td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($ownedGames)): ?>
                <tr>
                    <td colspan="3">No games to display. Please submit your SteamID.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="footer">
    &copy; 2023 Electrik.com. All rights reserved. <a class="terms-link" href="terms.php">Terms of Service</a>
</div>


<!-- Bootstrap JS (optional, but required for some features) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
