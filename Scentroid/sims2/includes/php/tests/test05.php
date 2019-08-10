<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
//require_once(TEMPLATES_PATH . "/template_main.php");

$equipment = 50;
$chart_number = 1;

$buildchart = buildSensorTable($equipment, $chart_number, $met=false) ;

echo $buildchart ;

//echo $obj->VOCs[0] ;

// Close db
//db_close($dbc) ;

?>