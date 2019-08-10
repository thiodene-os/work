<?php

require_once('/var/www/html/includes/php/crontab/common_cron.php') ;

// Connect to db
$dbc = db_connect_sims() ;
$dbc_local = db_connect_local() ;

// First Get a list of all the sensors / equipments
$query = "SELECT sensor.id AS sensor_id, equipement.id AS equipment_id, sensor.name AS sensor_name
          , dataunit
          FROM sensor
          INNER JOIN equipement ON equipement.id = sensor.equipement"
          . " ORDER BY sensor.equipement, sensor.id ASC" ; // LIMIT 1 for testing
$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc)) ;
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
{
  $sensor_id = $row['sensor_id'] ;
  $equipment_id = $row['equipment_id'] ;
  // sensor has to be a chemical -> ppm/ppb/ug/m3
  $sensor_name = $row['sensor_name'] ;
  
  // Avoid special characters  // Beware of the special characters
  $sensor_formula = $row['sensor_name'] ;
  $sensor_formula = utf8_encode($sensor_formula);
  $sensor_formula0 = preg_replace('/\s+/', ' ',$sensor_formula);
  $sensor_formula = shortenSensorName($sensor_formula0, true) ;
  if (strlen($sensor_formula) == 0)
    $sensor_formula = $sensor_formula0 ;
  
  $data_unit = strtolower(trim($row['dataunit'])) ;
  if (strtolower($data_unit) == 'ppm' || strtolower($data_unit) == 'ppb' || strtolower($data_unit) == 'ug/m3')
  {
    echo $sensor_id . ':' . $sensor_formula . ' (' . $data_unit . '); <br />' ;
    // Check if records of standard AQI exists in the local DB
    // If not create them
    $query2 = "SELECT aqi_sensor_standard.id AS aqi_id
          FROM aqi_sensor_standard
          WHERE sensor_id = " . $sensor_id ;
    $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
    if (mysqli_num_rows($result2) == 0)
    {
      // If no records INSERT new value
      $query3 = "INSERT INTO aqi_sensor_standard (sensor_id, formula, data_unit, equipment_id) VALUES (" 
                . $sensor_id . ", '" . $sensor_formula . "', '" . $data_unit . "', " . $equipment_id . "); " ;
      $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
      
    }
    else
    {
      $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
      $aqi_id = $row2[0] ;
      // UPDATE in case the formula or name or data unit have changed
      $query3 = "UPDATE aqi_sensor_standard 
                 SET formula='$sensor_formula', data_unit='$data_unit'"
                . " WHERE id = " . $aqi_id ;
      $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
    }
  
  }
  
}

// Close db
db_close($dbc) ;
db_close($dbc_local) ;

echo "AQI Register OK; \n" ;

?>