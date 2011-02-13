<?php

require_once("src/models/Model.php");

/**
 * This map the Users table from the db
 *
 * @author wezzy
 */
class Users extends Model{

    protected $_name = 'users';
    protected $_primary = 'id';
    protected $_sequence = true;
	
	/**
	 * Return the list of the groups for the specified user
	 * @param object $userid the id of the user
	 * @return an array with the groups 
	 */
    public function getGroups($userid){
		$db = FFactory::get('dbAdapter');
		$sql = "SELECT lkp_groups.* FROM users JOIN users2groups ON users.id = users2groups.user_id JOIN lkp_groups ON users2groups.group_id = lkp_groups.id WHERE users.id = ?";
		$tmp = $db->fetchAssoc($sql, $userid);
		$result = array();
		foreach($tmp as $key => $value){
			$result[] = $value;
		}
		return $result;
	}
	
	/**
	 * Get the list of available users
	 * @return the user list
	 */
	public function getList(){
		$db = FFactory::get('dbAdapter');
		$sql = "SELECT id, name, surname FROM users ORDER BY surname, name";
		$tmp = $db->fetchAssoc($sql);
		$result = array();
		foreach($tmp as $key => $value){
			$result[] = $value;
		}
		return $result;
	}
}