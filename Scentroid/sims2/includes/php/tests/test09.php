<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
//require_once(TEMPLATES_PATH . "/template_main.php");

// Connect to db
$dbc_local = db_connect_local() ;

// Test equipment value
//$equipment = 43 ;

$sensor_json = '[{"name":"VOCs-LC","id":"240","value":"58.813","unit":"PPB"},{"name":"Hydrogen Sulfide-LC","id":"241","value":"222.363","unit":"PPB"},{"name":"Ammonia-LC","id":"242","value":"377.051","unit":"PPB"},{"name":"Internal Temperature","id":"243","value":"0.00","unit":"C"},{"name":"Internal Humidity","id":"244","value":"0.00","unit":"%"},{"name":"External Temperature","id":"245","value":"27.70","unit":"C"},{"name":"External Humidity","id":"246","value":"1.00","unit":"%"},{"name":"Wind Speed","id":"247","value":"8.047","unit":"m/s"},{"name":"Wind Direction","id":"248","value":"293.000","unit":"°"},{"name":"Sulfur Dioxide-LC","id":"249","value":"223.169","unit":"PPB"}]' ;
$sensor_obj = json_decode($sensor_json) ;

if ($sensor_obj)
  echo "JSON: OK" ;
else
  echo "Bad JSON" ;

// Close db
db_close($dbc_local) ;

?>