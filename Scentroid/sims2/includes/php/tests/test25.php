<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
//require_once(TEMPLATES_PATH . "/template_main.php");

$data_unit = 'ug/m3' ;
$concentration = 32 ;
$user_id = 10 ;
$sensor_id = 330 ;
//$time_avg = 1 ;
//$time_avg = 8 ;
$time_avg = 24 ;

$aqi = calculateUserDefinedAQI($user_id, $sensor_id, $data_unit, $concentration, $time_avg) ;

if ($aqi)
  echo $sensor_id . ", AQI: " . $aqi ;
else
  echo $sensor_id . ", AQI: " . '-' ;

?>