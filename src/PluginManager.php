<?php

namespace Fluid;

/**
 * Description of PluginManager
 *
 * @author wezzy
 */
class PluginManager {

	// Loads all the plugins
	public static function load(){

		// First loads the system plugins
		PluginManager::_loadDirectory("/src/plugins");

		// Now loads custom plugins
		PluginManager::_loadDirectory("/app/plugins");
		
	}

	private static function _loadDirectory($path){
		$myDirectory = opendir(APPLICATION_PATH . $path);

		while($entryName = readdir($myDirectory)) {
			$fileName = APPLICATION_PATH . $path . "/" . $entryName . "/" . $entryName . ".php";
			if(file_exists($fileName)){
				fb("Loaded " . $fileName);
				$plugin = require_once($fileName);
				$plugin->init();
			}
		}

		closedir($myDirectory);
	}

}