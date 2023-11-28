/*const config = { 
	headers: {
		'Access-Control-Allow-Origin': 'https://api.steampowered.com',
		'Access-Control-Allow-Methods' : 'POST, GET, OPTIONS',
		'Access-Control-Allow-Headers' : 'X-PINGOTHER, Content-Type',
		'Access-Control-Max-Age': '86400'
	}
}*/

axios.get('http://localhost:3000/steam-api/get-app-list')
	.then(function (response) {
		// handle success
		console.log(response);
	})
	.catch(function (error) {
		// handle error
		console.log(error);
	})
	.finally(function () {
		// always executed
	});
