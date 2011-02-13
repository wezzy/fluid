<?php

namespace Fluid;

require('libs/smarty/Smarty.class.php');

/**
 * Description of TemplateEngine
 *
 * @author wezzy
 */
class TemplateEngine extends \Smarty{
    function TemplateEngine(){
        
        $this->compile_dir = APPLICATION_PATH . '/var/smarty/templates_c';
        $this->cache_dir = APPLICATION_PATH . '/var/smarty/cache';
        $this->config_dir = APPLICATION_PATH . '/app/config';
		
		// Setup some values for each template
		$this->assign('application_path', APPLICATION_PATH);
    }
}