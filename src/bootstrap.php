<?php

namespace Fluid;

error_reporting(E_ALL | E_STRICT);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);


// Zend libraries
require_once("src/views/Output.php");
require_once("libs/Zend/Config.php");
require_once("libs/Zend/Config/Ini.php");
require_once("libs/Zend/Controller/Request/Http.php");
require_once("libs/Zend/Controller/Response/Http.php");
require_once("libs/Zend/Wildfire/Channel/HttpHeaders.php");
// Some shared functions
require_once("src/utils.php");
// Custom libraries
require_once("src/Factory.php");
require_once("src/PluginManager.php");

// Loads plugins
PluginManager::load();

// Register the callback function
register_shutdown_function(function (){

	Factory::get('eventsDispatcher')->notify("START_DISPLAY_OUTPUT", null);
	Output::display();  // this display the page content
	Factory::get('eventsDispatcher')->notify("END_DISPLAY_OUTPUT", null);

	Factory::get('eventsDispatcher')->notify("EXIT", null);

	// Flush log data to browser
	Factory::get('channel')->flush();
	Factory::get('httpResponse')->sendHeaders();

});

// Load configuration
$config = new \Zend_Config_Ini(
    APPLICATION_PATH . '/app/config/config.ini',
    APPLICATION_ENVIRONMENT
);

// Set the right timezone according to the configuration
date_default_timezone_set($config->timezone);


// Setup the channel
$request = new \Zend_Controller_Request_Http();
$response = new \Zend_Controller_Response_Http();
$channel = \Zend_Wildfire_Channel_HttpHeaders::getInstance();
$channel->setRequest($request);
$channel->setResponse($response);
Factory::set('httpRequest', $request);
Factory::set('httpResponse', $response);
Factory::set('channel', $channel);

// Start output buffering
ob_start();

// Store the configuration
Factory::set('config', $config);

require_once("src/Router.php");
$router = new Router();
Factory::set('router', $router);

Factory::get('logger')->log("Startup...", \Zend_Log::INFO);

Factory::get('eventsDispatcher')->notify("STARTUP", null);

// cleanup
unset($config);
unset($request);
unset($response);
unset($channel);

$router->dispatch();

unset($router);
