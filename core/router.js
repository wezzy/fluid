/*
 * Router class
 */

var Log = require('log'), log = new Log(Log.INFO);

exports.handleRequest = function(req, res){
    
	var genericRoute = function (req, res, next){
		
        log.info("Handle generic route");
		
	};

	// Special case

    app.get("*", genericRoute);

};
