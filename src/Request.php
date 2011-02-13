<?php

namespace Fluid;

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Request
 *
 * @author wezzy
 */
class Request {

    private $_zone;
    private $_page;
    private $_parameters;
    private $_url;

    public function getZone(){
        return $this->_zone;
    }

    public function setZone($zoneName){
        $this->_zone = $zoneName;
    }

    public function getPage(){
        return $this->_page;
    }

    public function setPage($pageName){
        $this->_page = $pageName;
    }

    public function getParameters(){
        return $this->_parameters;
    }

    public function setParameters($valuesArray){
        $this->_parameters = $valuesArray;
    }

    public function getUrl(){
        return $this->_url;
    }
    
    public function setUrl($url){
        $this->_url = $url;
    }



}