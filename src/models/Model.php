<?php

namespace Fluid;


/**
 * All the classes that maps DB object inherits from this class
 *
 * @author wezzy
 */
class Model extends \MongoCollection{

    function __construct($name){
		
		$dbAdapter = Factory::get("dbAdapter");
		parent::__construct($dbAdapter, $name);
		
    }
}