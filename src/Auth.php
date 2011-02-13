<?php

namespace Fluid;

require_once("libs/Zend/Auth.php");
require_once("src/Factory.php");

/**
 * The authentication manager
 */
class Auth{
	
	private $_authAdapter = null;
	private $_identity = null;
	
    public function Auth(){
		
    }

	public function logout(){
		\Zend_Auth::getInstance()->clearIdentity();
	}
	
	public function identity(){
		
		require_once 'Zend/Session/Namespace.php';
		$auth = new \Zend_Session_Namespace('Zend_Auth');

		$identity = false;
		try{
			$identity = $auth->storage;
		}catch(\Exception $e){
			$identity = false;
		}
		return $identity;
	}
		
	public function authenticate($userid, $password){

		if(!$this->_authAdapter) $this->_setupAdapter();

		$this->_authAdapter->setIdentity($userid)->setCredential($password);
		$result = $this->_authAdapter->authenticate();
		
		if (!$result->isValid()){
		    // Authentication failed; print the reasons why
		    foreach ($result->getMessages() as $message) {
		    	fb($message);
		    }
			return NULL;
		}else{


            $userData = $this->_authAdapter->getResultRowObject(null, 'password');
			
			$auth = \Zend_Auth::getInstance();  
			$auth->getStorage()->write($userData);
			
			return $userData;
		}
		
	}
	
	private function _setupAdapter(){
		
		$config = Factory::get('config');
		
		switch($config->authentication->type){
			case "table":
				
				require_once("libs/Zend/Config.php");
				require_once("libs/Zend/Config/Ini.php");
				
				$tableConfig = new \Zend_Config_Ini(
				    APPLICATION_PATH . '/app/config/auth.table.ini',
				    APPLICATION_ENVIRONMENT
				);
			
				require_once("libs/Zend/Auth/Adapter/DbTable.php");

				$this->_authAdapter = new \Zend_Auth_Adapter_DbTable(
				    Factory::get('dbAdapter'),
				    $tableConfig->table->tableName,
				    $tableConfig->table->identityColumn,
				    $tableConfig->table->credentialColumn,
					"SHA(?)"
				);
			break;
		}
	}
	
	public function userHasGroup($groupName){
		
		// TODO: cache the list of groups for the current user
		$auth = Factory::get('auth');
		$id = $auth->identity();
		
		if(!$id) return false; // User not authenticated
		
		require_once("src/models/Users.php");
		
		$u = new Users();
		
		$list = $u->getGroups($id->id);
		// fb($list);
		for($i = 0; $i < count($list); $i++){
		//	fb($list[$i]['name']);
			if(strcasecmp($list[$i]['name'], "root") == 0) return true;	// The root has access to all groups
			if(strcasecmp($list[$i]['name'], $groupName) == 0) return true;
		}
		return false;
	}
	
	public function isAdmin(){
		return $this->userHasGroup("Admin");
	}
	
}