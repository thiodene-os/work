<?php

require_once('/var/www/html/includes/php/crontab/common_cron.php') ;

// Connect to db
$dbc = db_connect_sims() ;
$dbc_local = db_connect_local() ;

// Get the list of sensors saved into the user's settings
$query = "SELECT settings.id AS setting_id, user_id, settings_json
      FROM settings
      ORDER BY user_id ASC" ;
$result = mysqli_query($dbc_local, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
if (mysqli_num_rows($result) != 0)
{
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
  {
    $setting_id = $row['setting_id'] ;
    $user_id = $row['user_id'] ;
    $settings_json = $row['settings_json'] ;
    
    $settings_obj = json_decode($settings_json) ;
    $aqi_obj = $settings_obj->aqi ;
    
    foreach ($aqi_obj as $value) 
    {
      $sensor_id = $value->sensor_id ;
      $equipment_id = $value->equipment_id ;
      $parameters_arr = $value->parameters ;
      $data_unit = $parameters_arr[1] ;
      
      // Check if the sensor is not already saved, if not register the sensor
      // First Get a list of all the sensors / users
      $query2 = "SELECT aqi_sensor_user.id AS aqi_id
                FROM aqi_sensor_user
                WHERE user_id = " . $user_id
                . " AND sensor_id = " . $sensor_id ; // LIMIT 1 for testing
      $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
      if (mysqli_num_rows($result2) == 0)
      {
        // If not INSERTed yet Then INSERT the sensor
        $query3 = "INSERT INTO aqi_sensor_user (user_id, sensor_id, data_unit, equipment_id) VALUES (" 
                  . $user_id . ", '" . $sensor_id . "', '" . $data_unit . "', " . $equipment_id . "); " ;
        $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
        
      }
      
    }
  }
  
}

// Close db
db_close($dbc) ;
db_close($dbc_local) ;

echo "AQI User Register OK; \n" ;

?>