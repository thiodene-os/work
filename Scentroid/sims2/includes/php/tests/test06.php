<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
require_once(TEMPLATES_PATH . "/template_main.php");


// Connect to db
$dbc = db_connect_local() ;

$equipment = 34 ;
//$last_data = getEquipmentLastMetData($equipment) ;
$avg_data = getEquipmentDataAverages($equipment, true) ;

//echo $obj->VOCs[0] ;

echo var_dump($avg_data) ;

// Close db
db_close($dbc) ;

?>