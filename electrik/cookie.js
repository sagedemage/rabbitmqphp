const express = require('express');

const session = require('express-session');

const cookie = express();
const port = 3000;

cookie.use(session({
    secret: "Wearegr0upelectr1k@NJ1T",
    resave: false,
    saveUninitialized: true,
}));

//Define a route
cookie.get('/', (req, res) => {
    if (req.session.views) {
        req.session.views++;
    } else {
        req.session.views = 1;
    }
    res.send('Page views: ${req.session.views}');
});

cookie.listen(port, () => {
    console.log('Server is running on port ${port}');
});