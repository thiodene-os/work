<?php

// Notification libraires for Alarms, Health and all types of Notifications
// SQL + Email or SMS handling and sending

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
    echo "LIDAR image copy failed!" ;
  // CAM
  if (!copy($path_cam . '/' . $latest_cam_file, $path_image . '/' . $latest_cam_file))
    echo "360CAM image copy failed!" ;
  
  // ----------------------------------------------------- END IMAGES ------------------------------
  
  // Move the very last picture over to /var/www/images.
  //rename
  
  // Go through the last SQL record and populate the JSON
  $query = "SELECT small_data.id AS smalldata_id, lat, lon, wind_speed, wind_direction
        , vehicle_speed, vehicle_direction
        , value1_co_mv, value1_co_ppm, value1_no2_mv, value1_no2_ppm
        , value1_o3_mv, value1_o3_ppm, value1_pm1_ugm3, value1_pm25_ugm3
        , value1_pm4_ugm3, value1_pm10_ugm3, value1_temp, value1_humid, value1_aqi
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
  $co_s1_mv = $row[7] ; $co_s1_ppm = $row[8] ; $co_s1_dataunit = 'PPM' ;
  $no2_s1_mv = $row[9] ; $no2_s1_ppm = $row[10] ; $no2_s1_dataunit = 'PPM' ;
  $o3_s1_mv = $row[11] ; $o3_s1_ppm = $row[12] ;  $o3_s1_dataunit = 'PPM' ;
  $pm1_s1_ugm3 = $row[13] ; $pm1_s1_dataunit = 'ug/m3' ;
  $pm25_s1_ugm3 = $row[14] ; $pm25_s1_dataunit = 'ug/m3' ;
  $pm4_s1_ugm3 = $row[15] ; $pm4_s1_dataunit = 'ug/m3' ; 
  $pm10_s1_ugm3 = $row[16] ; $pm10_s1_dataunit = 'ug/m3' ;
  $temp_s1 = $row[17] ; $temp_s1_dataunit = 'C' ;
  $humid_s1 = $row[18] ; $humid_s1_dataunit = '%' ;
  
  $aqi_s1 = $row[19] ;
  
  // Gas Sensors 2
  $co_s2_mv = $row[20] ; $co_s2_ppm = $row[21] ; $co_s2_dataunit = 'PPM' ;
  $no2_s2_mv = $row[22] ; $no2_s2_ppm = $row[23] ; $no2_s2_dataunit = 'PPM' ;
  $o3_s2_mv = $row[24] ; $o3_s2_ppm = $row[25] ;  $o3_s2_dataunit = 'PPM' ;
  $pm1_s2_ugm3 = $row[26] ; $pm1_s2_dataunit = 'ug/m3' ;
  $pm25_s2_ugm3 = $row[27] ; $pm25_s2_dataunit = 'ug/m3' ;
  $pm4_s2_ugm3 = $row[28] ; $pm4_s2_dataunit = 'ug/m3' ; 
  $pm10_s2_ugm3 = $row[29] ; $pm10_s2_dataunit = 'ug/m3' ;
  $temp_s2 = $row[30] ; $temp_s2_dataunit = 'C' ;
  $humid_s2 = $row[31] ; $humid_s2_dataunit = '%' ;
  
  $aqi_s2 = $row[32] ;
  
  
  $timestamp = $row[33] ;
  
  $cam_path = 'http://localhost/images/' . $latest_cam_file ;
  $lidar_path = 'http://localhost/images/' . $latest_lidar_file ;
  
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
  $json_output .= '"co_s2":["' . $co_s2_mv . '","mV","' . $co_s2_ppm . '","' . $co_s2_dataunit . '"],' ;
  $json_output .= '"no2_s2":["' . $no2_s2_mv . '","mV","' . $no2_s2_ppm . '","' . $no2_s2_dataunit . '"],' ;
  $json_output .= '"o3_s2":["' . $o3_s2_mv . '","mV","' . $o3_s2_ppm . '","' . $o3_s2_dataunit . '"],' ;
  $json_output .= '"pm1_s2":["' . $pm1_s2_ugm3 . '","' . $pm1_s2_dataunit . '"],' ; 
  $json_output .= '"pm2.5_s2":["' . $pm25_s2_ugm3 . '","' . $pm25_s2_dataunit . '"],' ;
  $json_output .= '"pm4_s2":["' . $pm4_s2_ugm3 . '","' . $pm4_s2_dataunit . '"],' ;
  $json_output .= '"pm10_s2":["' . $pm10_s2_ugm3 . '","' . $pm10_s2_dataunit . '"],' ;
  $json_output .= '"temp_s2":["' . $temp_s2 . '","' . $temp_s2_dataunit . '"],' ;
  $json_output .= '"humid_s12":["' . $humid_s2 . '","' . $humid_s2_dataunit . '"],' ;
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

?>
