<?php

// For allowing remote connection to API add the following header:
header('Access-Control-Allow-Origin: *') ;

$timestamp = strtotime("now");

$aqi1 = rand(0,500) ;
$aqi2 = rand(0,500) ;

$wind_speed = rand(0,20);
$wind_direction = rand(0,360);
$vehicle_speed = rand(0,20);
$vehicle_direction = rand(0,360);

$co_mv_s1 = rand(0,500)/50 ;
$co_mv_s2 = rand(0,500)/50 ;
$co_ppb_s1 = rand(0,5000)/10 ;
$co_ppb_s2 = rand(0,5000)/10 ;

$no2_mv_s1 = rand(0,500)/50 ;
$no2_mv_s2 = rand(0,500)/50 ;
$no2_ppb_s1 = rand(0,5000)/10 ;
$no2_ppb_s2 = rand(0,5000)/10 ;

$o3_mv_s1 = rand(0,500)/50 ;
$o3_mv_s2 = rand(0,500)/50 ;
$o3_ppb_s1 = rand(0,5000)/10 ;
$o3_ppb_s2 = rand(0,5000)/10 ;

$pm1_ugm3_s1 = rand(0,5000)/100 ;
$pm1_ugm3_s2 = rand(0,5000)/100 ;
$pm25_ugm3_s1 = rand(0,5000)/100 ;
$pm25_ugm3_s2 = rand(0,5000)/100 ;
$pm4_ugm3_s1 = rand(0,5000)/100 ;
$pm4_ugm3_s2 = rand(0,5000)/100 ;
$pm10_ugm3_s1 = rand(0,5000)/100 ;
$pm10_ugm3_s2 = rand(0,5000)/100 ;

$temp_s1 = rand(12,30) ;
$temp_s2 = rand(12,30) ;

$humid_s1 = rand(0,100)/100 ;
$humid_s2 = rand(0,100)/100 ;

$lat = 43.96172 + rand(1,100)/100000 ;
$lon = -79.26752 + rand(1,100)/100000 ;

$build_data_output = '{"timestamp":"' . $timestamp . '",'
                     . '"lat":"' . $lat . '","lon":"' . $lon . '",'
                     . '"wind":["' . $wind_speed . '","' . $wind_direction . '"],'
                     . '"vehicle":["' . $vehicle_speed . '","' . $vehicle_direction . '"],'
                     . '"co_s1":["' . $co_mv_s1 . '","mV","' . $co_ppb_s1 . '","PPB"],'
                     . '"no2_s1":["' . $no2_mv_s1 . '","mV","' . $no2_ppb_s1 . '","PPB"],'
                     . '"o3_s1":["' . $o3_mv_s1 . '","mV","' . $o3_ppb_s1 . '","PPB"],'
                     . '"pm1_s1":["' . $pm1_ugm3_s1 . '","ug/m3"],'
                     . '"pm2.5_s1":["' . $pm25_ugm3_s1 . '","ug/m3"],'
                     . '"pm4_s1":["' . $pm4_ugm3_s1 . '","ug/m3"],'
                     . '"pm10_s1":["' . $pm10_ugm3_s1 . '","ug/m3"],'
                     . '"temp_s1":["' . $temp_s1 . '","C"],'
                     . '"humid_s1":["' . $humid_s1 . '","%"],'
                     . '"aqi_s1":"' . $aqi1 . '",'
                     . '"co_s2":["' . $co_mv_s2 . '","mV","' . $co_ppb_s2 . '","PPB"],'
                     . '"no2_s2":["' . $no2_mv_s2 . '","mV","' . $no2_ppb_s2 . '","PPB"],'
                     . '"o3_s2":["' . $o3_mv_s2 . '","mV","' . $o3_ppb_s2 . '","PPB"],'
                     . '"pm1_s2":["' . $pm1_ugm3_s2 . '","ug/m3"],'
                     . '"pm2.5_s2":["' . $pm25_ugm3_s2 . '","ug/m3"],'
                     . '"pm4_s2":["' . $pm4_ugm3_s2 . '","ug/m3"],'
                     . '"pm10_s2":["' . $pm10_ugm3_s2 . '","ug/m3"],'
                     . '"temp_s2":["' . $temp_s2 . '","C"],'
                     . '"humid_s2":["' . $humid_s2 . '","%"],'
                     . '"aqi_s2":"' . $aqi2 . '",'
                     . '"cam360":"/images/camera' . rand(1,10) . '.jpg",'
                     . '"lidar":"/images/lidar' . rand(1,10) . '.png"}' ;


echo $build_data_output ;




?>
