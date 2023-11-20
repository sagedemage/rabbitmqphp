<!-- upcomingGames.php -->

<?php
ini_set('display_errors', 1);
function getUpcomingGames() {
    $apiKey = '8ACEC2BCC36C5A84BAB32F2A71B0A7F1';
	$apiUrl = "https://api.steampowered.com/IStoreService/GetAppList/v1/?key=$apiKey";

   	$curl = curl_init($apiUrl);
   	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
   	$response = curl_exec($curl);
	curl_close($curl);

	echo $response;
}

getUpcomingGames()

?>
