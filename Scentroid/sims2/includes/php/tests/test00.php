<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
require_once(TEMPLATES_PATH . "/template_main.php");

//$lat = -26.930111 ;
//$lon = 27.916389 ;
$lat = 0 ;
$lon = 0 ;

$wind_speed = 0.000 ;
$wind_direction = 90.000 ;

$test_result = calcPolygonCoordinates($lat, $lon, $wind_speed, $wind_direction) ;

// Echo an array
var_dump($test_result);
//echo $test_result ;

?>