<?php 

require_once("src/models/Model.php");

class DataSources extends Model{
	
	protected $_name = 'datasources';
    protected $_primary = 'id';
    protected $_sequence = true;

}