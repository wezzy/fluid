<?php

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

    public function addPage($url, $title, $theme ){}

    public function getList($zone_id){
        $select = $this->select();
        if($zone_id){
            $select->where("zone_id = ?", $zone_id);
        }
        $select->order("father_id");

        $list = $this->fetchAll($select);
        fb($list->toArray());
        return $list->toArray();

    }

	public function getDefaultPage(){
		$select = $this->select();
        $select->where("is_default = 1");
        
        $list = $this->fetchRow($select);

        return $list->toArray();
	}
}