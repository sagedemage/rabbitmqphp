/*const config = { 
	headers: {
		'Access-Control-Allow-Origin': 'https://api.steampowered.com',
		'Access-Control-Allow-Methods' : 'POST, GET, OPTIONS',
		'Access-Control-Allow-Headers' : 'X-PINGOTHER, Content-Type',
		'Access-Control-Max-Age': '86400'
	}
}
const apiResponseData = {
  response: {
    apps: [
      {
        appid: 10,
        name: "Counter-Strike",
        last_modified: 1666823513,
        price_change_number: 21319021
      },
    ]
  }
};

axios.get('/upcomingGames.php', { jsonData: yourDataObject })
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
*/

axios.get('upcomingGames.php')
  .then(function (response) {
    // Log raw data from the server for debugging
    console.log("Raw data from server:", response.data);
    console.log(typeof response.data);
    // Check if the response data is a string (it should be based on the provided API output)
    if (typeof response.data === 'string') {
      try {
        // Attempt to parse JSON data
        const jsonData = JSON.parse(response.data);

        const dataObject = {
          jsonData: jsonData
        };

        axios.post('upcomingGames.php', dataObject)
          .then(function (response) {
            console.log(response.data);
          })
          .catch(function (error) {
            console.log(error);
          })
          .finally(function () {
            // Any final cleanup or actions
          });
      } catch (e) {
        // Log error if JSON parsing fails
        console.error("Error parsing JSON data:", e);
      }
    } else {
      console.error("Invalid response data type. Expected string.");
    }
  })
  .catch(function (error) {
    console.log(error);
  });

