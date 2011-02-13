<?php

namespace Fluid;

require_once("src/interfaces/IDispatcher.php");
require_once("src/views/TemplateEngine.php");
require_once("src/views/TemplateHelpers.php");
require_once("src/PortletManager.php");

/**
 * Description of PageManager
 *
 * @author wezzy
 */
class PageManager implements IDispatcher{

    private $_zone;
    private $_data;
	private $_requiredCss = array();
	private $_requiredJs = array();
	
    public function __construct($zone){

    	$this->_zone = $zone;
	
		// Load the required libraries
        $auth = Factory::get('auth');
		$config = Factory::get('config');
        $sl = Factory::get('staticLoader');

        if($auth->userHasGroup("admin")){
			$sl->request($config->yui . "assets/skins/sam/skin.css");
			$sl->request($config->url . "/src/css/master.css");
			$sl->request($config->url . "/src/css/admin.css");
			
			$sl->request($config->yui . "utilities/utilities.js");
			/*
	        $sl->request($config->yui . "yuiloader/yuiloader.js");
	        $sl->request($config->yui . "dom/dom.js");
	        $sl->request($config->yui . "event/event.js");
	        $sl->request($config->yui . "animation/animation.js");
	        $sl->request($config->yui . "connection/connection.js");
	        $sl->request($config->yui . "dragdrop/dragdrop.js");
	        $sl->request($config->yui . "element/element.js");
            */

			$sl->request($config->yui . "selector/selector-min.js");
			$sl->request($config->yui . "json/json-min.js");
			$sl->request($config->yui . "button/button-min.js");
			$sl->request($config->yui . "container/container-min.js");
			$sl->request($config->yui . "menu/menu-min.js");

            // YUI 3 seed
            $sl->request($config->yui3 . "yui/yui-min.js");

            $sl->request($config->url . "/src/js/admin.js");
			$sl->request($config->url . "/src/js/datasources.js");
            $sl->request($config->url . "/src/js/dataschemas.js");
			$sl->request($config->url . "/src/js/utils.js");
			$sl->request($config->url . "/src/js/startup.js");
	        $sl->request($config->url . "/src/js/portal.js");
        }else{
			// These are the resources available fro every user even guests, that's for you portlet's developer :-)
			$sl->request($config->url . "/src/css/master.css");
			
			$sl->request($config->yui . "yuiloader-dom-event/yuiloader-dom-event.js");
			$sl->request($config->yui . "selector/selector-min.js");
			$sl->request($config->url . "/src/js/utils.js");
			$sl->request($config->url . "/src/js/startup.js");

            // YUI 3 seed
            $sl->request($config->yui3 . "yui/yui-min.js");
		}

        Factory::set("pageManager", $this);
    }	

    /**
     * 1) Check ACL
     * 2) Load Page info
     * 3) Load each portlet
     */
    public function dispatch(){

		Factory::get('eventsDispatcher')->notify("START_PAGE_DISPATCH", $this);

        $this->applyBeforeFilters();

        $req = Factory::get('request');		
		$db = Factory::get('dbAdapter');

		$data = $db->pages->findOne(array(
			"url" => $req->getUrl()
		));
		
        if(!$data){
			// TODO reaplce this with a real error
        	fb("Error while processing the page");
            exit();
        }
		
        // TODO check ACL

        // Load portlets
        $portlets = $this->_loadPortletContainer();

        // Load the layout
        $layout = $this->_loadLayout($portlets);

        // Load the template
        $template = $this->_loadTemplate($layout);
        
        $response =  Factory::get('response');
        $response->appendData($template);

        $this->applyAfterFilters();

		Factory::get('eventsDispatcher')->notify("END_PAGE_DISPATCH", $this);

    }

    public function  applyBeforeFilters(){
        // TODO
        return true;
    }

    public function  applyAfterFilters(){
        // TODO
        return true;
    }

    private function _loadPortletContainer(){
	
		$data = array();
		$db = Factory::get('dbAdapter');
		$cursor = $db->portlets->find(array(
			"page_id" => $this->_data['id']
		));
		$cursor->sort(array(
			"container_name" => 1,
			"order" => 1
		));
		
		while($cursor->hasNext()){
			$data[] = $cursor->getNext();
		}

        $portlet = array();
		
        for($i = 0; $i < count($data); $i++){
            $el =& $data[$i];

            if(!array_key_exists($el['container_name'], $portlet)){
                $portlet[$el['container_name']] = "";
            }
			
            // Wrap everything into a portlet wrapper
            $portlet[$el['container_name']] .= PortletManager::loadPortletByName($el['portlet_name'], $el);
        }
		
        $te = new TemplateEngine();
        foreach($portlet as $key => $value){
          $te->assign("column_content", $value);
          $te->assign('column_name', $key);
          $portlet[$key] = $te->fetch(APPLICATION_PATH . "/src/views/templates/container.tpl");
        }
		
        //fb($portlet);
		
        return $portlet;
    }

    private function _loadLayout($portlets){

        $layout = file_get_contents(APPLICATION_PATH . "/app/themes/" . $this->_data['theme'] . "/layouts/" . $this->_data['layout'] . ".tpl");
        $result = array();
        preg_match_all("/column[0-9]/i", $layout, $result);
        $containers = $result[0];
        $count = count($containers);
        
        $te = new TemplateEngine();
        $containerTemplate = null;

        for($i = 0; $i < $count; $i++){
            $cnt = $containers[$i];
            if(array_key_exists($cnt, $portlets)){
                $te->assign($cnt, $portlets[$cnt]);
            }else{
                // load an empty container
                if($containerTemplate == null){
                    $te2 = new TemplateEngine();
                    $te2->assign("column_content", "");
                    $te2->assign('column_name', $cnt);
                    $containerTemplate = $te2->fetch(APPLICATION_PATH . "/src/views/templates/container.tpl");
                }
                $te->assign($cnt, $containerTemplate);
            }
        }
        /*
        foreach($portlets as $key => $value){
            $te->assign($key, $value);
        }*/
		
        return $te->fetch(APPLICATION_PATH . "/app/themes/" . $this->_data['theme'] . "/layouts/" . $this->_data['layout'] . ".tpl");
        
    }

    private function _loadTemplate($layout){


        require_once("src/views/TemplateHelpers.php");
		// Write some parameters used by the javacript frontend
        $tmp = $this->_zone->getCurrentZone();
		$javascriptConfig = TemplateHelpers::javascriptConfig($tmp['id'], $this->_data['id']);
		
		// Parse the template
		$sl = Factory::get('staticLoader');
		$te = new TemplateEngine();
		$te->assign('url', Factory::get('config')->url);
		$te->assign('javascriptConfig', $javascriptConfig);
        $te->assign('layout', $layout);
        $te->assign('title', $this->_data['title']);
		$te->assign('requiredCss', TemplateHelpers::linkCss($sl->getCss()));
        $te->assign('customCss', TemplateHelpers::includeCss($this->_data['custom_css']));
		$te->assign('requiredJs', TemplateHelpers::linkJs($sl->getJs()));
        $te->assign('customJs', TemplateHelpers::includeJs($this->_data['custom_js']));
		$te->assign('themePath', Factory::get('config')->url . "/app/themes/" . $this->_data['theme'] . "/");
		

        $auth = Factory::get('auth');
		if($auth->userHasGroup("admin")){
			require_once("src/AdminMenu.php");
            $te->assign('adminMenu', FAdminMenu::display());
		}
        return $te->fetch(APPLICATION_PATH . "/app/themes/" . $this->_data['theme'] . "/templates/" . $this->_data['template'] . ".tpl");
    }

    /**
     * Returns the structure of the site
     */
    public static function getList($zone_id = NULL, $father_id = NULL){

        require_once("src/models/Pages.php");
        $table = new Pages();
        $list = $table->getList($zone_id);
        $result = array();
		
        for($i = 0; $i < count($list); $i++){
            $page = $list[$i];
            if($page == null) continue;

        	if($page['father_id'] == $father_id) $result[] = $page; 
        	
                    
        }

        fb($result);

        return $result;
    }

}