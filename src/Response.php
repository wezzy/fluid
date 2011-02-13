<?php

namespace Fluid;

/**
 * Description of Response
 *
 * @author wezzy
 */
class Response extends \Zend_Controller_Response_Http{
    private $_data;
	private $_isJson = true;	// tell if the request is a json response

    public function __construct($initalValue = ""){
        $this->_data = $initalValue;
    }

    public function getData(){
        return $this->_data;
    }

    public function setData($data){
        $this->_data = $data;
    }

    public function appendData($str){
        $this->_data .= $str;
    }
	
	public function setJson($value){
		$this->_isJson = $value;
	}
	
	public function getJson(){
		return $this->_isJson;
	}
	
}