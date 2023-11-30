/*const config = { 
	headers: {
		'Access-Control-Allow-Origin': 'https://api.steampowered.com',
		'Access-Control-Allow-Methods' : 'POST, GET, OPTIONS',
		'Access-Control-Allow-Headers' : 'X-PINGOTHER, Content-Type',
		'Access-Control-Max-Age': '86400'
	}
}
    const dataObject = {
      jsonData: response.data
    };
    console.log("Data Object:", dataObject);
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
        },
      })      
      .then(function (response) {
        console.log("In js before post:", response.data);
      })
      .catch(function (error) {
        console.log(error);
      });
    } else {
      console.error("Invalid response data type. Expected object.");
    }
  })
  .catch(function (error) {
    console.log(error);
  });



