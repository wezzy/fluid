/*
 *  Main Server Module
 * */
var connect = require('connect');
var MemoryStore = connect.session.MemoryStore;

var router = require("router");

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
        connect.router(router.route)
    ).listen(8080);

};
