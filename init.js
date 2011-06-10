/*
 *  FluidCMS
 *  Copyright (c) artBits snc - Fabio Trezzi
 */

// First setup require path
require.paths.push("./app");
require.paths.push("./core");
require.paths.push(".");

console.log("Startup");

var server = require("server");
server.start();
