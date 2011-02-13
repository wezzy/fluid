<?php

require_once("src/models/Model.php");

/**
 * Description of PortletInstances
 *
 * @author wezzy
 */
class PortletInstances extends Model{

    protected $_name = 'portlet_instances';
    protected $_primary = 'id';
    protected $_sequence = true;

	public function loadSettings($identifier){
		$select = $this->select();
		$select->where("identifier = ?", $identifier);

		$data = $this->fetchRow($select);
		if(!$data) return "";
		$data->toArray();
		$settings = unserialize($data['settings']);
		return $settings;
	}
	
	public function saveSettings($identifier, $settings){
		fb("IDENTIFIER = " . $identifier);
		fb("SETTINGS = " . $settings);
		$data = array('settings' => serialize($settings));		
		$where = $this->getAdapter()->quoteInto('identifier = ?', $identifier);
		$this->update($data, $where);
		return true;
	}
}