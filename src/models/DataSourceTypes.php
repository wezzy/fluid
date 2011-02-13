<?php 

require_once("src/models/Model.php");

class DataSourceTypes extends Model{
	
	protected $_name = 'lkp_datasources_types';
    protected $_primary = 'id';
    protected $_sequence = true;

}