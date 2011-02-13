<?php
	
namespace Fluid;

require_once("src/Factory.php");

/**
 * Description of Output
 *
 * @author wezzy
 */
class Output {
	
    public static function display(){

        $response = Factory::get('response');
        $output = $response->getData();
        echo($output);
		//Factory::get('httpResponse')->setBody($output);
    }
}