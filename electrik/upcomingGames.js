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
  console.log("Raw data from server:", response.data);

  if (typeof response.data === 'object' && response.data !== null) {
    const dataObject = {
      jsonData: response.data
    };
    console.log("Data Object:", dataObject);

    axios.post('copy.php', dataObject, {
      headers: {
        'Content-Type': 'application/json',
      }
    })
      .then(function (response) {
        console.log("In js before post:", response.data);
      })
      .catch(function (error) {
        console.log(error);
      })
      .finally(function () {
      });
  } else {
    console.error("Invalid response data type. Expected object.");
  }
})
.catch(function (error) {
  console.log(error);
});



