<?php

namespace Fluid;

require_once("src/views/TemplateEngine.php");

/**
*	this class holds all the informations for the available portlets
*/
class PortletManager{

    public static $_cacheList = NULL;

	public static function removePortlet($portletId){
		require_once("src/models/PortletInstances.php");
        $table = new PortletInstances();

		$where = $table->getAdapter()->quoteInto('identifier = ?', $portletId);
		
		$table->delete($where);
		
		return "ok";
	}
	
	public static function addPortlet($name, $page, $identifier = null){
		
		require_once("src/models/PortletInstances.php");
        $table = new PortletInstances();
		
		if(!$identifier){
			$identifier = "portlet_" . (time() - mktime(0, 0, 0, 1, 1, 2009));
		}
		
		$data = array(
		    'page_id'      => $page,
		    'container_name' => 'column1',
		    'portlet_title'      => str_replace("_", " ", $name),
			'portlet_name'      => $name,
			'identifier' => $identifier, 
		);

		$table->insert($data);
		
		$data = array();
		$data['portlet_title'] = str_replace("_", " ", $name);
		$data['identifier'] = $identifier;
		
		$result = array();
		$result['portlet'] = PortletManager::loadPortletByName($name, $data);
		$result['identifier'] = $identifier;
		return $result;
	}
	/**
	*	This method loads the portlet from the file and then call a method from the portlet's class, by default this method is "show" to display standard portlet content
	*/
	public static function loadPortletByUrl($url, $data, $wrapTemplate = true, $method = "show"){
		
		// Load the portlet file
		$portlet = require($url . "/index.php");
		
		// Init the portlet
		if($data && array_key_exists("identifier", $data)){
			$identifier = $data['identifier'];
		}else{
			$identifier = "";
		}
		call_user_func(array($portlet, "init"), $url, $identifier);
		
		// Show the portlet
		$portletBody = call_user_func(array($portlet, $method));
		
		//fb("Portlet name: " . basename($url));
		
		if($wrapTemplate){
			$teWrapper = new FTemplateEngine();
			$teWrapper->assign('portletName', basename($url));
        	$teWrapper->assign('portletTitle', $data['portlet_title']);
        	$teWrapper->assign('portletBody', $portletBody);
        	$teWrapper->assign('portletId', $identifier);
        	$teWrapper->assign('portletFooter', "");
			return $teWrapper->fetch(APPLICATION_PATH . "/src/views/templates/portlet.tpl");
		}else{
		
			return $portletBody;
		
		}
       
	}
	
	public static function loadPortletByName($name, $data, $wrapTemplate = true, $method = "show"){
		if($name[strlen($name) - 1] != "/") $name .= "/";
		
		$templateFile = APPLICATION_PATH . "/app/portlets/" . $name;
		if(!file_exists($templateFile . "index.php")) $templateFile = APPLICATION_PATH . "/src/portlets/" . $name;
		
		if(!file_exists($templateFile . "index.php")){
			fb("Portlet not found: " . $templateFile . "index.php");
			return "Portlet not found";
		}
		return PortletManager::loadPortletByUrl($templateFile, $data, $wrapTemplate, $method);
	}
	
	public static function movePortlet($portlets, $container){
		try{
			
			$db = FFactory::get('dbAdapter');
			
			$list = Zend_Json::decode(stripslashes($portlets));
			fb("PORTLTEST = " . $portlets);
			fb($list);
			for($i = 0; $i < count($list); $i++){
				
				$data = array(
				    'container_name' => $container,
					'order' => $i
				);
				
				$portlet_id = $list[$i];
				$n = $db->update('portlet_instances', $data, "identifier = '$portlet_id'");
			}
			
			
		}catch(Exception $e){
			return $e;
		}
		return true;
	}

    /**
     * Return the list of all available portlets
     * @return array the list of all available portlets
     */
	public static function getList(){

        if(!PortletManager::$_cacheList){
            $result = array();

            // Parse the source directory
            $handle = opendir(APPLICATION_PATH . '/src/portlets');
            if ($handle) {


                // Lista di tutti i file:
                while (false !== ($file = readdir($handle))) {
                    if($file[0] != '.'){	// No hidden directory
                        $iniPath = APPLICATION_PATH . '/src/portlets/' . $file . "/info.ini";
                        if(file_exists($iniPath)){
                            $config = new Zend_Config_Ini($iniPath, 'info');

                            $data = array();
                            $data['name'] = $config->name;
                            $data['author'] = $config->author;
                            $data['copyright'] = $config->copyright;
                            $data['version'] = $config->version;
                            $data['homePage'] = $config->homePage;
                            $data['description'] = $config->description;
                            $data['path'] = $file;	// Add the path of the portlet
                            if($config->mainMenu) $data['mainMenu'] = $config->mainMenu;

                            $result[$file] = $data;
                        }
                    }
                }
                closedir($handle);
            }

            // Parse the application directory, note that if i have a portlet with the same name in the
            // application path it replace the original portlet founded in the source path
            $handle = opendir(APPLICATION_PATH . '/app/portlets');
            if ($handle) {


                // Lista di tutti i file:
                while (false !== ($file = readdir($handle))) {
                    if($file[0] != '.'){	// No hidden directory
                        $iniPath = APPLICATION_PATH . '/app/portlets/' . $file . "/info.ini";
                        if(file_exists($iniPath)){
                            $config = new Zend_Config_Ini($iniPath, 'info');

                            $data = array();
                            $data['name'] = $config->name;
                            $data['author'] = $config->author;
                            $data['copyright'] = $config->copyright;
                            $data['version'] = $config->version;
                            $data['homePage'] = $config->homePage;
                            $data['description'] = $config->description;
                            $data['path'] = $file;	// Add the path of the portlet
                            if($config->mainMenu) $data['mainMenu'] = $config->mainMenu;

                            $result[$file] = $data;
                        }
                    }
                }
                closedir($handle);
            }

            PortletManager::$_cacheList = $result;

        }
		return PortletManager::$_cacheList;
		
	}
	
}