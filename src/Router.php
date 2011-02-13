<?php

namespace Fluid;

require_once("src/interfaces/IDispatcher.php");

/**
 * Main router class, it checks for user authorization and dispatch requests
 *
 * @author wezzy
 */
class Router implements IDispatcher{
    
	public function Router(){
		Factory::set("router", $this);
	}
	
    /*
     * Dispatch the request
     */
    public function dispatch(){

		Factory::get('eventsDispatcher')->notify("START_ROUTER_DISPATCH", $this);

        $this->applyBeforeFilters();
    
    	$request = $this->parseRequest();
	
        Factory::set('request', $request);
		
		$specialUrl = false;
		// Direct call to a portlet method
		if(preg_match("/^\/fluid\/portlets/", $request->getUrl()) > 0){
			$specialUrl = true;
			
			$url = $request->getUrl();
			$regExp = "/^\/fluid\/portlets\//";
			$url = preg_replace($regExp, "", $url);
			$portletAddr = explode("/", $url);
			$portletName = $portletAddr[0];
			if(count($portletAddr) > 1){
				$portletMethod = $portletAddr[1];
			}else{
				$portletMethod = "show";
			}
			
			require_once("src/PortletManager.php");
			$data = null;
			if(array_key_exists('identifier', $_GET)) $data = array('identifier' => $_GET['identifier']);
			$out = PortletManager::loadPortletByName($portletName, $data, false, $portletMethod);
			$response = Factory::get('response');
			$response->appendData($out);
		}
		
		// Ask for fluid shared services, this are functionalities useful for every portlet
		if($specialUrl == false && preg_match("/^\/fluid\/services/", $request->getUrl()) > 0){
			$specialUrl = true;
			$url = $request->getUrl();
			$regExp = "/^\/fluid\/services\//";
			$url = preg_replace($regExp, "", $url);
			$file = "src/services/" . $url . ".php";
			
			if(file_exists($file)){
				require_once($file);
			}else{
				fb($file);
				reportError("Service not found");
			}
		}
		
		
        // The standard route, load the page specified by the url
		if(!$specialUrl){
			require_once("src/ZoneManager.php");
	        $zoneManager = new ZoneManager($request->getZone());
			Factory::get('response')->setJson(FALSE);
	        $zoneManager->dispatch();
		}
		
        $this->applyAfterFilters();

		Factory::get('eventsDispatcher')->notify("END_ROUTER_DISPATCH", $this);
    }

    public function parseRequest($url = NULL){
        // Execute the request: Community -> Page -> Portlet
        if($url == NULL) $url = $_SERVER['REQUEST_URI'];
		
		fb("Url to parse: " . $url);
		
        // Remove the parameters
        $index = strpos($url, '?');
        if($index > 0){
            $url = substr($url, 0, $index);
        }		
		
		$basePath = Factory::get('config')->url;
		
        // Set the default route
		$cleanedUrl = str_replace("/", "", $url);
		$cleanedBasePath =  str_replace("/", "", $basePath);
		//fb($cleanedUrl . "=" . $cleanedBasePath);
        if(strcasecmp($url, "") == 0 || strcasecmp($url, $basePath . "/index.php") == 0 || strcasecmp($cleanedUrl, $cleanedBasePath) == 0){
			$url = Factory::get('config')->routes->default;	
		}else{
			$regExp = "/^" . str_replace('/', '\/', $basePath) . "/";
			$url = preg_replace($regExp, "", $url);
		}
			
        // Create the Request object
		if(count($url) == 0){
			// Not found
			//TODO: fix this with a better error handling
			fb("Page not found");
			exit();
		}
		
		fb("Parsed url:" . $url);
		
		$urlElements = explode("/", $url);

        require_once("src/Request.php");
        $req = new Request();

        $req->setZone($urlElements[1]);
        if(count($urlElements) > 1) $req->setPage($urlElements[2]);
        $req->setParameters($_GET);
        $req->setUrl($url);

        return $req;

    }
	
	public static function joinUrlElements($elements){
		$out = "/";
		
		for($i = 0; $i < count($elements); $i++){
			$el = $elements[$i];
			
			if($el[strlen($el) - 1] == "/") $el = substr($el, 0, strlen($el) - 1);
			if($el[0] == "/") $el = substr($el, 1);
			
			if($i > 0) $out .= "/";
			$out .= $el;
		}
		
		return $out;
	}
	
    public function  applyBeforeFilters(){
        // TODO
        return true;
    }

    public function  applyAfterFilters(){
        // TODO
        return true;
    }
}
