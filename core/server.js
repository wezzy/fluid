/*
 *  Main Server Module
 * */

// Import libraries
var Log = require('log'), log = new Log(Log.INFO);

var connect = require('connect');
var MemoryStore = connect.session.MemoryStore;

var router = require("router");

var port = 8081 
log.info("Server created on port %s", port);

exports.start = function(){
    connect(
        connect.logger(),
        connect.bodyParser(),
        connect.favicon(),
        connect.cookieParser(),
        connect.static(__dirname),
        connect.session({ 
            secret: 'super secret session',
            store: new MemoryStore({ reapInterval: 60000, maxAge:300000 })
        }),
        router.handleRequest
    ).listen(port);

}; 
