<?php

namespace Fluid;

require_once("src/models/Model.php");

/**
 * This map the Zones table from the db
 *
 * @author wezzy
 */
class Zones extends Model{

    protected $_name = 'zones';
    protected $_primary = 'id';
    protected $_sequence = true;

    /**
     * Return the list of available zones
     * @return array of objects [id, name]
     */
    public function getList(){
        $select = $this->select();
		$select->order("id");
        $list = $this->fetchAll($select);
        return $list->toArray();
    }

    /**
     * Return the zone that match the specified paramter
     * @param $parameter if paramter is an integer we match paramter with the id column otherwise we match it with the name
     * @return an array with id and name
     */
    public function getZone($parameter){
        $select = $this->select();
        if(is_int($parameter)){
            $select->where("id = ?", $parameter);
        }else{
            $select->where("name = ?", $parameter);
        }

        return $this->fetchRow($select);
    }
}