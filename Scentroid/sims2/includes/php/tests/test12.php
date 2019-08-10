<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
//require_once(TEMPLATES_PATH . "/template_main.php");

$pollutant = 'Ammonia-lc' ;
$pollutant = shortenSensorName($pollutant, true) ;
$data_unit = 'ppb' ;
$concentration = 444 ;
//$time_avg = 1 ;
//$time_avg = 8 ;
$time_avg = 24 ;

$aqi = calculatePollutantAQI($pollutant, $data_unit, $concentration, $time_avg) ;

if ($aqi)
  echo $pollutant . ", AQI: " . $aqi ;
else
  echo $pollutant . ", AQI: " . '-' ;

?>