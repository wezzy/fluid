<?php

namespace Fluid;

fb("Inizializing db ...");

$db = Factory::get('dbAdapter');

// Add default zone
$zones = $db->zone;
$obj = array( "name" => "default");
$zones->insert($obj);

fb("Db initialized");