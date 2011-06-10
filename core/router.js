/*
 * Router class
 */

exports.route = function(app){
    
	var genericRoute = function (req, res, next){
		
		
	};

	// Special case

    app.get("*", genericRoute);

};
