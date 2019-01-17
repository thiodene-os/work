<?php

// Main libraries for Chart features

// Builds and returns the Top Input Fields (Dates, Sensors Update buttons etc...)
function buildTopInputFields($equipment, $date_range, $begin_date, $end_date)
{
  // Build the top input bar
  $final_top_input = "" ;
  
  // Connect to db
  $dbc = db_connect_sims() ;
  
  // If both Begin and End date submitted don't display Last: '1 Week' value
  if (strlen($begin_date) > 0 && strlen($end_date) > 0)
    $dates_submitted = true ;
  else
    $dates_submitted = false ;
  
  // Get all the Scentinals and the respective company name and build the top input select field
	$query = "SELECT equipement.id AS equip_id, equipement.name AS equip_name
            , equipement.company AS company_id
            , company.name AS company_name
            FROM equipement 
            INNER JOIN company ON equipement.company = company.id 
            ORDER BY equipement.id DESC" ;
	$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc)) ;
	if (@mysqli_num_rows($result) != 0) 
  {
    $final_top_input .= "<form method=\"get\" action=\"index.php\">
                  <label>Scentinal:</label>
                  <select class=\"input_select\" name=\"equipment\">" ;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
    {
      $equipement_id = $row['equip_id'] ;
      $equipement_name = $row['equip_name'] ;
      $company_id = $row['company_id'] ;
      $company_name = $row['company_name'] ;
      
      // Build the option fields
      if ($equipement_id == $equipment)
        $final_top_input .= "<option value=\"" . $equipement_id . "\" selected>" . $equipement_name . " -:- " . $company_name . "</option>";
      else
        $final_top_input .= "<option value=\"" . $equipement_id . "\">" . $equipement_name . " -:- " . $company_name .  "</option>";

		}
    $final_top_input .= "</select>" ;
	}
  
  $date_interval = buildIntervalDateSelectInput($dates_submitted, $date_range) ;
  
  $final_top_input .= "
                  &emsp;<label>Last:</label> $date_interval
                  &emsp; <label>Begin Date:</label><input type=\"text\" id=\"begin_date\" class=\"input_date\" name=\"begin_date\" value=\"$begin_date\" />  
                  <label>End Date:</label><input type=\"text\" id=\"end_date\" class=\"input_date\" name=\"end_date\" value=\"$end_date\" />
                  <button class=\"button_update\">Update</button></form>" ;
                  
  // Close db
  db_close($dbc) ;
  
  return $final_top_input ;
} // buildTopInputFields 

// Provides the last data for Met sensors as an array
// Used for Google Map extra window
function getEquipmentLastMetData($equipment)
{
  
  // Connect to db
  $dbc_local = db_connect_local() ;
  
  // Get the last position of the scentinel
  $query = "SELECT lastvalue_equipment.id AS lastvalue_id, value_per_sensor
        FROM lastvalue_equipment
        WHERE equipment_id = " . $equipment ;
  $result = mysqli_query($dbc_local, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc_local));
  $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
  $sensor_json = $row[1] ;
  
  $final_last_met = '' ;
  // Beware of the special characters
  $sensor_json = utf8_encode($sensor_json);
  $sensor_json = preg_replace('/\s+/', ' ',$sensor_json);
  // Decode JSON
  $sensor_obj = json_decode($sensor_json) ;
  
  // Get the last updated value and construct the other values based on it (for now!)
  //foreach ($obj as $key => $value)
  foreach ($sensor_obj as $sensor)
  {
    //echo "Key:" . $key . ", Value:" . strtolower($sensor->name) . " <br />" ;
    $final_last_met .= "Name:" . shortenSensorName($sensor->name) . " Value:" . strtolower($sensor->value) . " <br />" ;
  }
  
  // Close db
  db_close($dbc_local) ;
  
  return $final_last_met ;
} // getEquipmentLastMetData 

// Calculates the averages for all the sensors of an equipment
// Return result as an array
function getEquipmentDataAverages($equipment, $met=false) {
  
  // Connect to db
  $dbc_local = db_connect_local() ;
  
  $final_sensor_array = array();
  $final_sensor_iter = 0 ;
  
  $final_sensor_array[$final_sensor_iter][0] = 'Sensor' ;
  $final_sensor_array[$final_sensor_iter][1] = 'Last' ;
  $final_sensor_array[$final_sensor_iter][2] = '1 Hour' ;
  $final_sensor_array[$final_sensor_iter][3] = '8 Hours' ;
  $final_sensor_array[$final_sensor_iter][4] = '24 Hours' ;
  $final_sensor_array[$final_sensor_iter][5] = 'AQI' ;
  $final_sensor_array[$final_sensor_iter][6] = 'Data Unit' ;
  $final_sensor_iter++ ;
  
  // Calculate the averages for last [updated, 1 hour, 8 hour, 24 hour] + AQI
  // Get the values of each sensors of that equipment
  $query = "SELECT lastvalue_equipment.id AS lastvalue_id, value_per_sensor
        FROM lastvalue_equipment
        WHERE equipment_id = " . $equipment ;
  $result = mysqli_query($dbc_local, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc_local));
  $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
  $sensor_json = $row[1] ;
  // Beware of the special characters
  $sensor_json = utf8_encode($sensor_json);
  $sensor_json = preg_replace('/\s+/', ' ',$sensor_json);
  // Decode JSON
  
  $sensor_obj = json_decode($sensor_json) ;
  
  // Get the last updated value and construct the other values based on it (for now!)
  //foreach ($obj as $key => $value)
  foreach ($sensor_obj as $value)
  {
    //echo "Key:" . $key . ", Value:" . strtolower($value->name) . " <br />" ;
    //echo "Value:" . strtolower($value->name) . " <br />" ;
    // Avoid Met data or normal Gas data
    if (!$met)
    {
      if (strtolower($value->unit) == 'ppm' || strtolower($value->unit) == 'ppb' || strtolower($value->unit) == 'ou'
        || strtolower($value->unit) == 'ug/m3' || strtolower($value->unit) == 'ou/m3')
      {
        $final_sensor_array[$final_sensor_iter][0] = shortenSensorName($value->name) ;
        $final_sensor_array[$final_sensor_iter][1] = $value->value ;
        $final_sensor_array[$final_sensor_iter][2] = $value->value ;
        $final_sensor_array[$final_sensor_iter][3] = $value->value ;
        $final_sensor_array[$final_sensor_iter][4] = $value->value ;
        $final_sensor_array[$final_sensor_iter][5] = rand(1,1000)/10 ;
        $final_sensor_array[$final_sensor_iter][6] = $value->unit ;
        $final_sensor_iter++ ;
      }
    }
    else
    {
      if (strtolower($value->unit) != 'ppm' && strtolower($value->unit) != 'ppb' && strtolower($value->unit) != 'ou'
        && strtolower($value->unit) != 'ug/m3' && strtolower($value->unit) != 'ou/m3')
      {
        $final_sensor_array[$final_sensor_iter][0] = shortenSensorName($value->name) ;
        $final_sensor_array[$final_sensor_iter][1] = $value->value ;
        $final_sensor_array[$final_sensor_iter][2] = $value->value ;
        $final_sensor_array[$final_sensor_iter][3] = $value->value ;
        $final_sensor_array[$final_sensor_iter][4] = $value->value ;
        $final_sensor_array[$final_sensor_iter][5] = rand(1,1000)/10 ;
        $final_sensor_array[$final_sensor_iter][6] = $value->unit ;
        $final_sensor_iter++ ;
      }
    }
  
  }
  
  
  // Close db
  db_close($dbc_local) ;
  
  return $final_sensor_array ;
} // getEquipmentDataAverages 

// This function formats Met data for Google Map top banner
function getSensorMetDataGoogleMap($equipment)
{
  $final_met_array = array();
  $final_met_iter = 0 ;
  
  $final_met_array[$final_met_iter][0] = 'Sensor' ;
  $final_met_array[$final_met_iter][1] = 'Value' ;
  $final_met_array[$final_met_iter][2] = 'Data Unit' ;
  $final_met_iter++ ;
  // Temperatures
  $final_met_array[$final_met_iter][0] = 'Internal Temperature' ;
  $final_met_array[$final_met_iter][1] = rand(1,350)/10 ;
  $final_met_array[$final_met_iter][2] = 'C' ;
  $final_met_iter++ ;
  
  $final_met_array[$final_met_iter][0] = 'External Temperature' ;
  $final_met_array[$final_met_iter][1] = rand(1,350)/10 ;
  $final_met_array[$final_met_iter][2] = 'C' ;
  $final_met_iter++ ;
  // Humidity
  $final_met_array[$final_met_iter][0] = 'Internal Humidity' ;
  $final_met_array[$final_met_iter][1] = rand(1,100)/100 ;
  $final_met_array[$final_met_iter][2] = '%' ;
  $final_met_iter++ ;
  
  $final_met_array[$final_met_iter][0] = 'External Humidity' ;
  $final_met_array[$final_met_iter][1] = rand(1,100)/100 ;
  $final_met_array[$final_met_iter][2] = '%' ;
  $final_met_iter++ ;
  
  $final_met_array[$final_met_iter][0] = 'Daily Rain' ;
  $final_met_array[$final_met_iter][1] = rand(1,1000)/100 ;
  $final_met_array[$final_met_iter][2] = 'mm' ;
  $final_met_iter++ ;
  // Pressure
  $final_met_array[$final_met_iter][0] = 'Barometric Pressure' ;
  $final_met_array[$final_met_iter][1] = rand(1,100)/100 ;
  $final_met_array[$final_met_iter][2] = 'kPA' ;
  $final_met_iter++ ;
  // UV Data
  $final_met_array[$final_met_iter][0] = 'UV' ;
  $final_met_array[$final_met_iter][1] = rand(1,1500)/100 ;
  $final_met_array[$final_met_iter][2] = 'UV Index' ;
  $final_met_iter++ ;
  
  $final_met_array[$final_met_iter][0] = 'Solar Radiation' ;
  $final_met_array[$final_met_iter][1] = rand(1,10000)/10 ;
  $final_met_array[$final_met_iter][2] = 'W/m2' ;
  $final_met_iter++ ;
  
  // Wind Data
  $final_met_array[$final_met_iter][0] = 'Wind Direction' ;
  $final_met_array[$final_met_iter][1] = rand(0,360) ;
  $final_met_array[$final_met_iter][2] = '°' ;
  $final_met_iter++ ;
  
  $final_met_array[$final_met_iter][0] = 'Wind Speed' ;
  $final_met_array[$final_met_iter][1] = rand(0,100)/10 ;
  $final_met_array[$final_met_iter][2] = 'm/s' ;
  $final_met_iter++ ;
  
  // AQI Last value
  $final_met_array[$final_met_iter][0] = 'AQI' ;
  $final_met_array[$final_met_iter][1] = rand(1,1000)/10;
  $final_met_array[$final_met_iter][2] = '' ;
  
  return $final_met_array ;
} // getSensorMetDataGoogleMap

// Builds and returns Charts for Met or Gas sensors Data
function displaySensorChart($chart_number, $met =false)
{
  if ($met)
  {
    // Add the chartContainer DIV
    $final_sensor_chart = "  <div id=\"chartContainer" . $chart_number . "\" style=\"height: 400px; width: 100%; float:right;\"></div>" ;
  }
  else
  {
    // Add the chartContainer DIV
    $final_sensor_chart = "  <div id=\"chartContainer" . $chart_number . "\" style=\"height: 400px; width: 100%; background-color: transparent;\"></div>" ;
  }

  return $final_sensor_chart ;
} // displaySensorChart

// Builds and returns Charts for all sensors Data
function displayAllSensorsChart($chart_number)
{
  // Add the chartContainer DIV
  $final_sensor_chart = "  <div id=\"chartContainer" . $chart_number . "\" style=\"height: 800px; width: 100%; background-color: transparent;\"></div>" ;

  return $final_sensor_chart ;
} // displayAllSensorsChart

// Builds and returns controlling table for Met or Gas sensors
function buildSensorTable($equipment, $chart_number, $met=false)
{

  // Connect to db
  $dbc = db_connect_sims() ;
  $dbc_local = db_connect_local() ;
  
  $final_sensor_table = '' ;
  $final_sensor_array = array() ;
  $final_sensor_iter = 0 ;
  
  // Add the condition for Met data
  if ($met)
  {
    $sign = 'NOT' ;
    $table_type = 'met_data' ;
  }
  else
  {
    $sign = '' ;
    $table_type = 'sensor_data' ;
  }

  // Get all the Met sensor (Not PPM or PPB for now) for the equipement 86
  $plot_number = 0 ;
	$query = "SELECT sensor.id AS sensor_id, sensor.name AS sensor_name
            , sensor.dataunit AS sensor_dataunit
            FROM sensor
            WHERE equipement = " . $equipment 
              . " AND dataunit ". $sign . " IN ('PPM','PPB','ug/m3','ou','ou/m3')"
              . " AND sensor.id IN (SELECT sensor FROM sample)"
            . " ORDER BY sensor.id ASC" ;
	$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
	if (@mysqli_num_rows($result) != 0) 
  {
    // Prepare Array for Ademir
    $final_sensor_array[$final_sensor_iter][0] = 'Parameter' ;
    $final_sensor_array[$final_sensor_iter][1] = 'Value' ;
    $final_sensor_array[$final_sensor_iter][2] = 'Data Unit' ;
    $final_sensor_iter++ ;
    
    // Add the frozen table to be replaced asap
    $final_sensor_table .= "
    <table class=\"scroll " . $table_type . "\">
    <thead>
    <tr>
    <th>Parameter</th>
    <th>Value</th>
    <th>Data Unit</th>
    <th>&nbsp;</th>
    </tr>
    </thead>" ;
    
    $final_sensor_table .= "<tbody>" ;
    
    $naxis = 0 ;
    $nsensor = 0 ;
    $sensor_arr = array() ;
    $axisy = '' ;
    $dataunit_arr = array() ;
    $dataunit_str = ' ' ;
    list ($naxis_full, $naxis_half) = calcNumberOfYAxesCanvasJS($equipment) ;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
    {
      $sensor_id = $row['sensor_id'] ;
      $sensor_name = shortenSensorName($row['sensor_name']) ;
      $sensor_dataunit = $row['sensor_dataunit'] ;
      if (strlen($sensor_dataunit) == 0)
        $sensor_dataunit = 'uu' ;
      
      // register the dataunit
      if (substr_count($dataunit_str, $sensor_dataunit) == 0)
      {
        $dataunit_arr[$naxis] = $sensor_dataunit ;
        $sensor_arr[$sensor_name] = array_search($sensor_dataunit, $dataunit_arr) ;
        $dataunit_str .= $sensor_dataunit ;
        
        if ($naxis_half > 0)
        {
          // If more than 4 build scales on the other side Y2
          if ($naxis <= $naxis_half - 1)
          {
            $axisyindex = array_search($sensor_dataunit, $dataunit_arr) ;
            $axisytype = "primary";
          }
          else
          {
            $axisyindex = array_search($sensor_dataunit, $dataunit_arr) - $naxis_half ;
            $axisytype = "secondary";
          }
        }
        else
        {
          $axisyindex = array_search($sensor_dataunit, $dataunit_arr) ;
          $axisytype = "primary";
        }
        $naxis++;
      }
      else
      {
        if ($naxis_half > 0)
        {
          // If more than 4 build scales on the other side Y2
          if ($naxis <= $naxis_half - 1)
          {
            $axisyindex = array_search($sensor_dataunit, $dataunit_arr) ;
            $axisytype = "primary";
          }
          else
          {
            $axisyindex = array_search($sensor_dataunit, $dataunit_arr) - $naxis_half ;
            $axisytype = "secondary";
          }
        }
        else
        {
          $axisyindex = array_search($sensor_dataunit, $dataunit_arr) ;
          $axisytype = "primary";
        }
      }
      $nsensor++;
      
      
      
      /*
      // Get the last sample measured by that Sensor
	    $query2 = "SELECT sample.id AS sample_id, sample.value AS sample_value, sampledat
            FROM sample
            WHERE equipement = " . $equipment 
            . " AND sensor = " . $sensor_id 
            . " ORDER BY sampledat DESC LIMIT 1" ;
      $result2 = mysqli_query($dbc, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc));
      $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
      
      $last_sample_value = $row2[1] ;
      $last_sample_time = timeElapsedString($row2[2]) ;
      */
      
      $query2 = "SELECT lastvalue_equipment.id AS lastvalue_id, value_per_sensor, sampledat
            FROM lastvalue_equipment
            WHERE equipment_id = " . $equipment ;
      $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local));
      $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
      $sensor_json = $row2[1] ;
      // Beware of the special characters
      $sensor_json = utf8_encode($sensor_json);
      $sensor_json = preg_replace('/\s+/', ' ',$sensor_json);
      // Decode JSON
      $sensor_obj = json_decode($sensor_json) ;
      
      // Get the last updated value and construct the other values based on it (for now!)
      foreach ($sensor_obj as $sensor)
      {
        if ($sensor->id == $sensor_id)
          $last_sample_value = $sensor->value ;
      }
      $last_sample_time = timeElapsedString($row2[2]) ;
      
      // Prepare Array for Ademir
      $final_sensor_array[$final_sensor_iter][0] = $sensor_name ;
      $final_sensor_array[$final_sensor_iter][1] = $last_sample_value ;
      $final_sensor_array[$final_sensor_iter][2] = $sensor_dataunit ;
      $final_sensor_iter++ ;
      
      $final_sensor_table .= "<tr><td title=\"" . $last_sample_time . "\">" . $sensor_name . "</td><td>" . $last_sample_value . "</td><td>" . $sensor_dataunit . "</td>"
                       . "<td><span class=\"option selectable remove\" chart=\"" . $chart_number . "\" plot_number=\"" . $plot_number 
                       . "\" series=\"" . $plot_number . "\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>"
                       . "<span class=\"option selectable add\" chart=\"" . $chart_number . "\" plot_number=\"" . $plot_number 
                       . "\" series=\"" . $plot_number . "\" sensor_name=\"" . $sensor_name . "\" axisyindex=\"" . $axisyindex 
                       . "\" axisytype=\"" . $axisytype . "\">PLOT</span></td>"
                       . "</tr>";
      $plot_number++ ;
		}
    $final_sensor_table .= "</tbody>" ;
    
    $final_sensor_table .= "

  </table>" ;
    
  }

  // Get the equipment Name
  $query4 = "SELECT equipement.name FROM equipement
            WHERE id = " . $equipment;
  $result4 = mysqli_query($dbc, $query4) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysqli_error($dbc));
  $row4 = mysqli_fetch_array($result4, MYSQLI_NUM) ;
  $equipment_name = $row4[0] ;
  
  // Close db
  db_close($dbc) ;
  db_close($dbc_local) ;

  return array($final_sensor_table,$final_sensor_array, $equipment_name) ;
} // buildSensorTable

// Builds and returns controlling Table for all sensors in Analysis page
function buildAllSensorsTable($equipment, $chart_number)
{

  // Connect to db
  $dbc = db_connect_sims() ;
  $dbc_local = db_connect_local() ;
  
  // Prepare array for Ademir
  $final_sensor_table = '' ;
  $final_sensor_array = array() ;
  $final_sensor_iter = 0 ;
  // Start the average array for javascript
  $sensor_avg_array_js = '';
  
  // Add the condition and data type
  $table_type = 'sensor_data' ;

  // Get all the sensors (Gas+Met)
  $plot_number = 0 ;
	$query = "SELECT sensor.id AS sensor_id, sensor.name AS sensor_name
            , sensor.dataunit AS sensor_dataunit
            FROM sensor
            WHERE equipement = " . $equipment 
              . " AND sensor.id IN (SELECT sensor FROM sample)"
            . " ORDER BY sensor.id ASC" ;
	$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
	if (@mysqli_num_rows($result) != 0) 
  {
    // Prepare Array for Ademir
    $final_sensor_array[$final_sensor_iter][0] = 'Parameter' ;
    $final_sensor_array[$final_sensor_iter][1] = 'Value' ;
    $final_sensor_array[$final_sensor_iter][2] = 'Data Unit' ;
    $final_sensor_iter++ ;
    
    // Add the frozen table to be replaced asap
    $final_sensor_table .= "
    <table id=\"gas\" class=\"scroll " . $table_type . "\">
    <thead>
    <tr>
    <th>Sensor</th>
    <th><select id=\"toggle_avg\"><option value=\"0\" selected>Current</option><option value=\"1\">1 Hour</option>
    <option value=\"8\">8 Hours</option><option value=\"24\">24 Hours</option></select></th>
    <th>AQI</th>
    <th>&nbsp;</th>
    </tr>
    </thead>" ;
    
    $final_sensor_table .= "<tbody>" ;
    
    $naxis = 0 ;
    $nsensor = 0 ;
    $sensor_arr = array() ;
    $axisy = '' ;
    $dataunit_arr = array() ;
    $dataunit_str = ' ' ;
    list ($naxis_full, $naxis_half) = calcNumberOfYAxesAllSensorsCanvasJS($equipment) ;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
    {
      $sensor_id = $row['sensor_id'] ;
      $sensor_name = shortenSensorName($row['sensor_name']) ;
      $sensor_dataunit = $row['sensor_dataunit'] ;
      if (strlen($sensor_dataunit) == 0)
        $sensor_dataunit = 'uu' ;
      
      // register the dataunit
      if (substr_count($dataunit_str, $sensor_dataunit) == 0)
      {
        $dataunit_arr[$naxis] = $sensor_dataunit ;
        $sensor_arr[$sensor_name] = array_search($sensor_dataunit, $dataunit_arr) ;
        $dataunit_str .= $sensor_dataunit ;
        
        if ($naxis_half > 0)
        {
          // If more than 4 build scales on the other side Y2
          if ($naxis <= $naxis_half - 1)
          {
            $axisyindex = array_search($sensor_dataunit, $dataunit_arr) ;
            $axisytype = "primary";
          }
          else
          {
            $axisyindex = array_search($sensor_dataunit, $dataunit_arr) - $naxis_half ;
            $axisytype = "secondary";
          }
        }
        else
        {
          $axisyindex = array_search($sensor_dataunit, $dataunit_arr) ;
          $axisytype = "primary";
        }
        $naxis++;
      }
      else
      {
        if ($naxis_half > 0)
        {
          // If more than 4 build scales on the other side Y2
          if ($naxis <= $naxis_half - 1)
          {
            $axisyindex = array_search($sensor_dataunit, $dataunit_arr) ;
            $axisytype = "primary";
          }
          else
          {
            $axisyindex = array_search($sensor_dataunit, $dataunit_arr) - $naxis_half ;
            $axisytype = "secondary";
          }
        }
        else
        {
          $axisyindex = array_search($sensor_dataunit, $dataunit_arr) ;
          $axisytype = "primary";
        }
      }
      $nsensor++;
      
      $query2 = "SELECT lastvalue_equipment.id AS lastvalue_id, value_per_sensor, sampledat
            FROM lastvalue_equipment
            WHERE equipment_id = " . $equipment ;
      $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local));
      $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
      $sensor_json = $row2[1] ;
      // Beware of the special characters
      $sensor_json = utf8_encode($sensor_json);
      $sensor_json = preg_replace('/\s+/', ' ',$sensor_json);
      // Decode JSON
      $sensor_obj = json_decode($sensor_json) ;
      
      // Get the last updated value and construct the other values based on it (for now!)
      foreach ($sensor_obj as $sensor)
      {
        if ($sensor->id == $sensor_id)
          $last_sample_value = $sensor->value ;
      }
      $last_sample_time = timeElapsedString($row2[2]) ;
      
      // Prepare Array for Ademir
      $final_sensor_array[$final_sensor_iter][0] = $sensor_name ;
      $final_sensor_array[$final_sensor_iter][1] = $last_sample_value ;
      $final_sensor_array[$final_sensor_iter][2] = $sensor_dataunit ;
      $final_sensor_iter++ ;
      
      $final_sensor_table .= "<tr chemical=\"" . $sensor_name . "\"><td title=\"" . $last_sample_time . "\">" . $sensor_name . "</td><td class=\"avg\">" . $last_sample_value . " " . $sensor_dataunit . "</td>"
                       . "<td class=\"aqi\">100</td><td><span class=\"option selectable remove\" chart=\"" . $chart_number . "\" plot_number=\"" . $plot_number 
                       . "\" series=\"" . $plot_number . "\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>"
                       . "<span class=\"option selectable add\" chart=\"" . $chart_number . "\" plot_number=\"" . $plot_number 
                       . "\" series=\"" . $plot_number . "\" sensor_name=\"" . $sensor_name . "\" axisyindex=\"" . $axisyindex 
                       . "\" axisytype=\"" . $axisytype . "\">PLOT</span></td>"
                       . "</tr>";
      
      if (strlen($sensor_avg_array_js) != 0)
        $sensor_avg_array_js .= ',
      ' ;
      $sensor_avg_array_js .= '["' . $sensor_name . '","' . $sensor_dataunit . '",' . $last_sample_value . ',' . '0.001' . ',' . '0.008' . ',' . '0.024'
                                . ',"' . '100' . '","' . '-' . '","' . '80' . '","' . '240' . '"]';
      
      $plot_number++ ;
		}
    $final_sensor_table .= "</tbody>" ;
    
    $final_sensor_table .= "

  </table>" ;
  
	}
  // Finish the average array for javascript
  $sensor_avg_array_js = '  var sensor_data = [ ' . $sensor_avg_array_js . ' ] ;
  ' ;
  
  // Get the equipment Name
  $query4 = "SELECT equipement.name FROM equipement
            WHERE id = " . $equipment;
  $result4 = mysqli_query($dbc, $query4) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysqli_error($dbc));
  $row4 = mysqli_fetch_array($result4, MYSQLI_NUM) ;
  $equipment_name = $row4[0] ;
  
  // Close db
  db_close($dbc) ;
  db_close($dbc_local) ;

  return array($final_sensor_table,$final_sensor_array, $equipment_name, $sensor_avg_array_js) ;
} // buildAllSensorsTable

// Builds and returns JS data for each sensors of the equipement
function getPreSavedSensorDataCanvasJS($equipment, $date_range, $met = false)
{
  
  // Connect to db
  $dbc_local = db_connect_local() ;
  
  // Get the date Range ID from date range to construct the name of the file
  // First get the Date range ID from local connection
  $query = "SELECT date_range.id AS daterange_id FROM date_range WHERE daterange = '" . $date_range . "'" ;
  $result = mysqli_query($dbc_local, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
  $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
  $daterange_id = $row[0] ;
  
  $filepath = "/var/www/html/includes/php/crontab/files/" ;
  
  if ($met)
    $met_ext = '_met' ;
  else
    $met_ext = '' ;
  
  $file_chart = $filepath . "series_plot". $met_ext . "_id" . $equipment . "_dr" . $daterange_id . ".txt" ;
  $file_series = $filepath . "chart_container". $met_ext . "_id" . $equipment . "_dr" . $daterange_id . ".txt" ;
  
  #usage: 
  if (file_exists($file_chart)) 
    $final_sensor_chart_js = file_get_contents($file_chart) ;
  else
    $final_sensor_chart_js = '' ;
  
  if (file_exists($file_series)) 
    $final_sensor_series_js = file_get_contents($file_series) ;
  else
    $final_sensor_series_js = '' ;
  
  // Close db
  db_close($dbc_local) ;
  
  return array($final_sensor_chart_js, $final_sensor_series_js) ;
  
} // getPreSavedSensorDataCanvasJS

// Builds and returns JS data for all sensors of one equipement for Analysis page
function getPreSavedAllSensorsDataCanvasJS($equipment, $date_range)
{
  
  // Connect to db
  $dbc_local = db_connect_local() ;
  
  // Get the date Range ID from date range to construct the name of the file
  // First get the Date range ID from local connection
  $query = "SELECT date_range.id AS daterange_id FROM date_range WHERE daterange = '" . $date_range . "'" ;
  $result = mysqli_query($dbc_local, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
  $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
  $daterange_id = $row[0] ;
  
  $filepath = "/var/www/html/includes/php/crontab/files/" ;
  
  $met_ext = '_all' ;
  
  $file_chart = $filepath . "series_plot". $met_ext . "_id" . $equipment . "_dr" . $daterange_id . ".txt" ;
  $file_series = $filepath . "chart_container". $met_ext . "_id" . $equipment . "_dr" . $daterange_id . ".txt" ;
  
  #usage: 
  if (file_exists($file_chart)) 
    $final_sensor_chart_js = file_get_contents($file_chart) ;
  else
    $final_sensor_chart_js = '' ;
  
  if (file_exists($file_series)) 
    $final_sensor_series_js = file_get_contents($file_series) ;
  else
    $final_sensor_series_js = '' ;
  
  // Close db
  db_close($dbc_local) ;
  
  return array($final_sensor_chart_js, $final_sensor_series_js) ;
  
} // getPreSavedAllSensorsDataCanvasJS

// Builds and returns JS data for each sensors of the equipement
function getDbSensorDataCanvasJS($equipment, $date_range, $met = false)
{
  // Connect to db
  $dbc_local = db_connect_local() ;
  
  // First get the Date range ID from local connection
  $query = "SELECT date_range.id AS daterange_id FROM date_range WHERE daterange = '" . $date_range . "'" ;
  $result = mysqli_query($dbc_local, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
  $row = mysqli_fetch_array($result, MYSQLI_NUM) ;

  $daterange_id = $row[0] ;
  
  if ($met)
  {
    // Now get the data from the local DB for the equipment
    $query2 = "SELECT canvas_js_equipment.id AS canvasjs_id, chart_container_met, series_plot_met 
               FROM canvas_js_equipment
               WHERE daterange_id = " . $daterange_id
               . " AND equipment_id = " . $equipment ;
  }
  else
  {
    // Now get the data from the local DB for the equipment
    $query2 = "SELECT canvas_js_equipment.id AS canvasjs_id, chart_container, series_plot 
               FROM canvas_js_equipment
               WHERE daterange_id = " . $daterange_id
               . " AND equipment_id = " . $equipment ;
  }

  $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
  $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
  
  $final_sensor_chart_js = $row2[1] ;
  $final_sensor_series_js = $row2[2] ;
  
  // Close db
  db_close($dbc_local) ;

  return array($final_sensor_chart_js, $final_sensor_series_js) ;
  
} // getDbSensorDataCanvasJS

// Builds and returns JS data for each sensors of the equipement
function buildSensorDataCanvasJS($equipment, $begin_date, $end_date, $chart_number, $met = false)
{
  // Connect to db
  $dbc = db_connect_sims() ;
  
  // Get the equipment Name
  $query4 = "SELECT equipement.name FROM equipement
            WHERE id = " . $equipment;
  $result4 = mysqli_query($dbc, $query4) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysqli_error($dbc));
  $row4 = mysqli_fetch_array($result4, MYSQLI_NUM) ;
  $equipment_name = $row4[0] ;
  
  // Initialize the CanvasJS values
  $final_sensor_chart_js = '' ;
  $final_sensor_series_js = '' ;
  
  // Add the condition for Met data
  if ($met)
    $sign = 'NOT' ;
  else
    $sign = '' ;
  
  $naxis = 0 ;
  $nsensor = 0 ;
  $sensor_arr = array() ;
  $axisy = '' ;
  $dataunit_arr = array() ;
  $dataunit_str = ' ' ;
  if ($met)
    list ($naxis_full, $naxis_half) = calcNumberOfYAxesCanvasJS($equipment, $met) ;
  else
    list ($naxis_full, $naxis_half) = calcNumberOfYAxesCanvasJS($equipment) ;
  // Go through each different dataunit and build a scale
  // First get all the different MET sensors from this equipement ordered!
  $query3 = "SELECT sensor.id AS sensor_id, sensor.name AS sensor_name
            , sensor.dataunit AS sensor_dataunit
            FROM sensor
            WHERE equipement = " . $equipment 
            . " AND dataunit " . $sign . " IN ('PPM','PPB', 'ug/m3','ou','ou/m3')"
            . " ORDER BY sensor.id ASC" ;
  $result3 = mysqli_query($dbc, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc));
  while ($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC))
  {
    $sensor_dataunit = $row3['sensor_dataunit'] ;
    if (strlen($sensor_dataunit) == 0)
      $sensor_dataunit = 'uu' ;
    $sensor_name =  $row3['sensor_name'] ;
    
    // register the dataunit
    if (substr_count($dataunit_str, $sensor_dataunit) == 0)
    {
      $dataunit_arr[$naxis] = $sensor_dataunit ;
      $sensor_arr[$sensor_name] = array_search($sensor_dataunit, $dataunit_arr) ;
      $dataunit_str .= $sensor_dataunit ;
      
      if ($naxis_half > 0)
      {
        // If more than 4 build scales on the other side Y2
        if ($naxis <= $naxis_half - 1)
        {
          if ($naxis == 0)
            $axisy .= '		axisY:[';
          $axisy .= '{
            title: "' . $sensor_dataunit . '",
            titleFontSize: 14,
            //gridThickness: 0, -- remove grid line
            tickColor: "#0496FF", // -- Dark theme
            gridColor: "#0496FF", // -- Dark theme
            lineColor: "#0496FF", // -- Dark theme
            titleFontColor: "#FFFFFF", // -- Dark theme
            labelFontColor: "#FFFFFF" // -- Dark theme
            /* tickColor: "#0496FF", // -- Light theme
            gridColor: "#0496FF", // -- Light theme
            lineColor: "#0496FF", // -- Light theme
            titleFontColor: "#2f2f2f", // -- Light theme
            labelFontColor: "#2f2f2f" */ // -- Light theme
          }' ;
          if ($naxis == $naxis_half - 1)
            $axisy .= '],' ;
          else
            $axisy .= ',' ;
            
        }
        else
        {
          if ($naxis == $naxis_half)
            $axisy .= '
          axisY2:[';
          $axisy .= '{
            title: "' . $row3['sensor_dataunit'] . '",
            titleFontSize: 14,
            //gridThickness: 0, -- remove grid line
            tickColor: "#0496FF", // -- Dark theme
            gridColor: "#0496FF", // -- Dark theme
            lineColor: "#0496FF", // -- Dark theme
            titleFontColor: "#FFFFFF", // -- Dark theme
            labelFontColor: "#FFFFFF" // -- Dark theme
            /* tickColor: "#0496FF", // -- Light theme
            gridColor: "#0496FF", // -- Light theme
            lineColor: "#0496FF", // -- Light theme
            titleFontColor: "#2f2f2f", // -- Light theme
            labelFontColor: "#2f2f2f" */ // -- Light theme
          }' ;
          if ($naxis == $naxis_full - 1)
            $axisy .= '],' ;
          else
            $axisy .= ',' ;
        }
      } 
      else
      {
        if ($naxis == 0)
          $axisy .= '		axisY:[';
        $axisy .= '{
            title: "' . $sensor_dataunit . '",
            titleFontSize: 14,
            tickColor: "#0496FF", // -- Dark theme
            gridColor: "#0496FF", // -- Dark theme
            lineColor: "#0496FF", // -- Dark theme
            titleFontColor: "#FFFFFF", // -- Dark theme
            labelFontColor: "#FFFFFF" // -- Dark theme
            /* tickColor: "#0496FF", // -- Light theme
            gridColor: "#0496FF", // -- Light theme
            lineColor: "#0496FF", // -- Light theme
            titleFontColor: "#2f2f2f", // -- Light theme
            labelFontColor: "#2f2f2f" */ // -- Light theme
        }' ;
        if ($naxis == $naxis_full - 1)
          $axisy .= '],' ;
        else
          $axisy .= ',' ;
      }
      $naxis++;
    }
    else
      $sensor_arr[$sensor_name] = array_search($sensor_dataunit, $dataunit_arr) ;
    $nsensor++;
  }
  
  // First get all the different sensors from this equipement ordered!
	$query = "SELECT sensor.id AS sensor_id, sensor.name AS sensor_name
            , sensor.dataunit AS sensor_dataunit
            FROM sensor
            WHERE equipement = " . $equipment 
            . " AND dataunit " . $sign . " IN ('PPM','PPB','ug/m3','ou','ou/m3')"
            . " ORDER BY sensor.id ASC" ;
	$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
	if (@mysqli_num_rows($result) != 0) 
  {
      // Test
      //$testme = mysqli_num_rows($result) ;
    
    // Construct the chart container for each sensors
    $final_sensor_chart_js .= "  
    chart[$chart_number] = new CanvasJS.Chart(\"chartContainer$chart_number\",
    {
      colorSet: \"customSet\",
      title:{
//        text: \"$equipment_name\",
      },
      toolTip:{
        enabled: true,
        animationEnabled: true,
        backgroundColor: \"rgba(20,20,20,.8)\",
        borderThickness: \"2\",
        fontColor: \"white\"
      },
      backgroundColor: \"#F5DEB300\",
      $axisy
      axisX: {
            tickColor: \"#0496FF\", // -- Dark theme
            gridColor: \"#0496FF\", // -- Dark theme
            lineColor: \"#0496FF\", // -- Dark theme
            titleFontColor: \"#FFFFFF\", // -- Dark theme
            labelFontColor: \"#FFFFFF\" // -- Dark theme
            /* tickColor: \"#0496FF\", // -- Light theme
            gridColor: \"#0496FF\", // -- Light theme
            lineColor: \"#0496FF\", // -- Light theme
            titleFontColor: \"#2f2f2f\", // -- Light theme
            labelFontColor: \"#2f2f2f\" */ // -- Light theme
      },
      data: [
      " ;
    
    // Construct the plot series for the Chart to be displayed
    $final_sensor_series_js .= " if (chart_number == $chart_number)
      {
    " ;
    $plot_series = 0;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
    {
      // Handle the date interval
      //$sql_date_interval = strtotime("-1 week") ;
      
      // Default Begin date (1 week ago)
      if ($begin_date == '')
        $begin_timestamp = strtotime("-1 week") ;
      else
        $begin_timestamp = strtotime($begin_date) ;
      // Default End date (Now)
      if ($end_date == '')
        $end_timestamp = strtotime("now") ;
      else
        $end_timestamp = strtotime($end_date) ;
      
      $sensor_id = $row['sensor_id'] ;
      $sensor_name = $row['sensor_name'] ;
      // Now get all the sample value for the sensors and build the CanvasJS data
      // Get the values and timestamp to build the CanvasJS data
      $query2 = "SELECT sample.id AS sample_id, sample.value, sample.sampledat, sensor.name
                FROM sample
                INNER JOIN sensor ON sample.sensor = sensor.id
                WHERE sample.equipement = " . $equipment 
                . " AND sample.sensor = " . $sensor_id
                . " AND sampledat >= " . $begin_timestamp
                . " AND sampledat <= " . $end_timestamp
                . " ORDER BY sampledat ASC" ;
      $result2 = mysqli_query($dbc, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc));
      if (@mysqli_num_rows($result2) != 0) 
      {
        
        $nyindex = $sensor_arr[$sensor_name] ;
        if ($naxis_half > 0)
        {
          if ($nyindex < $naxis_half)
          {
            $nyindex = $nyindex ;
            $axisytype = '' ;
          }
          else
          {
            $nyindex = $nyindex - $naxis_half ;
            $axisytype = 'axisYType: "secondary",
          ' ;
          }
        }
        else
        {
            $nyindex = $nyindex ;
            $axisytype = '' ;
        }
        
        $final_sensor_chart_js .= "     {        
        type: \"spline\",
        markerType: \"none\",
        name: \"$sensor_name\",
        showInLegend: false,
        axisYIndex: " . $nyindex . ",
        "
        . $axisytype . 
        "lineThickness: 1,
        xValueType: \"dateTime\",
        dataPoints: [" ;
        
        // Construct the plot series for the Chart to be displayed
        if ($plot_series > 0)
          $final_sensor_series_js .= " else" ;
        $final_sensor_series_js .= " if (series_to_plot == $plot_series)
        {" ;
        $plot_series++ ;
        while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC))
        {
          $milli_timestamp = $row2['sampledat'] * 1000 ;
          // Verify that the value is numeric as the SQL field has been defined as TEXT!!!!!!!
          if (is_numeric($row2['value']))
            $sample_value = floatval($row2['value']) ;
          else
            $sample_value = 0;
          
          // Add each data samples to the CanvasJS files
          $final_sensor_chart_js .= '{ x: ' . $milli_timestamp . ', y: ' . $sample_value . ' },';
          $final_sensor_series_js .= 'dataPoints.push({ x: ' . $milli_timestamp . ', y: ' . $sample_value . ' });' ;
          
        }
        
        $final_sensor_chart_js .= " 
        ]
      }," ;
        
        $final_sensor_series_js .= " 
        }" ;
        
      }
      
      
    }
    
    $final_sensor_chart_js .= "
    ]
  });
  
  chart[$chart_number].render();
  
  $('.button_export').click(function () {
      chart[1].exportChart({format: \"jpg\"});
  });
  ";
    
    $final_sensor_series_js .= " 
    }";
    
  }
  
  // Close db
  db_close($dbc) ;

  return array($final_sensor_chart_js, $final_sensor_series_js) ;
  
} // buildSensorDataCanvasJS

// Builds and returns JS data for all sensors of the equipement (MET+GAS)
function buildAllSensorsDataCanvasJS($equipment, $begin_date, $end_date) {
  // Connect to db
  $dbc = db_connect_sims() ;
  
  // Get the equipment Name
  $query4 = "SELECT equipement.name FROM equipement
            WHERE id = " . $equipment;
  $result4 = mysqli_query($dbc, $query4) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysqli_error($dbc));
  $row4 = mysqli_fetch_array($result4, MYSQLI_NUM) ;
  $equipment_name = $row4[0] ;
  
  // Initialize the CanvasJS values
  $final_sensor_chart_js = '' ;
  $final_sensor_series_js = '' ;
  $chart_number = 1 ;
  
  $naxis = 0 ;
  $nsensor = 0 ;
  $sensor_arr = array() ;
  $axisy = '' ;
  $dataunit_arr = array() ;
  $dataunit_str = ' ' ;
  
  list ($naxis_full, $naxis_half) = calcNumberOfYAxesAllSensorsCanvasJS($equipment) ;
  // Go through each different dataunit and build a scale
  // First get all the different MET sensors from this equipement ordered!
  $query3 = "SELECT sensor.id AS sensor_id, sensor.name AS sensor_name
            , sensor.dataunit AS sensor_dataunit
            FROM sensor
            WHERE equipement = " . $equipment 
            . " ORDER BY sensor.id ASC" ;
  $result3 = mysqli_query($dbc, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc));
  while ($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC))
  {
    $sensor_dataunit = $row3['sensor_dataunit'] ;
    if (strlen($sensor_dataunit) == 0)
      $sensor_dataunit = 'uu' ;
    $sensor_name =  $row3['sensor_name'] ;
    
    // register the dataunit
    if (substr_count($dataunit_str, $sensor_dataunit) == 0)
    {
      $dataunit_arr[$naxis] = $sensor_dataunit ;
      $sensor_arr[$sensor_name] = array_search($sensor_dataunit, $dataunit_arr) ;
      $dataunit_str .= $sensor_dataunit ;
      
      if ($naxis_half > 0)
      {
        // If more than 4 build scales on the other side Y2
        if ($naxis <= $naxis_half - 1)
        {
          if ($naxis == 0)
            $axisy .= '		axisY:[';
          $axisy .= '{
            suffix: "' . $sensor_dataunit . '",
            titleFontSize: 12,
            labelFontSize: 12,
            labelFontFamily: "Calibri",
            tickColor: "rgba(4, 150, 255, .7)", // -- Dark theme
            gridColor: "rgba(4, 150, 255, .1)", // -- Dark theme
            lineColor: "rgba(4, 150, 255, .7)", // -- Dark theme
            titleFontColor: "white", // -- Dark theme
            labelFontColor: "white" // -- Dark theme
          }' ;
          if ($naxis == $naxis_half - 1)
            $axisy .= '],' ;
          else
            $axisy .= ',' ;
            
        }
        else
        {
          if ($naxis == $naxis_half)
            $axisy .= '
          axisY2:[';
          $axisy .= '{
            suffix: "' . $row3['sensor_dataunit'] . '",
            titleFontSize: 12,
            labelFontSize: 12,
            labelFontFamily: "Calibri",
            tickColor: "rgba(4, 150, 255, .7)", // -- Dark theme
            gridColor: "rgba(4, 150, 255, .1)", // -- Dark theme
            lineColor: "rgba(4, 150, 255, .7)", // -- Dark theme
            titleFontColor: "white", // -- Dark theme
            labelFontColor: "white" // -- Dark theme
          }' ;
          if ($naxis == $naxis_full - 1)
            $axisy .= '],' ;
          else
            $axisy .= ',' ;
        }
      } 
      else
      {
        if ($naxis == 0)
          $axisy .= '		axisY:[';
        $axisy .= '{
            suffix: "' . $sensor_dataunit . '",
            titleFontSize: 12,
            labelFontSize: 12,
            labelFontFamily: "Calibri",
            tickColor: "rgba(4, 150, 255, .7)", // -- Dark theme
            gridColor: "rgba(4, 150, 255, .1)", // -- Dark theme
            lineColor: "rgba(4, 150, 255, .7)", // -- Dark theme
            titleFontColor: "white", // -- Dark theme
            labelFontColor: "white" // -- Dark theme
        }' ;
        if ($naxis == $naxis_full - 1)
          $axisy .= '],' ;
        else
          $axisy .= ',' ;
      }
      $naxis++;
    }
    else
      $sensor_arr[$sensor_name] = array_search($sensor_dataunit, $dataunit_arr) ;
    $nsensor++;
  }
  
  // First get all the different sensors from this equipement ordered!
	$query = "SELECT sensor.id AS sensor_id, sensor.name AS sensor_name
            , sensor.dataunit AS sensor_dataunit
            FROM sensor
            WHERE equipement = " . $equipment 
            . " ORDER BY sensor.id ASC" ;
	$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
	if (@mysqli_num_rows($result) != 0) 
  {
      // Test
      //$testme = mysqli_num_rows($result) ;
    
    // Construct the chart container for each sensors
    $final_sensor_chart_js .= "  
    chart[$chart_number] = new CanvasJS.Chart(\"chartContainer$chart_number\",
    {
      colorSet: \"customSet\",
      title:{
//        text: \"$equipment_name\",
      },
      toolTip:{
        enabled: true,
        animationEnabled: true,
        backgroundColor: \"rgba(20,20,20,.8)\",
        borderThickness: \"2\",
        fontColor: \"white\"
      },
      backgroundColor: '#F5DEB300',
      $axisy
      axisX: {
        gridThickness: 1,
        titleFontSize: 12,
        labelFontSize: 12,
        labelFontFamily: \"Calibri\",
        interval: 30,
        intervalType: \"minute\",
        labelFormatter: function(e) {
            return CanvasJS.formatDate(e.value, \"HH:mm\");
        },
        tickColor: \"rgba(4, 150, 255, .7)\", // -- Dark theme
        gridColor: \"rgba(4, 150, 255, .1)\", // -- Dark theme
        lineColor: \"rgba(4, 150, 255, .7)\", // -- Dark theme
        titleFontColor: \"white\", // -- Dark theme
        labelFontColor: \"white\" // -- Dark theme
      },
      data: [
      " ;
    
    // Construct the plot series for the Chart to be displayed
    $final_sensor_series_js .= " if (chart_number == $chart_number)
      {
    " ;
    $plot_series = 0;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
    {
      // Handle the date interval
      //$sql_date_interval = strtotime("-1 week") ;
      
      // Default Begin date (1 week ago)
      if ($begin_date == '')
        $begin_timestamp = strtotime("-1 week") ;
      else
        $begin_timestamp = strtotime($begin_date) ;
      // Default End date (Now)
      if ($end_date == '')
        $end_timestamp = strtotime("now") ;
      else
        $end_timestamp = strtotime($end_date) ;
      
      $sensor_id = $row['sensor_id'] ;
      $sensor_name = $row['sensor_name'] ;
      // Now get all the sample value for the sensors and build the CanvasJS data
      // Get the values and timestamp to build the CanvasJS data
      $query2 = "SELECT sample.id AS sample_id, sample.value, sample.sampledat, sensor.name
                FROM sample
                INNER JOIN sensor ON sample.sensor = sensor.id
                WHERE sample.equipement = " . $equipment 
                . " AND sample.sensor = " . $sensor_id
                . " AND sampledat >= " . $begin_timestamp
                . " AND sampledat <= " . $end_timestamp
                . " ORDER BY sampledat ASC" ;
      $result2 = mysqli_query($dbc, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc));
      if (@mysqli_num_rows($result2) != 0) 
      {
        
        $nyindex = $sensor_arr[$sensor_name] ;
        if ($naxis_half > 0)
        {
          if ($nyindex < $naxis_half)
          {
            $nyindex = $nyindex ;
            $axisytype = '' ;
          }
          else
          {
            $nyindex = $nyindex - $naxis_half ;
            $axisytype = 'axisYType: "secondary",
          ' ;
          }
        }
        else
        {
            $nyindex = $nyindex ;
            $axisytype = '' ;
        }
        
        $final_sensor_chart_js .= "     {        
        type: \"spline\",
        markerType: \"none\",
        name: \"$sensor_name\",
        showInLegend: false,
        axisYIndex: " . $nyindex . ",
        "
        . $axisytype . 
        "lineThickness: 1,
        xValueType: \"dateTime\",
        dataPoints: [" ;
        
        // Construct the plot series for the Chart to be displayed
        if ($plot_series > 0)
          $final_sensor_series_js .= " else" ;
        $final_sensor_series_js .= " if (series_to_plot == $plot_series)
        {" ;
        $plot_series++ ;
        while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC))
        {
          $milli_timestamp = $row2['sampledat'] * 1000 ;
          // Verify that the value is numeric as the SQL field has been defined as TEXT!!!!!!!
          if (is_numeric($row2['value']))
            $sample_value = floatval($row2['value']) ;
          else
            $sample_value = 0;
          
          // Add each data samples to the CanvasJS files
          $final_sensor_chart_js .= '{ x: ' . $milli_timestamp . ', y: ' . $sample_value . ' },';
          $final_sensor_series_js .= 'dataPoints.push({ x: ' . $milli_timestamp . ', y: ' . $sample_value . ' });' ;
          
        }
        
        $final_sensor_chart_js .= " 
        ]
      }," ;
        
        $final_sensor_series_js .= " 
        }" ;
        
      }
      
      
    }
    
    $final_sensor_chart_js .= "
    ]
  });
  
  chart[$chart_number].render();
  ";
    
    $final_sensor_series_js .= " 
    }" ;
    
  }
  
  // Close db
  db_close($dbc) ;

  return array($final_sensor_chart_js, $final_sensor_series_js) ;
  
} // buildAllSensorsDataCanvasJS

// Calculate the number of total Y-Axes for Met data in one equipment and the half displayed on the primary (left side)
// Number of axes equal the number of data units
function calcNumberOfYAxesCanvasJS($equipment, $met = false)
{
  
  // Connect to db
  $dbc = db_connect_sims() ;
  
  // Add the condition for Met data
  if ($met)
    $sign = 'NOT' ;
  else
    $sign = '' ;
  
  $dataunit_arr = array() ;
  $ndataunit = 1 ;
  // First get all the different dataunits of Met sensors!
  $query = "SELECT sensor.id AS sensor_id, sensor.dataunit AS sensor_dataunit
            FROM sensor
            WHERE equipement = " . $equipment 
            . " AND dataunit " . $sign . " IN ('PPM','PPB','ug/m3','ou','ou/m3')"
            . " ORDER BY sensor.id ASC" ;
  $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
  {
    // Go through each sensors and register each different data units
    $sensor_dataunit = $row['sensor_dataunit'] ;
    $is_registered = array_search($sensor_dataunit, $dataunit_arr) ;
    // If is not already registered enter it!
    if (!$is_registered)
    {
      $dataunit_arr[$ndataunit] = $sensor_dataunit ;
      $ndataunit++ ;
    }
  }
  
  // Calculate final Yaxis values (If more than 4 Data unit split in half if less or equals 4 display all the Y axes on the left side)
  $naxis_full = $ndataunit - 1 ;
  if ($naxis_full > 4)
    $naxis_half = ceil($naxis_full / 2) ;
  else
    $naxis_half = 0 ;
  
  // Close db
  db_close($dbc) ;
  
  return array($naxis_full, $naxis_half) ;
  
} // calcNumberOfYAxesCanvasJS

// Calculate the number of total Y-Axes for All sensors in one equipment
// Number of axes equal the number of data units
function calcNumberOfYAxesAllSensorsCanvasJS($equipment)
{
  
  // Connect to db
  $dbc = db_connect_sims() ;
  
  $dataunit_arr = array() ;
  $ndataunit = 1 ;
  // First get all the different dataunits of Met sensors!
  $query = "SELECT sensor.id AS sensor_id, sensor.dataunit AS sensor_dataunit
            FROM sensor
            WHERE equipement = " . $equipment
            . " ORDER BY sensor.id ASC" ;
  $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
  {
    // Go through each sensors and register each different data units
    $sensor_dataunit = $row['sensor_dataunit'] ;
    $is_registered = array_search($sensor_dataunit, $dataunit_arr) ;
    // If is not already registered enter it!
    if (!$is_registered)
    {
      $dataunit_arr[$ndataunit] = $sensor_dataunit ;
      $ndataunit++ ;
    }
  }
  
  // Calculate final Yaxis values (If more than 4 Data unit split in half if less or equals 4 display all the Y axes on the left side)
  $naxis_full = $ndataunit - 1 ;
  if ($naxis_full > 4)
    $naxis_half = ceil($naxis_full / 2) ;
  else
    $naxis_half = 0 ;
  
  // Close db
  db_close($dbc) ;
  
  return array($naxis_full, $naxis_half) ;
  
} // calcNumberOfYAxesAllSensorsCanvasJS

// Return serial number of a Scentinal
function get_equipment_sn($equipment) {

    // Connect to db
    $dbc = db_connect_sims() ;
    $dbc_local = db_connect_local() ;


    $query = "SELECT equipement.sn FROM equipement
            WHERE id = " . $equipment;
    $result = mysqli_query($dbc, $query) or trigger_error("Query: $query \n MySQL Error:" . mysqli_error($dbc));
    $row = mysqli_fetch_array($result, MYSQLI_NUM);
    $sn = $row[0];

    // Close db
    db_close($dbc) ;
    db_close($dbc_local) ;

    $sn = strtoupper($sn);

    return $sn;
} // get_equipment_sn

// Builds and returns chart for analysis page
function get_analysis_chart($chart_number) {
    // Returns the chartContainer DIV where $chart_number is the number of the div and the full id would be something like: 'chartContainer1'
    return "<div id='chartContainer" . $chart_number . "'></div>";
} // get_analysis_chart

// Return all sensor values
function get_all_equipment_averages($equipment) {
    // Connect to db
    $dbc_local = db_connect_local();

    $final_sensor_array = array();
    $final_sensor_iter = 0;

    $final_sensor_array[$final_sensor_iter][0] = 'Sensor';
    $final_sensor_array[$final_sensor_iter][1] = 'Last';
    $final_sensor_array[$final_sensor_iter][2] = '1 Hour';
    $final_sensor_array[$final_sensor_iter][3] = '8 Hours';
    $final_sensor_array[$final_sensor_iter][4] = '24 Hours';
    $final_sensor_array[$final_sensor_iter][5] = 'AQI';
    $final_sensor_array[$final_sensor_iter][6] = 'Data Unit';
    $final_sensor_iter++;

    // Calculate the averages for last [updated, 1 hour, 8 hour, 24 hour] + AQI
    // Get the values of each sensors of that equipment
    $query = "SELECT lastvalue_equipment.id AS lastvalue_id, value_per_sensor
        FROM lastvalue_equipment
        WHERE equipment_id = " . $equipment;
    $result = mysqli_query($dbc_local, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc_local));
    $row = mysqli_fetch_array($result, MYSQLI_NUM);
    $sensor_json = $row[1];
    // Beware of the special characters
    $sensor_json = utf8_encode($sensor_json);
    $sensor_json = preg_replace('/\s+/', ' ', $sensor_json);
    // Decode JSON

    $sensor_obj = json_decode($sensor_json);

    // Get the last updated value and construct the other values based on it (for now!)
    //foreach ($obj as $key => $value)
    foreach ($sensor_obj as $value) {
        $final_sensor_array[$final_sensor_iter][0] = shortenSensorName($value->name);
        $final_sensor_array[$final_sensor_iter][1] = $value->value;
        $final_sensor_array[$final_sensor_iter][2] = $value->value;
        $final_sensor_array[$final_sensor_iter][3] = $value->value;
        $final_sensor_array[$final_sensor_iter][4] = $value->value;
        $final_sensor_array[$final_sensor_iter][5] = rand(1, 1000) / 10;
        $final_sensor_array[$final_sensor_iter][6] = $value->unit;
        $final_sensor_iter++;
    }
    return $final_sensor_array;
}

?>