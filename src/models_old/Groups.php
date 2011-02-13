<?php

require_once("src/models/Model.php");

/**
 * This map the Groups table from the db
 *
 * @author wezzy
 */
class Groups extends Model{

    protected $_name = 'lkp_groups';
    protected $_primary = 'id';
    protected $_sequence = true;
	
	/**
	 * Get the list of user for the specified group
	 * @param object $groupid the id of the group
	 * @return an arrays of users that belongs to the group
	 */
	public function getUsers($groupId){
		$db = FFactory::get('dbAdapter');
		$sql = "SELECT users.* FROM users JOIN users2groups ON users.id = users2groups.user_id JOIN lkp_groups ON users2groups.group_id = lkp_groups.id WHERE lkp_groups.id = ?";
		$tmp = $db->fetchAssoc($sql, $groupId);
		$result = array();
		foreach($tmp as $key => $value){
			$result[] = $value;
		}
		return $result;
	}
	
	/**
	 * Get the list of available groups
	 * @return the user list
	 */
	public function getList(){
		$db = FFactory::get('dbAdapter');
		$sql = "SELECT id, name FROM lkp_groups ORDER BY name";
		$tmp = $db->fetchAssoc($sql);
		$result = array();
		foreach($tmp as $key => $value){
			$result[] = $value;
		}
		return $result;
	}
}