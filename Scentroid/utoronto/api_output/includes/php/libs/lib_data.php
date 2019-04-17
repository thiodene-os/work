<?php

// Notification libraires for Alarms, Health and all types of Notifications
// SQL + Email or SMS handling and sending

// Builds and returns the JSON output for the latest recorded data
function buildJSONDataOutput($timestamp=false)
{

  // Connect to db
  $dbc = db_connect_ut() ;

  $json_output = '' ;

  // Move the very last picture over to /var/www/images.
  //rename

  // Go through the last SQL record and populate the JSON
  $query = "SELECT small_data.id AS smalldata_id, lat, lon, wind_speed, wind_direction
        , vehicle_speed, vehicle_direction, value_co_mv, value_co_ppm, value_no2_mv, value_no2_ppm, value_o3_mv, value_o3_ppm
        , value_pm25_mv, value_pm25_ugm3, value_pm10_mv, value_pm10_ugm3, timestamp
        FROM small_data"
        . " ORDER BY timestamp DESC LIMIT 1" ;
  $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
  $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
  $id = $row[0] ;
  $lat = $row[1] ; $lon = $row[2] ;

  $wind_speed = $row[3] ; $wind_direction = $row[4] ;
  $vehicle_speed = $row[5] ; $vehicle_direction = $row[6] ;

  $co_mv = $row[7] ; $co_ppm = $row[8] ; $co_dataunit = 'PPM' ;
  $no2_mv = $row[9] ; $no2_ppm = $row[10] ; $no2_dataunit = 'PPM' ;
  $o3_mv = $row[11] ; $o3_ppm = $row[12] ;  $o3_dataunit = 'PPM' ;
  $pm25_mv = $row[13] ; $pm25_ugm3 = $row[14] ;  $pm25_dataunit = 'ug/m3' ;
  $pm10_mv = $row[15] ; $pm10_ugm3 = $row[16] ; $pm10_dataunit = 'ug/m3' ;

  $timestamp = $row[17] ;

  $cam_path = 'http://localhost/images/cam360.jpg' ;
  $lidar_path = 'http://localhost/images/lidar.jpg' ;

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

  // Gas Sensors
  $json_output .= '"co":["' . $co_mv . '","mV","' . $co_ppm . '","' . $co_dataunit . '"],' ;
  $json_output .= '"no2":["' . $no2_mv . '","mV","' . $no2_ppm . '","' . $no2_dataunit . '"],' ;
  $json_output .= '"o3":["' . $o3_mv . '","mV","' . $o3_ppm . '","' . $o3_dataunit . '"],' ;
  $json_output .= '"pm2.5":["' . $pm25_mv . '","mV","' . $pm25_ugm3 . '","' . $pm25_dataunit . '"],' ;
  $json_output .= '"pm10":["' . $pm10_mv . '","mV","' . $pm10_ugm3 . '","' . $pm10_dataunit . '"],' ;
  // Snapshot image 360
  $json_output .= '"cam360":"' . $cam_path . '",' ;
  $json_output .= '"lidar":"' . $lidar_path . '"' ;

  $json_output .= '}' ;

  // Close db
  db_close($dbc) ;

  return $json_output ;

}

?>

