<?php

require_once('/var/www/html/includes/php/crontab/common_cron.php') ;

// Connect to db
$dbc = db_connect_sims() ;
$dbc_local = db_connect_local() ;

// First Get a list of all the sensor (types) registered with alarm value
$query = "SELECT settings.id AS setting_id, user_id, settings_json, notification_dt
          FROM settings" ; // LIMIT 1 for testing
$result = mysqli_query($dbc_local, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
{
  $setting_id = $row['setting_id'] ;
  $user_id = $row['user_id'] ;
  $settings_json = $row['settings_json'] ;
  $notification_dt = $row['notification_dt'] ;
  
  // If JSON has been saved get the content (for Alarm)
  if ($settings_json)
  {
    $settings_obj = json_decode($settings_json) ;
    $alarm_sensor_obj = $settings_obj->alarm_sensor ;
  }
  else
    $alarm_sensor_obj = false ;
  
  if ($alarm_sensor_obj)
  {
    // From the user_id get all the sensor for this user/company
    foreach ($alarm_sensor_obj as $alarm_sensor)
    {
      // Be sure that the alarm has threshold (low or high) values
      // First get all the sensor ID that has the alarm sensor type
      $sensor_name = $alarm_sensor->sensor_id ;
      
      $extremum = false ;
      //if low point threshold is enabled
      if (strlen($alarm_sensor->low_high_value[0]) > 0)
      {
        $extremum = 'l' ;
        $low_point = "'" . $alarm_sensor->low_high_value[0] . "'" ;
      }
      else
        $low_point = 'NULL' ;
      //if high point threshold is enabled
      if (strlen($alarm_sensor->low_high_value[1]) > 0)
      {
        $extremum = 'h' ;
        $high_point = "'" . $alarm_sensor->low_high_value[1] . "'" ;
      }
      else
        $high_point = 'NULL' ;
      
      // If Low and High points have been set for that sensor type go through all sensors
      if ($extremum)
      {
        // Check if enabled Alarm
        if ($alarm_sensor->enabled == 'yes')
          $enabled = 'y' ;
        else
          $enabled = '' ;
      }
      else
        $enabled = '' ;
      
      // Get all the Sensors from that company/user
      $query4 = "SELECT sensor.id AS sensor_id, sensor.name AS sensor_name
                , company.id AS company_id, equipement.id AS equipment_id
                FROM sensor 
                INNER JOIN equipement ON sensor.equipement = equipement.id
                INNER JOIN company ON equipement.company = company.id
                INNER JOIN user ON user.company = company.id
                WHERE user.id = " . $user_id
                . " ORDER BY sensor.id ASC" ; // LIMIT 1 for testing
      $result4 = mysqli_query($dbc, $query4) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysqli_error($dbc)) ;
      while ($row4 = mysqli_fetch_array($result4, MYSQLI_ASSOC))
      {
        
        $sensor_id = $row4['sensor_id'] ;
        $company_id = $row4['company_id'] ;
        $equipment_id = $row4['equipment_id'] ;
        
        $sensor_name = $row4['sensor_name'] ;
        $sensor_formula = utf8_encode($sensor_name) ;
        $sensor_formula0 = preg_replace('/\s+/', ' ',$sensor_formula) ;
        $sensor_formula = shortenSensorName($sensor_formula0, true) ;
        if (strlen($sensor_formula) == 0)
          $sensor_formula = $sensor_formula0 ;
        
        if ($sensor_id == $alarm_sensor->sensor_id)
        {
          $data_unit = $alarm_sensor->data_unit ;
          // Check if records of Alarm Checkpoint exists in the local DB
          // If not create them
          $query2 = "SELECT alarmcheckpoint_sensor.id AS alarmcheckpoint_id
                FROM alarmcheckpoint_sensor
                WHERE user_id = " . $user_id
                . " AND sensor_id = " . $sensor_id ;
          $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
          if (mysqli_num_rows($result2) == 0)
          {
            // If no records INSERT new value
            $query3 = "INSERT INTO alarmcheckpoint_sensor (user_id, sensor_id, formula, data_unit, low_point, high_point, enabled, high_notification_dt, low_notification_dt, created_dt) VALUES (" 
                      . $user_id . ", " . $sensor_id . ", '" . $sensor_formula . "','" . $data_unit . "', " . $low_point 
                      . ", " . $high_point . ", '" . $enabled . "', '" . $notification_dt . "', '" . $notification_dt . "',NOW()); " ;
            $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
            
          }
          else
          {
            // Get the ID for update
            $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
            $alarmcheckpoint_id = $row2[0] ;
            // UPDATE even if the record is already there (because of enable function)
            $query3 = "UPDATE alarmcheckpoint_sensor 
                       SET low_point=" . $low_point . ", high_point=" . $high_point 
                       . ",low_notification_dt='" . $notification_dt . "',high_notification_dt='" . $notification_dt
                       . "', enabled='" . $enabled . "'"
                       . " WHERE id = " . $alarmcheckpoint_id ;
            $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
          }
        }
      }
    }
    
  }
  
}

// Close db
db_close($dbc) ;
db_close($dbc_local) ;

echo "Register Alarm OK; \n" ;

?>