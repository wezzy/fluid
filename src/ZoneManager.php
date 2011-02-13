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

    public function ZoneManager($name){
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

        require_once("src/models/Zones.php");

        // Force the db connection
        $dbAdapter = Factory::get('dbAdapter');
        $table = new Zones();

        $select = $table->select();
        $select->where('name = ?', $this->_name);
		
        $data = $table->fetchRow($select);
		
		if(!$data){
			// TODO replace this with a better error
			echo("Zone not found");
			exit();
		}
		
        $this->_data = $data->toArray();
        // TODO check ACL

        require_once("src/PageManager.php");
        $page = new FPageManager($this);
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
