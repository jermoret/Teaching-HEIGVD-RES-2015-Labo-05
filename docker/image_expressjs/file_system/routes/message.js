var express = require('express');
var router = express.Router();

/* GET users listing. */
router.get('/', function(req, res, next) {
  console.log(req.get("Accept"));
  console.log(req.accepts('html', 'text/plain', 'json'));

  var payload = {
	"value" : "hello world"
  }

  res.send(payload);	
  
});

module.exports = router;
