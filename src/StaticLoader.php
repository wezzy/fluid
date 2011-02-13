<?php

namespace Fluid;

/**
 * ResourceLoader store request for static files (js and css) made during the execution of the
 * code and is used by the page template to display resources in the right place, the head for
 * css and the bottom of the page for js files
 *
 * @author wezzy
 */
class StaticLoader {
    
    private $_css;
    private $_js;

    /**
     * Default constructor
     */
    public function __construct(){
        $this->_css = array();
        $this->_js = array();
    }

    /**
     * Store a request for a specific file. It checks for duplicates and ignore the file that are alredy requested
     * @param string $fileUrl the url of the requested file
     */
    public function request($fileUrl){
        $arr = null;

        if(stripos($fileUrl, ".js") > 0) $arr =& $this->_js;
        if(stripos($fileUrl, ".css") > 0) $arr =& $this->_css;

        if(in_array($fileUrl, $arr)) return;

        $arr[] = $fileUrl;
    }

    /**
     * Return an array with all the javascript file requested
     * @return array the array with all the javascript file requested
     */
    public function getJs(){
        return $this->_js;
    }

    /**
     * Return an array with all the css file requested
     * @return array the array with all the css file requested
     */
    public function getCss(){
        return $this->_css;
    }

}