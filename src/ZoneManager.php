<?php

namespace Fluid;

require_once("src/interfaces/IDispatcher.php");

/**
 * This class represent a stanard community
 *
 * @author wezzy
 */
class ZoneManager implements IDispatcher{

    private $_name;
    private $_data;

    public function __construct($name){
        $this->_name = $name;
		fb("Zone name = " . $this->_name);
		
		Factory::set("zoneManager", $this);
    }

    /**
     * 1) Load zone informations
     * 2) Check ACL
     * 3) Laod the Page object and pass the control to it
     */
    public function dispatch(){

		Factory::get('eventsDispatcher')->notify("START_ZONE_DISPATCH", $this);

        $this->applyBeforeFilters();

        // Force the db connection
        $db = Factory::get('dbAdapter');
		
		$data = $db->zones->findOne(array("name" => $this->_name));
		
		if(!$data){
			// TODO replace this with a better error
			
			if(strcasecmp("default", $this->_name) == 0){
				fb("Rebuild db");
				require_once("init_db.php");
			}else{
				echo("Zone not found");
				exit();
			}
		
		}
		
        // TODO check ACL

        require_once("src/PageManager.php");
        $page = new PageManager($this);
        $page->dispatch();

        $this->applyAfterFilters();

		Factory::get('eventsDispatcher')->notify("END_ZONE_DISPATCH", $this);

    }

    public function getId(){
        return $this->_data['id'];
    }

    public function  applyBeforeFilters(){
        // TODO
        return true;
    }

    public function  applyAfterFilters(){
        // TODO
        return true;
    }

    /**
     * Return the current zone
     * @return <array> zone an array with the ID and the name of the current displayed zone
     */
    public function getCurrentZone(){
        return $this->_data;
    }

    /**
     * Return the list of all available zones
     * @return array a list of [id name] arrays
     */
    public static function getList(){
        $table = new Zones();
        $list = $table->getList();
        return $list;
    }
}
