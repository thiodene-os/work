<?php

require_once('/var/www/html/includes/php/crontab/common_cron.php') ;

// Connect to db
$dbc = db_connect_sims() ;
$dbc_local = db_connect_local() ;

// Select the equipment with the oldest update
$query = "SELECT lastvalue_equipment.id AS lastvalue_id, equipment_id, company_id 
           FROM lastvalue_equipment"
       . " ORDER BY updated_dt ASC LIMIT 1" ;
$result = mysqli_query($dbc_local, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
$row = mysqli_fetch_array($result, MYSQLI_NUM) ;

$update_value_id = $row[0] ;
$equipment = $row[1] ;
$company = $row[2] ;

// First get the last sample values per equipment (LAT, LON, SAMPLEDAT)
$query = "SELECT sample.id AS sample_id, sample.sampledat, lat, lon
      FROM sample
      WHERE equipement = " . $equipment 
      . " ORDER BY sampledat DESC LIMIT 1";
$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
if (mysqli_num_rows($result) != 0)
{
  $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
  
  $sampledat = $row[1] ;
  $lat = $row[2] ;
  $lon = $row[3] ;
  
  // Now get the last value per sensor and transform it into JSON
  // Get the list of sensors for this equipment
  $query3 = "SELECT sensor.id AS sensor_id, name, dataunit
            FROM sensor
            WHERE equipement = " . $equipment 
            . " ORDER BY sensor.id ASC" ;
  $result3 = mysqli_query($dbc, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc));
  if (mysqli_num_rows($result3) != 0)
  {
    $value_per_sensor = '[' ;
    while ($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC))
    {
      if (strlen($value_per_sensor) > 1)
        $value_per_sensor .= ',' ;
      
      $sensor_dataunit = $row3['dataunit'] ;
      $sensor_name =  $row3['name'] ;
      $sensor_id =  $row3['sensor_id'] ;
      // Get the very last sample value for this sensor
      $query5 = "SELECT sample.id AS sample_id, value
                FROM sample
                WHERE equipement = " . $equipment
                . " AND sensor = " . $sensor_id
                . " ORDER BY sampledat DESC LIMIT 1" ;
      $result5 = mysqli_query($dbc, $query5) or trigger_error("Query: $query5\n<br>MySQL Error: " . mysqli_error($dbc));
      $row5 = mysqli_fetch_array($result5, MYSQLI_NUM) ;
      $value  = $row5[1] ;
      
      if (!is_numeric($value))
        $value = 0;
      
      $value_per_sensor .= '{"name":"' . $sensor_name . '","id":"' . $sensor_id . '","value":"' .  $value . '","unit":"' .  $sensor_dataunit . '"}' ;
      
    }
    
    $value_per_sensor .= ']' ;
    
  }
  else
  {
    $value_per_sensor = '' ;
  }
  
  // Update the date priority on the DB
  $query4 = "UPDATE lastvalue_equipment 
             SET lat='$lat', lon='$lon', value_per_sensor='$value_per_sensor', sampledat='$sampledat', updated_dt=NOW()"
            . " WHERE id = " . $update_value_id ;
  $result4 = mysqli_query($dbc_local, $query4) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
}
else
{
  // At least update the date to go to next one...
  $query3 = "UPDATE lastvalue_equipment 
             SET updated_dt=NOW()"
            . " WHERE id = " . $update_value_id ;
  $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
}
// Close db
db_close($dbc) ;
db_close($dbc_local) ;

echo "Update Latest Values:OK; \n" ;

?>