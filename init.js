/*
 *  FluidPortal
 *  Copyright (c) artBits snc - Fabio Trezzi
 */

// Load libraries
var Log = require('log'), log = new Log(Log.INFO);

// First setup require path
require.paths.push("./app");
require.paths.push("./core");
require.paths.push(".");

console.log("\n");
log.info("Startup ..");

var server = require("server");
server.start();
