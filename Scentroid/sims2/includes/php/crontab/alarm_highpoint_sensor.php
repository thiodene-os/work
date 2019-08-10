<?php

require_once('/var/www/html/includes/php/crontab/common_cron.php') ;

// Connect to db
$dbc = db_connect_sims() ;
$dbc_local = db_connect_local() ;

// First Get a list of all the sensor (types) registered with alarm value
$query = "SELECT alarmcheckpoint_sensor.id AS alarmcheckpoint_id, user_id
          , sensor_id, high_point, highcheckedsample_id
          FROM alarmcheckpoint_sensor
          WHERE enabled = 'y'" ; // LIMIT 1 for testing
$result = mysqli_query($dbc_local, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
{
  $alarmcheckpoint_id = $row['alarmcheckpoint_id'] ;
  $user_id = $row['user_id'] ;
  $sensor_id = $row['sensor_id'] ;
  $high_point = $row['high_point'] ;
  echo 'High Point: ' . $high_point ;
  $highcheckedsample_id = $row['highcheckedsample_id'] ;
  
  // If high point has been defined check any occurences in the sample values
  if ($high_point)
  {
    // Select the sample value for which the High Point has been crossed
    $query2 = "SELECT sample.id AS sample_id, value, sample.createdat
              , sensor.name AS sensor_name, equipement.id AS equipment_id
              , equipement.name AS equipment_name, sensor.dataunit
              FROM sample
              INNER JOIN sensor ON sensor.id = sample.sensor
              INNER JOIN equipement ON sensor.equipement = equipement.id
              WHERE sample.sensor = " . $sensor_id
              . " AND value >= '" . $high_point . "'"
              . " AND sample.id > " . $highcheckedsample_id
              . " ORDER BY sample.sampledat ASC LIMIT 1" ;
    $result2 = mysqli_query($dbc, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc)) ;
    if (mysqli_num_rows($result2) != 0)
    {
      echo 'Value! Sensor: ' . $sensor_id ;
      // If any value Add it to the Alarm and save that check point
      $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
      $new_highcheckedsample_id = $row2[0] ;
      $new_value = $row2[1] ;
      $createdat = date("l jS F Y", $row2[2]) ;
      $sensor_name = $row2[3] ;
      $equipment_id = $row2[4] ;
      $equipment_name = $row2[5] ;
      $dataunit = $row2[6] ;
      
      $message = "The value of " . $new_value . " (" . $dataunit . ") for sensor " . $sensor_name . " in " . $equipment_name 
      . " crossed the maximum threshold of " . floatval($high_point) . " (" . $dataunit . ") on " . $createdat . "." ;
      
      // Insert the new Alarm message
      $query3 = "INSERT INTO alarm_sensor (message, user_id, sensor_id, equipment_id, created_dt) VALUES ('" 
                . $message . "', " . $user_id . ", " . $sensor_id . ", '" . $equipment_id . "',NOW()); " ;
      $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
      
      // Update the alarm checkpoint
      $query4 = "UPDATE alarmcheckpoint_sensor 
                 SET highcheckedsample_id = " . $new_highcheckedsample_id
                . " WHERE id = " . $alarmcheckpoint_id ;
      $result4 = mysqli_query($dbc_local, $query4) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
      
    }
    else
      echo 'No Value! Sensor: ' . $sensor_id ;
  }
  
}

// Close db
db_close($dbc) ;
db_close($dbc_local) ;

echo "Add Alarm OK; \n" ;

?>