var express = require('express');
var request = require('request');

var router = express.Router();
let dotenv = require('dotenv').config()

/* GET home page. */
router.get('/', function(req, res, next) {
	res.render('index', { title: 'Express' });
});

router.get('/hello', (req, res) => {
	res.send('Hello World!')
});

router.get('/get-app-list', (req, res) => {
	 res.setHeader('Content-Type', 'application/json');

	let api_key = process.env.STEAM_WEB_API_KEY;
	let url = "https://api.steampowered.com/IStoreService/GetAppList/v1/?key=" + api_key;
	request(url, function(err, response, body) {
		if(!err && response.statusCode < 400) {
			console.log(body);

  			res.send(body);
		}
	});
})

module.exports = router;
