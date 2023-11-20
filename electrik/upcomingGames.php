<?php
function getUpcomingGames() {
    $apiKey = '7077B51AC45030DB2B70BBAECA86B8B2';
    $apiUrl = "https://api.steampowered.com/IStoreService/GetAppList/v1/?key=$apiKey";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $apiUrl);

    $result = curl_exec($ch);

    if ($result === false) {
        die('Failed to fetch data from Steam API. cURL Error: ' . curl_error($ch));
    }

    curl_close($ch);

    $decodedResult = json_decode($result, true);

    if ($decodedResult && isset($decodedResult['response']['apps'])) {
        return $decodedResult;
    } else {
        return false;
    }
}
?>
