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

<?php
ini_set('display_errors', 1);

require_once('../rabbitmq_lib/path.inc');
require_once('../rabbitmq_lib/get_host_info.inc');
require_once('../rabbitmq_lib/rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

/*
function decodeCookie($cookieValue) {
    if (empty($cookieValue)) {
        return false;
    }

	// this does not work
    $env = parse_ini_file('env.ini');
    $key = $env["OPENSSL_KEY"];
    $cipher = "AES-128-CBC";  // Assuming you use the same cipher as in your login script

    $c = base64_decode($cookieValue);
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len=32);
    $ciphertext_raw = substr($c, $ivlen + $sha2len);
    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);

    if (hash_equals($hmac, $calcmac)) {
        // Assuming the plaintext is the userId
        return $original_plaintext;
    }

    return false;
}
 */

// undefined

if (isset($_COOKIE['user_id'])) {
	$cookieValue = $_COOKIE['user_id'];
	$request = array();
 
	$request['type'] = "Session";
	$request['username_cipher_text'] = strval($cookieValue);
	$response = $client->send_request($request);

	echo $response;
}

$userId = null;
$userSteamID = null;
$ownedGames = [];

// undefined key user id

if (isset($userId)) {
    $request = array('type' => "GetUserSteamID", 'userId' => $userId);
    $userSteamID = $client->send_request($request);
    if ($userSteamID) {
        // Fetch user's most-played games using the associated SteamID
        $request = array();
        $request['type'] = "GetOwnedGames";
        $request['steamID'] = $userSteamID;
        $response = $client->send_request($request);
    
        if (is_string($response)) {
            $jsonResponse = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($jsonResponse['response']['games'])) {
                usort($jsonResponse['response']['games'], function($a, $b) {
                    return $b['playtime_forever'] - $a['playtime_forever'];
                });
                $ownedGames = array_slice($jsonResponse['response']['games'], 0, 5);
            }
        }
    }
}

if(isset($_POST['updateSteamID'])) {
    $steamID = $_POST['steamID'];
    $request = array();
    $request['type'] = "UpdateSteamID";
    $request['userId'] = $userId;
    $request['steamID'] = $steamID;
    $response = $client->send_request($request);

    // Handle the response (e.g., display a message to the user)
} 
?>

<!-- Update SteamID Form -->
<form action="index.php" method="post" class="mt-3 mb-3">
    <div class="input-group mb-3">
        <input type="hidden" name="formType" value="updateSteamID">
        <input type="text" class="form-control" placeholder="Enter SteamID" name="steamID">
        <button class="btn btn-outline-secondary" type="submit" name="updateSteamID">Update SteamID</button>
    </div>
</form>

<!-- Search Game Form -->
<form action="index.php" method="get" class="mt-3 mb-3">
    <input type="hidden" name="formType" value="searchGame">
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Enter Game Name" name="gameSearch">
        <button class="btn btn-outline-secondary" type="submit">Search</button>
    </div>
</form>

<!-- Bootstrap Carousel -->
<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner" id="carouselInner">
        <?php
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
                    echo '<a href="review.php?appid=' . $appId . '&name=' . urlencode($gameName) . '">';
                    echo '<img src="' . $imageUrl . '" class="d-block w-100" alt="' . $gameName . '" style="height: 25rem;">';
                    echo '</a>';
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

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['gameSearch'])) {
            $gameSearch = strtolower($_GET['gameSearch']);
            $foundGame = false;
        
            foreach ($jsonResponse->response->apps as $app) {
                if (strtolower($app->name) == $gameSearch) {
                    header("Location: review.php?appid={$app->appid}&name=" . urlencode($app->name));
                    $foundGame = true;
                    break;
                }
            }
        
            if (!$foundGame) {
                echo '<p>Game not found. Please try another search.</p>';
            }
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

<!-- Table of Owned Games -->
<div class="container mt-5">
    <h3>Your Owned Games</h3>
    <table class="table table-bordered">
        <thead>
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
                            <a href="reviews.php?appid=<?php echo $game['appid']; ?>&name=<?php echo urlencode($game['name']); ?>&hoursPlayed=<?php echo round($game['playtime_forever'] / 60, 1); ?>&steamID=<?php echo $userSteamID; ?>">
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



<div class="footer">
   &copy; 2023 Electrik.com. All rights reserved. <a class="terms-link" href="terms.php">Terms of Service</a>
</div>

<!-- Bootstrap JS (optional, but required for some features) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
