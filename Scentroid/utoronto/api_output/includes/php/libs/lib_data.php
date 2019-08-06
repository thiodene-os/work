<?php

// SQL + Email or SMS handling and sending
// save PolluTracker Data
function recordPolluTrackerData($pt, $data)
{
  // Connect to db
  $dbc = db_connect_ut() ;
  
  $result = '' ;
  //echo $data ; 
  // Get the date for sesion Table
  $session_dt = date("dmY", strtotime("now"));
  
  // The data in CSV has to exploded and saved in the correct order
  $data_array = explode(",", $data) ;
  $value_co_mv = $data_array[0] ;
  //echo $data_array[0] ;
  $value_co_ppm = $data_array[1] ;
  $value_no2_mv = $data_array[2] ;
  $value_no2_ppm = $data_array[3] ;
  $value_o3_mv = $data_array[4] ;
  $value_o3_ppm = $data_array[5] ;
  
  $value_pm1_ugm3 = $data_array[6] ;
  $value_pm25_ugm3 = $data_array[7] ;
  $value_pm4_ugm3 = $data_array[8] ;
  $value_pm10_ugm3 = $data_array[9] ;
  
  $value_temp = $data_array[10] ;
  $value_humid = $data_array[11] ;
  
  // Check if there is an awaiting polly tracker data to be saved
  $query = "SELECT small_data.id AS data_id
             FROM small_data
        WHERE status" . $pt . " IS NOT NULL"
        . " ORDER BY id DESC LIMIT 1" ;
  $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
  if (@mysqli_num_rows($result) != 0)
  {
    $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
    $data_id = $row[0] ;
    
    // Calculate the AQI for each compound before the UPDATE
    $query3 = "SELECT *
              FROM config_aqi
              WHERE id" . $config_id . " IS NOT NULL" ;
    $result3 = mysqli_query($dbc, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc));
    $row3 = mysqli_fetch_array($result3, MYSQLI_NUM) ;
    // AQI Initial value
    $aqi = 0 ;
    // CO
    $max_good = $row3[1] ;
    $max_moderate = $row3[2] ;
    $max_sensitive = $row3[3] ;
    $max_unhealthy = $row3[4] ;
    $max_very = $row3[5] ;
    $max_hazardous = $row3[6] ;
    $aqi_co = calculatePollutantAQI($value_co_ppm, $max_good, $max_moderate, $max_sensitive, $max_unhealthy, $max_very, $max_hazardous) ;
    if ($aqi_co > $aqi)
      $aqi = $aqi_co ;
    
    // NO2
    $max_good = $row3[7] ;
    $max_moderate = $row3[8] ;
    $max_sensitive = $row3[9] ;
    $max_unhealthy = $row3[10] ;
    $max_very = $row3[11] ;
    $max_hazardous = $row3[12] ;
    $aqi_no2 = calculatePollutantAQI($value_no2_ppm, $max_good, $max_moderate, $max_sensitive, $max_unhealthy, $max_very, $max_hazardous) ;
    if ($aqi_no2 > $aqi)
      $aqi = $aqi_no2 ;
    
    // O3
    $max_good = $row3[13] ;
    $max_moderate = $row3[14] ;
    $max_sensitive = $row3[15] ;
    $max_unhealthy = $row3[16] ;
    $max_very = $row3[17] ;
    $max_hazardous = $row3[18] ;
    $aqi_o3 = calculatePollutantAQI($value_o3_ppm, $max_good, $max_moderate, $max_sensitive, $max_unhealthy, $max_very, $max_hazardous) ;
    if ($aqi_o3 > $aqi)
      $aqi = $aqi_o3 ;
    
    // PM1
    $max_good = $row3[13] ;
    $max_moderate = $row3[14] ;
    $max_sensitive = $row3[15] ;
    $max_unhealthy = $row3[16] ;
    $max_very = $row3[17] ;
    $max_hazardous = $row3[18] ;
    $aqi_pm1 = calculatePollutantAQI($value_pm1_ugm3, $max_good, $max_moderate, $max_sensitive, $max_unhealthy, $max_very, $max_hazardous) ;
    if ($aqi_pm1 > $aqi)
      $aqi = $aqi_pm1 ;
    
    // PM2.5
    $max_good = $row3[19] ;
    $max_moderate = $row3[20] ;
    $max_sensitive = $row3[21] ;
    $max_unhealthy = $row3[22] ;
    $max_very = $row3[23] ;
    $max_hazardous = $row3[24] ;
    $aqi_pm25 = calculatePollutantAQI($value_pm25_ugm3, $max_good, $max_moderate, $max_sensitive, $max_unhealthy, $max_very, $max_hazardous) ;
    if ($aqi_pm25 > $aqi)
      $aqi = $aqi_pm25 ;
    
    // PM4
    $max_good = $row3[25] ;
    $max_moderate = $row3[26] ;
    $max_sensitive = $row3[27] ;
    $max_unhealthy = $row3[28] ;
    $max_very = $row3[29] ;
    $max_hazardous = $row3[30] ;
    $aqi_pm4 = calculatePollutantAQI($value_pm4_ugm3, $max_good, $max_moderate, $max_sensitive, $max_unhealthy, $max_very, $max_hazardous) ;
    if ($aqi_pm4 > $aqi)
      $aqi = $aqi_pm4 ;
    
    // PM10
    $max_good = $row3[31] ;
    $max_moderate = $row3[32] ;
    $max_sensitive = $row3[33] ;
    $max_unhealthy = $row3[34] ;
    $max_very = $row3[35] ;
    $max_hazardous = $row3[36] ;
    $aqi_pm10 = calculatePollutantAQI($value_pm10_ugm3, $max_good, $max_moderate, $max_sensitive, $max_unhealthy, $max_very, $max_hazardous) ;
    if ($aqi_pm10 > $aqi)
      $aqi = $aqi_pm10 ;
    
    // UPDATE in terms of data
    // + in the end UPDATE the status of this record
    // UPDATE main small data table
    $query2 = "UPDATE small_data 
               SET value" . $pt . "_co_mv=" . $value_co_mv . ",value" . $pt . "_co_ppm=" . $value_co_ppm . "
               ,value" . $pt . "_no2_mv=" . $value_no2_mv . ",value" . $pt . "_no2_ppm=" . $value_no2_ppm . "
               ,value" . $pt . "_o3_mv=" . $value_o3_mv . ",value" . $pt . "_o3_ppm=" . $value_o3_ppm . "
               ,value" . $pt . "_pm1_ugm3=" . $value_pm1_ugm3 . ",value" . $pt . "_pm25_ugm3=" . $value_pm25_ugm3 . "
               ,value" . $pt . "_pm4_ugm3=" . $value_pm4_ugm3 . ",value" . $pt . "_pm10_ugm3=" . $value_pm10_ugm3 . "
               ,value" . $pt . "_temp=" . $value_temp . ",value" . $pt . "_humid=" . $value_humid . "
               ,value" . $pt . "_aqi=" . $aqi . ", status" . $pt . "=NULL"
              . " WHERE id = " . $data_id ;
    $result2 = mysqli_query($dbc, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc)) ;
    
    if (mysqli_affected_rows($dbc) != 0)
      $result = '{"success": true}' ;
    else
      $result = '{"success": false}' ;
    
    
    
    // SELECT the very last record and UPDATE it
    $query4 = "SELECT id
              FROM small_data_" . $session_dt ."
              ORDER BY id DESC LIMIT 1" ;
    $result4 = mysqli_query($dbc, $query4) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysqli_error($dbc));
    $row4 = mysqli_fetch_array($result4, MYSQLI_NUM) ;
    $session_data_id = $row4[0] ;
    
    // UPDATE session small data table
    $query5 = "UPDATE small_data_" . $session_dt ." 
               SET value" . $pt . "_co_mv=" . $value_co_mv . ",value" . $pt . "_co_ppm=" . $value_co_ppm . "
               ,value" . $pt . "_no2_mv=" . $value_no2_mv . ",value" . $pt . "_no2_ppm=" . $value_no2_ppm . "
               ,value" . $pt . "_o3_mv=" . $value_o3_mv . ",value" . $pt . "_o3_ppm=" . $value_o3_ppm . "
               ,value" . $pt . "_pm1_ugm3=" . $value_pm1_ugm3 . ",value" . $pt . "_pm25_ppm=" . $value_pm25_ugm3 . "
               ,value" . $pt . "_pm4_ugm3=" . $value_pm4_ugm3 . ",value" . $pt . "_pm10_ppm=" . $value_pm10_ugm3 . "
               ,value" . $pt . "_temp=" . $value_temp . ",value" . $pt . "_humid=" . $value_humid . "
               , status" . $pt . "=NULL"
              . " WHERE id = " . $session_data_id ;
    $result5 = mysqli_query($dbc, $query5) or trigger_error("Query: $query5\n<br>MySQL Error: " . mysqli_error($dbc)) ;
    
    
    
  }
  else
  {
     $result = '{"success": false}' ;
  }  
  // Close db
  db_close($dbc) ;
  
  return $result ;
} //recordPolluTrackerData
// Builds and returns the JSON output for the latest recorded data
function buildJSONDataOutput($timestamp=false)
{
  
  // Connect to db
  $dbc = db_connect_ut() ;
  
  $json_output = '' ;
  
  // ----------------------------------------------------- BEGIN IMAGES ----------------------------
  // List of Directories for snapshots of LIDAR and 360 CAM
  $path_cam = "/home/scentroid/cam_snapshot/camera" ;
  $path_lidar = "/home/scentroid/cam_snapshot/lidar" ;
  $path_image = "/var/www/html/images" ;
  // List the files in those directories
  $files_lidar = array_diff(scandir($path_lidar), array('.', '..')) ;
  $files_cam = array_diff(scandir($path_cam), array('.', '..')) ;
  
  // -----------------------------LIDAR
  $file_lidar_list = array() ;
  $latest_lidar_tmstp = 0 ;
  foreach ($files_lidar as $file)
  {
    $file_lidar_tmstp = filemtime($path_lidar . '/' . $file) ;
    //echo $file . ": " . $file_lidar_tmstp ;
    $file_lidar_list[$file_lidar_tmstp] = $file ;
    if($latest_lidar_tmstp <= $file_lidar_tmstp)
      $latest_lidar_tmstp = $file_lidar_tmstp ;
  }
  //echo $file_lidar_list[$latest_lidar_tmstp] ;
  $latest_lidar_file = $file_lidar_list[$latest_lidar_tmstp] ;
  
  // -----------------------------CAM
  $file_cam_list = array() ;
  $latest_cam_tmstp = 0 ;
  foreach ($files_cam as $file)
  {
    $file_cam_tmstp = filemtime($path_cam . '/' . $file) ;
    //echo $file . ": " . $file_cam_tmstp ;
    $file_cam_list[$file_cam_tmstp] = $file ;
    if($latest_cam_tmstp <= $file_cam_tmstp)
      $latest_cam_tmstp = $file_cam_tmstp ;
  }
  //echo $file_cam_list[$latest_cam_tmstp] ;
  $latest_cam_file = $file_cam_list[$latest_cam_tmstp] ;
  
  // Now copy the file over to /mages
  // LIDAR
  if (!copy($path_lidar . '/' . $latest_lidar_file, $path_image . '/' . $latest_lidar_file))
    echo "" ; // "LIDAR image copy failed!" ;
  // CAM
  if (!copy($path_cam . '/' . $latest_cam_file, $path_image . '/' . $latest_cam_file))
    echo "" ; // "360CAM image copy failed!" ;
  
  // ----------------------------------------------------- END IMAGES ------------------------------
  
  // Move the very last picture over to /var/www/images.
  //rename
  
  // Go through the last SQL record and populate the JSON
  $query = "SELECT small_data.id AS smalldata_id, lat, lon, wind_speed, wind_direction
        , vehicle_speed, vehicle_direction
        , value1_lat, value1_lon
        , value1_co_mv, value1_co_ppm, value1_no2_mv, value1_no2_ppm
        , value1_o3_mv, value1_o3_ppm, value1_pm1_ugm3, value1_pm25_ugm3
        , value1_pm4_ugm3, value1_pm10_ugm3, value1_temp, value1_humid, value1_aqi
        , value2_lat, value2_lon
        , value2_co_mv, value2_co_ppm, value2_no2_mv, value2_no2_ppm
        , value2_o3_mv, value2_o3_ppm, value2_pm1_ugm3, value2_pm25_ugm3
        , value2_pm4_ugm3, value2_pm10_ugm3, value2_temp, value2_humid, value2_aqi
        , timestamp
        FROM small_data"
        . " ORDER BY timestamp DESC LIMIT 1" ;
  $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
  $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
  $id = $row[0] ;
  $lat = $row[1] ; $lon = $row[2] ;
  
  $wind_speed = $row[3] ; $wind_direction = $row[4] ;
  $vehicle_speed = $row[5] ; $vehicle_direction = $row[6] ;
  
  // Gas Sensors 1
  $lat_s1 = $row[7] ; $lon_s1 = $row[8] ;
  $co_s1_mv = $row[9] ; $co_s1_ppm = $row[10] ; $co_s1_dataunit = 'PPM' ;
  $no2_s1_mv = $row[11] ; $no2_s1_ppm = $row[12] ; $no2_s1_dataunit = 'PPM' ;
  $o3_s1_mv = $row[13] ; $o3_s1_ppm = $row[14] ;  $o3_s1_dataunit = 'PPM' ;
  $pm1_s1_ugm3 = $row[15] ; $pm1_s1_dataunit = 'ug/m3' ;
  $pm25_s1_ugm3 = $row[16] ; $pm25_s1_dataunit = 'ug/m3' ;
  $pm4_s1_ugm3 = $row[17] ; $pm4_s1_dataunit = 'ug/m3' ; 
  $pm10_s1_ugm3 = $row[18] ; $pm10_s1_dataunit = 'ug/m3' ;
  $temp_s1 = $row[19] ; $temp_s1_dataunit = 'C' ;
  $humid_s1 = $row[20] ; $humid_s1_dataunit = '%' ;
  
  $aqi_s1 = $row[21] ;
  
  // Gas Sensors 2
  $lat_s2 = $row[22] ; $lon_s2 = $row[23] ;
  $co_s2_mv = $row[24] ; $co_s2_ppm = $row[25] ; $co_s2_dataunit = 'PPM' ;
  $no2_s2_mv = $row[26] ; $no2_s2_ppm = $row[27] ; $no2_s2_dataunit = 'PPM' ;
  $o3_s2_mv = $row[28] ; $o3_s2_ppm = $row[29] ;  $o3_s2_dataunit = 'PPM' ;
  $pm1_s2_ugm3 = $row[30] ; $pm1_s2_dataunit = 'ug/m3' ;
  $pm25_s2_ugm3 = $row[31] ; $pm25_s2_dataunit = 'ug/m3' ;
  $pm4_s2_ugm3 = $row[32] ; $pm4_s2_dataunit = 'ug/m3' ; 
  $pm10_s2_ugm3 = $row[33] ; $pm10_s2_dataunit = 'ug/m3' ;
  $temp_s2 = $row[34] ; $temp_s2_dataunit = 'C' ;
  $humid_s2 = $row[35] ; $humid_s2_dataunit = '%' ;
  
  $aqi_s2 = $row[36] ;
  
  
  $timestamp = $row[37] ;
  
  //$cam_path = 'http://localhost/images/' . $latest_cam_file ;
  //$lidar_path = 'http://localhost/images/' . $latest_lidar_file ;
  $cam_path = '/images/' . $latest_cam_file ;
  $lidar_path = '/images/' . $latest_lidar_file ;
  
  // Start with Timestamp
  $json_output .= '{"timestamp":"' . $timestamp . '",' ;
  // GPS Data
  // LAT
  if ($lat)
    $json_output .= '"lat":"' . $lat . '",' ;
  else
    $json_output .= '"lat":"",' ;
  // LON
  if ($lon)
    $json_output .= '"lon":"' . $lon . '",' ;
  else
    $json_output .= '"lon":"",' ;
  // Speed + Direction
  $json_output .= '"wind":["' . $wind_speed . '","' . $wind_direction . '"],' ;
  $json_output .= '"vehicle":["' . $vehicle_speed . '","' . $vehicle_direction . '"],' ;
  
  // Gas Sensors 1
  // LAT
  if ($lat_s1)
    $json_output .= '"lat_s1":"' . $lat_s1 . '",' ;
  else
    $json_output .= '"lat_s1":"",' ;
  // LON
  if ($lon_s1)
    $json_output .= '"lon_s1":"' . $lon . '",' ;
  else
    $json_output .= '"lon_s1":"",' ;
  $json_output .= '"co_s1":["' . $co_s1_mv . '","mV","' . $co_s1_ppm . '","' . $co_s1_dataunit . '"],' ;
  $json_output .= '"no2_s1":["' . $no2_s1_mv . '","mV","' . $no2_s1_ppm . '","' . $no2_s1_dataunit . '"],' ;
  $json_output .= '"o3_s1":["' . $o3_s1_mv . '","mV","' . $o3_s1_ppm . '","' . $o3_s1_dataunit . '"],' ;
  $json_output .= '"pm1_s1":["' . $pm1_s1_ugm3 . '","' . $pm1_s1_dataunit . '"],' ; 
  $json_output .= '"pm2.5_s1":["' . $pm25_s1_ugm3 . '","' . $pm25_s1_dataunit . '"],' ;
  $json_output .= '"pm4_s1":["' . $pm4_s1_ugm3 . '","' . $pm4_s1_dataunit . '"],' ;
  $json_output .= '"pm10_s1":["' . $pm10_s1_ugm3 . '","' . $pm10_s1_dataunit . '"],' ;
  $json_output .= '"temp_s1":["' . $temp_s1 . '","' . $temp_s1_dataunit . '"],' ;
  $json_output .= '"humid_s1":["' . $humid_s1 . '","' . $humid_s1_dataunit . '"],' ;
  // AQI 1
  if ($aqi_s1)
    $json_output .= '"aqi_s1":"' . $aqi_s1 . '",' ;
  else
    $json_output .= '"aqi_s1":"",' ;
  // Gas Sensors 2
  // LAT
  if ($lat_s2)
    $json_output .= '"lat_s2":"' . $lat_s2 . '",' ;
  else
    $json_output .= '"lat_s2":"",' ;
  // LON
  if ($lon_s2)
    $json_output .= '"lon_s2":"' . $lon_s2 . '",' ;
  else
    $json_output .= '"lon_s2":"",' ;
  $json_output .= '"co_s2":["' . $co_s2_mv . '","mV","' . $co_s2_ppm . '","' . $co_s2_dataunit . '"],' ;
  $json_output .= '"no2_s2":["' . $no2_s2_mv . '","mV","' . $no2_s2_ppm . '","' . $no2_s2_dataunit . '"],' ;
  $json_output .= '"o3_s2":["' . $o3_s2_mv . '","mV","' . $o3_s2_ppm . '","' . $o3_s2_dataunit . '"],' ;
  $json_output .= '"pm1_s2":["' . $pm1_s2_ugm3 . '","' . $pm1_s2_dataunit . '"],' ; 
  $json_output .= '"pm2.5_s2":["' . $pm25_s2_ugm3 . '","' . $pm25_s2_dataunit . '"],' ;
  $json_output .= '"pm4_s2":["' . $pm4_s2_ugm3 . '","' . $pm4_s2_dataunit . '"],' ;
  $json_output .= '"pm10_s2":["' . $pm10_s2_ugm3 . '","' . $pm10_s2_dataunit . '"],' ;
  $json_output .= '"temp_s2":["' . $temp_s2 . '","' . $temp_s2_dataunit . '"],' ;
  $json_output .= '"humid_s2":["' . $humid_s2 . '","' . $humid_s2_dataunit . '"],' ;
  // AQI 2
  if ($aqi_s2)
    $json_output .= '"aqi_s2":"' . $aqi_s2 . '",' ;
  else
    $json_output .= '"aqi_s2":"",' ;
  // Snapshot image 360
  $json_output .= '"cam360":"' . $cam_path . '",' ;
  $json_output .= '"lidar":"' . $lidar_path . '"' ;
  
  $json_output .= '}' ;
  
  // Close db
  db_close($dbc) ;
  
  return $json_output ;
  
}

function calculatePollutantAQI($concentration, $max_good, $max_moderate, $max_sensitive, $max_unhealthy, $max_very, $max_hazardous)
{

  // From concentration get the concentration low and high for that concentration
  if ($concentration <= $max_good)
  {
    $c_low = 0 ; $c_high = $max_good ; $i_low = 0 ; $i_high = 50 ;
    if ($c_high == 0)
      $aqi = 0 ;
    else
      $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
  }
  elseif ($concentration > $max_good && $concentration <= $max_moderate)
  {
    $c_low = $max_good ; $c_high = $max_moderate ; $i_low = 51 ; $i_high = 100 ;
    if ($c_high - $c_low == 0)
      $aqi = 0 ;
    else
      $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
  }
  elseif ($concentration > $max_moderate && $concentration <= $max_sensitive)
  {
    $c_low = $max_moderate ; $c_high = $max_sensitive ; $i_low = 101 ; $i_high = 150 ;
    if ($c_high - $c_low == 0)
      $aqi = 0 ;
    else
      $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
  }
  elseif ($concentration > $max_sensitive && $concentration <= $max_unhealthy)
  {
    $c_low = $max_sensitive ; $c_high = $max_unhealthy ; $i_low = 151 ; $i_high = 200 ;
    if ($c_high - $c_low == 0)
      $aqi = 0 ;
    else
      $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
  }
  elseif ($concentration > $max_unhealthy && $concentration <= $max_very)
  {
    $c_low = $max_unhealthy ; $c_high = $max_very ; $i_low = 201 ; $i_high = 300 ;
    if ($c_high - $c_low == 0)
      $aqi = 0 ;
    else
      $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
  }
  elseif ($concentration > $max_very && $concentration <= $max_hazardous)
  {
    $c_low = $max_very ; $c_high = $max_hazardous ; $i_low = 301 ; $i_high = 500 ;
    if ($c_high - $c_low == 0)
      $aqi = 0 ;
    else
      $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
  }
  elseif ($concentration > $max_hazardous)
  {
    $aqi = 500 ;
  }
  
  return $aqi ;
  
}

?>
