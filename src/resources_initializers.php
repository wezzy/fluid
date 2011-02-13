<?php

namespace Fluid;

require_once("src/Factory.php");

/*
 * Some functions used form the Factory to iniailize resources
 */

// Register the initializer function for the resource manager
Factory::registerCustomInitialization('dbAdapter', function(){

    $config = Factory::get("config");

	if(strlen($config->database->params->user)){
		// Require authentication
		$mongoUrl = "mongodb://" . $config->database->params->username . ":" . $config->database->params->password . "@" . $config->database->params->host . ":" . $config->database->params->port;fb($mongoUrl);
	}else{
		// Anonymous connection
		$mongoUrl = "mongodb://" . $config->database->params->host . ":" . $config->database->params->port;fb($mongoUrl);
	}
	
	$conn = new \Mongo($mongoUrl, array("persist" => "x"));
	
	$db = $conn->selectDB($config->database->params->dbname);
    return $db;
});


// Register the initializer function for the resource manager
Factory::registerCustomInitialization('response', function(){

    require_once("Response.php");
    return new Response();
});


// Register the initializer function for the logger
Factory::registerCustomInitialization('logger', function(){
	// Setup the logger using FirePHP
	
	require_once("Zend/Log.php");
	require_once("Zend/Log/Writer/Firebug.php");
	require_once('Zend/Controller/Response/Http.php');
	require_once('Zend/Controller/Request/Http.php');

	$writer = new \Zend_Log_Writer_Firebug();
	$logger = new \Zend_Log($writer);
	
	// get the wildfire channel
	$channel = \Zend_Wildfire_Channel_HttpHeaders::getInstance();

	// create and set the HTTP response
	$response = new \Zend_Controller_Response_Http();
	$channel->setResponse($response);
	
	// create and set the HTTP request
	$channel->setRequest(new \Zend_Controller_Request_Http());

	return $logger;
});

// Register the initializer function for the logger
Factory::registerCustomInitialization('eventsDispatcher', function(){
	require_once("EventsDispatcher.php");
	$ed = new EventsDispatcher();
	return $ed;
});

// Register the initializer function for the authentication component
Factory::registerCustomInitialization('auth', function(){
	require_once("Auth.php");
	$auth = new FAuth();
	return $auth;
});

// Register the initializer function for the ACL component
Factory::registerCustomInitialization('acl', function(){
	require_once("Acl.php");
	$acl = new FAcl();
	return $acl;
});

// Register the initializer function for the ACL component
Factory::registerCustomInitialization('staticLoader', function(){
	require_once("StaticLoader.php");
	$sl = new FStaticLoader();
	return $sl;
});