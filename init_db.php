<?php

namespace Fluid;

fb("Inizializing db ...");

$db = Factory::get('dbAdapter');

// Add default zone
$zones = $db->zones;
$obj = array( "name" => "default");
$zones->insert($obj);

// Retrieve the default zone id
$query = array(
	'name' => "default"
);
$cursor = $zones->find($query);
$defaultZone = $cursor->getNext();

// Add default page
$pages = $db->pages;
$obj = array(
	"name" => "home",
	'zone_id' => $defaultZone['_id'],
	'father_id' => NULL,
	'is_default' => true,
	'url' => "/default/home"
);
$pages->insert($obj);

// Indexing
$zones->ensureIndex(array("name" => 1), array('unique' => true)); 
$pages->ensureIndex(array("name" => -1), array('unique' => true)); 

fb("Db initialized");