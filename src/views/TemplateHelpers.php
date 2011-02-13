<?php

namespace Fluid;

/**
 * Some methods useful when rendering pages
 *
 * @author wezzy
 */
class TemplateHelpers {

    public static function linkJs($fileName){
        if($fileName == "") return;
		$out = "";
		if(is_array($fileName)){
			for($i = 0; $i < count($fileName); $i++){
				$out .= "<script src='" . $fileName[$i] . "' type='text/javascript' charset='utf-8'></script>\n";
			}
		}else{
			$out .= "<script src='" . $fileName . "' type='text/javascript' charset='utf-8'></script>\n";
		}
        return $out;
    }

    public static function includeJs($fileName){
        if($fileName == "") return;

        return '<script type="text/javascript" charset="utf-8">' . $fileName . '</script>\n';
    }

    public static function linkCss($fileName){
        if($fileName == "") return;

		$out = "";
		if(is_array($fileName)){
			for($i = 0; $i < count($fileName); $i++){
				$out .= "<link rel='stylesheet' href='" . $fileName[$i] . "' type='text/css' charset='utf-8'>\n";
			}
		}else{
			$out .= "<link rel='stylesheet' href='" . $fileName . "' type='text/css' charset='utf-8'>\n";
		}
		
        return $out;
    }

    public static function includeCss($fileName){
        if($fileName == "") return;

        return '<style type="text/css" media="screen">' . $fileName . '</style>\n';
    }

    public static function javascriptConfig($zone_id = 0, $page_id = 0){

        require_once("src/Factory.php");

        $config = FFactory::get('config');

		$data = array();
		$data['basePath'] = $config->url;
		$data['yuiPath'] = $config->yui;
        $data['zoneId'] = $zone_id;
		$data['pageId'] = $page_id;
        $data['allowRollup'] = 'false';
        $data['combine'] = 'false';
        $data['filter'] = 'raw';


		$out = "F.config = {};\n";
		foreach($data as $key => $value){
			$out .= "F.config." . $key . "='" . $value . "';\n";
		}

        return $out;
    }

}

