<?php

namespace Fluid;

require_once("src/models/Model.php");
/**
 * Description of Page
 *
 * @author wezzy
 */
class Pages extends Model{

    protected $_name = 'pages';
    protected $_primary = 'id';
    protected $_sequence = true;
	
	public function __construct(){
		
		parent::__construct($this->_name);
		
	}
	
    public function addPage($url, $title, $theme ){}

    public function getList($zone_id){
		
		$result = array();
		$db = Factory::get('dbAdapter');
		$query = array("zone_id" => $zone_id);
		$cursor = $db->pages->find($query);
		
		while($cursor->hasNext()){
			$result[] = $cursor->getNext();
		}
		
		return $result;
		
    }

	public function getDefaultPage(){

		$db = Factory::get('dbAdapter');
		$query = array("id_default" => true);
		return $db->pages->findOne($query);
	}
}