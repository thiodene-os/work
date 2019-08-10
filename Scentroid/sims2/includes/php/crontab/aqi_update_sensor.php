<?php

require_once('/var/www/html/includes/php/crontab/common_cron.php') ;

// Connect to db
$dbc = db_connect_sims() ;
$dbc_local = db_connect_local() ;

// Go through all the equipments from Sims2.0 to get averages
$query = "SELECT equipement.id AS equipment_id, name
          FROM equipement"
          //. " WHERE equipement.id = '45'"
          . " ORDER BY equipement.id ASC" ; // Test with 45
$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
{
  $equipment_id = $row['equipment_id'] ;
  // Get the 3 JSON corresponding to 1/8/24Hours average
  $average1_url = 'http://api2.scentroid.com:8080/equipment/aqi?equipment=' . $equipment_id . '&hours=1' ;
  $average1_json = getSims2QueryResult($average1_url) ;
  $average1_obj = json_decode($average1_json) ;
  
  $average8_url = 'http://api2.scentroid.com:8080/equipment/aqi?equipment=' . $equipment_id . '&hours=8' ;
  $average8_json = getSims2QueryResult($average8_url) ;
  $average8_obj = json_decode($average8_json) ;
  
  $average24_url = 'http://api2.scentroid.com:8080/equipment/aqi?equipment=' . $equipment_id . '&hours=24' ;
  $average24_json = getSims2QueryResult($average24_url) ;
  $average24_obj = json_decode($average24_json) ;
  
  // Update the AQI values where applicable
  if ($average1_obj)
  {
    $sensor1_obj = $average1_obj->sensors ;
    // Get sensor ID and the average value and store
    foreach ($sensor1_obj as $value) 
    {
      $sensor_id = $value->sensor ;
      //$sensor_aqi = $value->aqi ;
      $avg_value = $value->avg_value ;
      
      // Now get the formula and the data unit from aqi_sensor_standard
      $query2 = "SELECT aqi_sensor_standard.id AS aqi_sensor_id, formula, data_unit
                FROM aqi_sensor_standard
                WHERE sensor_id = " . $sensor_id ;
      $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
      if (mysqli_num_rows($result2) != 0)
      {
        $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
        $aqi_id = $row2[0] ;
        $pollutant = $row2[1] ;
        $data_unit = $row2[2] ;
        
        // And then calculate the standard AQI for this Formula
        $standard1_aqi = calculatePollutantAQI($pollutant, $data_unit, $avg_value, 1) ;
        
        // If AQI value Update 
        if ($standard1_aqi)
        {
          if ($avg_value)
          {
            // Then Update the AQI
            // UPDATE in case the formula or name or data unit have changed
            $query3 = "UPDATE aqi_sensor_standard 
                       SET value='$standard1_aqi', hour1_avg='$avg_value', updated_dt=NOW()"
                      . " WHERE id = " . $aqi_id ;
            $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
          }
          else
          {
            // Then Update the AQI
            // UPDATE in case the formula or name or data unit have changed
            $query3 = "UPDATE aqi_sensor_standard 
                       SET value='$standard1_aqi', updated_dt=NOW()"
                      . " WHERE id = " . $aqi_id ;
            $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
          }
        }
        else
        { 
          if ($avg_value)
          {
            // Then Update the AQI
            // UPDATE in case the formula or name or data unit have changed
            $query3 = "UPDATE aqi_sensor_standard 
                       SET hour1_avg='$avg_value', updated_dt=NOW()"
                      . " WHERE id = " . $aqi_id ;
            $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
          }
          else
          {
            // UPDATE in case the formula or name or data unit have changed
            $query3 = "UPDATE aqi_sensor_standard 
                       SET updated_dt=NOW()"
                      . " WHERE id = " . $aqi_id ;
            $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
          }
        }
        
      }
    }
  }
  
  // Update the AQI values where applicable
  if ($average8_obj)
  {
    $sensor8_obj = $average8_obj->sensors ;
    // Get sensor ID and the average value and store
    foreach ($sensor8_obj as $value) 
    {
      $sensor_id = $value->sensor ;
      //$sensor_aqi = $value->aqi ;
      $avg_value = $value->avg_value ;
      
      // Now get the formula and the data unit from aqi_sensor_standard
      $query2 = "SELECT aqi_sensor_standard.id AS aqi_sensor_id, formula, data_unit
                FROM aqi_sensor_standard
                WHERE sensor_id = " . $sensor_id ;
      $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
      if (mysqli_num_rows($result2) != 0)
      {
        $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
        $aqi_id = $row2[0] ;
        $pollutant = $row2[1] ;
        $data_unit = $row2[2] ;
        
        // And then calculate the standard AQI for this Formula
        $standard8_aqi = calculatePollutantAQI($pollutant, $data_unit, $avg_value, 8) ;
        
        if ($standard8_aqi && $standard8_aqi > $standard1_aqi)
        {
          if ($avg_value)
          {
            // Then Update the AQI
            // UPDATE in case the formula or name or data unit have changed
            $query3 = "UPDATE aqi_sensor_standard 
                       SET value='$standard8_aqi', hour8_avg='$avg_value', updated_dt=NOW()"
                      . " WHERE id = " . $aqi_id ;
            $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
          }
          else
          {
            // Then Update the AQI
            // UPDATE in case the formula or name or data unit have changed
            $query3 = "UPDATE aqi_sensor_standard 
                       SET value='$standard8_aqi', updated_dt=NOW()"
                      . " WHERE id = " . $aqi_id ;
            $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
          }
        }
        else
        {
          if ($avg_value)
          {
            // Then Update the AQI
            // UPDATE in case the formula or name or data unit have changed
            $query3 = "UPDATE aqi_sensor_standard 
                       SET hour8_avg='$avg_value', updated_dt=NOW()"
                      . " WHERE id = " . $aqi_id ;
            $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
          }
          else
          {
            // UPDATE in case the formula or name or data unit have changed
            $query3 = "UPDATE aqi_sensor_standard 
                       SET updated_dt=NOW()"
                      . " WHERE id = " . $aqi_id ;
            $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
          }
        }
        
      }
    }
  }
  
  // Update the AQI values where applicable
  if ($average24_obj)
  {
    $sensor24_obj = $average24_obj->sensors ;
    // Get sensor ID and the average value and store
    foreach ($sensor24_obj as $value) 
    {
      $sensor_id = $value->sensor ;
      //$sensor_aqi = $value->aqi ;
      $avg_value = $value->avg_value ;
      
      // Now get the formula and the data unit from aqi_sensor_standard
      $query2 = "SELECT aqi_sensor_standard.id AS aqi_sensor_id, formula, data_unit
                FROM aqi_sensor_standard
                WHERE sensor_id = " . $sensor_id ;
      $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
      if (mysqli_num_rows($result2) != 0)
      {
        $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
        $aqi_id = $row2[0] ;
        $pollutant = $row2[1] ;
        $data_unit = $row2[2] ;
        
        // And then calculate the standard AQI for this Formula
        $standard24_aqi = calculatePollutantAQI($pollutant, $data_unit, $avg_value, 24) ;
        
        if ($standard24_aqi && $standard24_aqi > $standard8_aqi && $standard24_aqi > $standard1_aqi)
        {
          if ($avg_value)
          {
            // Then Update the AQI
            // UPDATE in case the formula or name or data unit have changed
            $query3 = "UPDATE aqi_sensor_standard 
                       SET value='$standard24_aqi', hour24_avg='$avg_value', updated_dt=NOW()"
                      . " WHERE id = " . $aqi_id ;
            $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
          }
          else
          {
            // Then Update the AQI
            // UPDATE in case the formula or name or data unit have changed
            $query3 = "UPDATE aqi_sensor_standard 
                       SET value='$standard24_aqi', updated_dt=NOW()"
                      . " WHERE id = " . $aqi_id ;
            $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
          }
        }
        else
        {
          if ($avg_value)
          {
            // Then Update the AQI
            // UPDATE in case the formula or name or data unit have changed
            $query3 = "UPDATE aqi_sensor_standard 
                       SET hour24_avg='$avg_value', updated_dt=NOW()"
                      . " WHERE id = " . $aqi_id ;
            $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
          }
          else
          {
            // UPDATE in case the formula or name or data unit have changed
            $query3 = "UPDATE aqi_sensor_standard 
                       SET updated_dt=NOW()"
                      . " WHERE id = " . $aqi_id ;
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

echo "AQI Update:OK; \n" ;

?>