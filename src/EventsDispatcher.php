<?php 

namespace Fluid;

class EventsDispatcher{
	
	private $_events;
	
	public function __construct(){
		$this->_events = array();
	}
	
	/**
	* Register an event listener
	* @param string $event the name of the event
	* @param function|array $callback the function or an array with the object and the method name that respond to the event 
	*/
	public function addListener($event, $callback){
		if(!array_key_exists($event, $this->_events)){
			$this->_events[$event] = array();
		}
		
		$this->_events[$event][] = $callback;
	}
	
	/**
	* Remove an event listener
	* @param string $event the name of the event
	* @param function|array $callback the function or an array with the object and the method name that respond to the event 
	*/
	public function removeListener($event, $callback){
		if(!array_key_exists($event, $this->_events)){
			return;	// Unknown event
		}
		
		$list = $this->_events[$event];
		$tmp = array();
		for($i = 0; $i < count($list); $i++){
			if($list[$i] != $callback) $tmp[] = $list[$i];
		}
		
		$this->_events[$event] = $tmp;
	}
	
	/**
	* When an event it's fired this function is called and notify all the registered callbacks
	* @param string $event the name of the event
	* @param object $source the object that fired the event
	*/
	public function notify($event, $source){
		//fb("Fired event " . $event);
		if(!array_key_exists($event, $this->_events)) return;
		$list = $this->_events[$event];
		for($i = 0; $i < count($list); $i++){
			call_user_func($list[$i], $source);
		}
	}
}