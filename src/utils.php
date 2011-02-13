<?php

namespace Fluid;

/*
	Some useful functions
*/

function print_obj($obj){
	echo('<pre>');
	print_r($obj);
	echo('</pre>');
}

// Log messages to FirePHP using ZendFramework facilities
function fb($message, $label=null){
	
	require_once("Zend/Log.php");
	require_once("Zend/Log/Writer/Firebug.php");
	require_once('Zend/Controller/Response/Http.php');
	require_once('Zend/Controller/Request/Http.php');
    
	if ($label!=null) {
        $message = array($label,$message);
    }

	$writer = new \Zend_Log_Writer_Firebug();
	$logger = new \Zend_Log($writer);
	
	// get the wildfire channel
	$channel = \Zend_Wildfire_Channel_HttpHeaders::getInstance();

	// create and set the HTTP response
	$response = new \Zend_Controller_Response_Http();
	$channel->setResponse($response);
	
	// create and set the HTTP request
	$channel->setRequest(new \Zend_Controller_Request_Http());
	
	$logger->log($message, \Zend_Log::INFO);
	
	// insert the wildfire headers into the HTTP response
	$channel->flush();
	
	// send the HTTP response headers
	$response->sendHeaders();
}

function reportError($msg){
	$response = Factory::get('response');
	$response->appendData($msg);
}

function redirect($pageUrl){
	require_once("src/Factory.php");
	$config = Factory::get('config');
	
	header("location: " . $config->host . $config->url . $pageUrl);
}

/**
 * Check if the requeste parameters exists in the specified arrray otherwise it returns null, if the parameter is required you can use
 * reqParam()
 * @param string $what the name of the reqired variable
 * @param array $where the array where to look for the varaible (ex $_GET or $_POST) 
 */
function getParam($what, $where = null){
	if(!$where){
		
		if(array_key_exists($what, $_GET)){
	        return $_GET[$what];
	    }
	
		if(array_key_exists($what, $_POST)){
	        return $_POST[$what];
	    }
	}else{
		if(array_key_exists($what, $where)){
	        return $where[$what];
	    }
	}
	
	return null;
}

/**
 * Check if the requeste parameters exists in the specified arrray otherwise it prints the default error message end exit
 * @param string $what the name of the reqired variable
 * @param array $where the array where to look for the varaible (ex $_GET or $_POST) 
 */
function reqParam($what, $where = null){
    
	$value = getParam($what, $where);
	if($value) return $value;

    require_once("src/views/TemplateEngine.php");
    $te = new FTemplateEngine();
    $te->assign('name', $what);
    $result = $te->fetch(APPLICATION_PATH . "/src/views/templates/missing_parameter.tpl");

    fb($result);

    $response = Factory::get('response');
    $response->appendData($result);
    exit();
}

/**
 * Returns the subversion release number
 */
function getSCID() {
	if(file_exists('.svn/entries')){
    	$svn = File('.svn/entries');
    	$svnrev = $svn[3];
    	return $svnrev;
	}
	return "";
}