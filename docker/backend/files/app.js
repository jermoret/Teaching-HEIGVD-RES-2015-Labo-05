var express = require('express');
var app = express();
var dice = new Object();

app.get('/', function (req, res) {
	res.setHeader('Content-Type', 'application/json');
	dice.value = Math.floor((Math.random() * 6) + 1);
	res.send( JSON.stringify(dice) );
});

var server = app.listen(80, function () {

  var host = server.address().address;
  var port = server.address().port;

});