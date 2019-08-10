<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
//require_once(TEMPLATES_PATH . "/template_main.php");

$company = 41 ;

$aqi_table = buildSensorAQITable($company) ;


echo $aqi_table ;


?>