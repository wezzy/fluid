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
	
	public function __construct(){
		parent::__construct();
		
	}
	
    /**
     * Return the list of available zones
     * @return array of objects [id, name]
     */
    public function getList(){
		
		$result = array();
		$db = Factory::get('dbAdapter');
		$cursor = $db->zones->find();
		$cursor->sort(array("name" => 1));
		
		while($cursor->hasNext()){
			$result[] = $cursor->getNext();
		}
		
		return $result;
    }

    /**
     * Return the zone that match the specified paramter
     * @param $parameter if paramter is an integer we match paramter with the id column otherwise we match it with the name
     * @return an array with id and name
     */
    public function getZone($parameter){
	
        $db = Factory::get('dbAdapter');		
        return $db->zones->findOne($parameter);

    }
}