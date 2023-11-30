/*const config = { 
	headers: {
		'Access-Control-Allow-Origin': 'https://api.steampowered.com',
		'Access-Control-Allow-Methods' : 'POST, GET, OPTIONS',
		'Access-Control-Allow-Headers' : 'X-PINGOTHER, Content-Type',
		'Access-Control-Max-Age': '86400'
	}
}*/

axios.get('/upcomingGames.php')
  .then(function (response) {
    console.log('Request successful. Response:', response);
    sendDataToServer(response.data);
  })
  .catch(function (error) {
    console.log('Error in request:', error);
  })
  .finally(function () {
    console.log('Request finished.');
  });


function sendDataToServer(data) {
  // Create a new XMLHttpRequest object
  var xhr = new XMLHttpRequest();
  // Specify the type of request (POST) and the URL
  xhr.open('POST', 'index.php', true);
  // Set the Content-Type header
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  // Convert the data to a URL-encoded string
  var encodedData = 'jsonData=' + encodeURIComponent(JSON.stringify(data));
  // Send the request
  xhr.send(encodedData);
}
