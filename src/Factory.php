<?php
namespace Fluid;

require_once("libs/Zend/Registry.php");

/**
 *  Rosurce manager, it holds all the shared resources and initialize them the first time that are requested
 */
class Factory{

    private static $_db;

    public static function get($name){
		
        if(!\Zend_Registry::isRegistered($name)){
            if(array_key_exists($name, Factory::$_db) && is_callable(Factory::$_db[$name])){
                \Zend_Registry::set($name, call_user_func(Factory::$_db[$name]));
            }else{
                // Not Found
                fb("Requested resource not found: " + $name);
                return null;
            }
        }

        return \Zend_Registry::get($name);
    }

    public static function set($name, $value){
        \Zend_Registry::set($name, $value);
    }

    public static function registerCustomInitialization($name, $function){
		
        if(!Factory::$_db){
            Factory::$_db = array();
        }

        Factory::$_db[$name] = $function;
        
    }

	public static function isRegistered($key){
		return \Zend_Registry::isRegistered($key);
	}
}

require_once("src/resources_initializers.php");   // Some function used for data initialization