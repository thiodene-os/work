<?php

// Input libraries for Update and other buttons

// Builds and returns the Input Select for Equipments
function buildNotificationsTopSelectFields($equipment=false,$search=false)
{
  // Build the top input bar
  $final_top_input = "" ;
  
  // Connect to db
  $dbc = db_connect_sims() ;
  
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
                  <label>Equipment:</label>
                  <select class=\"input_select\" name=\"equipment\">" ;
                  
    if (!$equipment)
      $final_top_input .= "<option value=\"\" selected>All Equipments</option>" ;
    else
      $final_top_input .= "<option value=\"\">All Equipments</option>" ;
    
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
  
  // Add a Search Bar to that
  $final_top_input .= " <label>Search:</label><input id=\"search\" type=\"text\" class=\"input_text\" name=\"search\" value=\"$search\">" ;
  
  $final_top_input .= " <button class=\"button_update\">Update</button></form>" ;
                  
  // Close db
  db_close($dbc) ;
  
  return $final_top_input ;
} // buildNotificationsTopSelectFields

// Data Type for Gas and Met Sensors
function buildDataUnitSelectInput($data_unit=false, $disabled=false)
{
  $final_data_unit_select = '' ;
  
  $final_data_unit_select .= '<select class="data_unit">' ;
  // PPM
  if ($data_unit == 'ppm')
    $final_data_unit_select .= '<option value="ppm" selected>PPM</option>' ;
  else
  {
    if ($disabled)
      $final_data_unit_select .= '<option value="ppm" disabled>PPM</option>' ;
    else
      $final_data_unit_select .= '<option value="ppm">PPM</option>' ;
  }
  // PPB
  if ($data_unit == 'ppb')
    $final_data_unit_select .= '<option value="ppb" selected>PPB</option>' ;
  else
  {
    if ($disabled)
      $final_data_unit_select .= '<option value="ppb" disabled>PPB</option>' ;
    else
      $final_data_unit_select .= '<option value="ppb">PPB</option>' ;
  }
  // ug/m3
  if ($data_unit == 'ug/m3')
    $final_data_unit_select .= '<option value="ug/m3" selected>ug/m3</option>' ;
  else
  {
    if ($disabled)
      $final_data_unit_select .= '<option value="ug/m3" disabled>ug/m3</option>' ;
    else
      $final_data_unit_select .= '<option value="ug/m3">ug/m3</option>' ;
  }
  // C
  if ($data_unit == 'c')
    $final_data_unit_select .= '<option value="c" selected>C</option>' ;
  else
  {
    if ($disabled)
      $final_data_unit_select .= '<option value="c" disabled>%</option>' ;
    else
      $final_data_unit_select .= '<option value="c">C</option>' ;
  }
  // %
  if ($data_unit == '%')
    $final_data_unit_select .= '<option value="%" selected>%</option>' ;
  else
  {
    if ($disabled)
      $final_data_unit_select .= '<option value="%" disabled>%</option>' ;
    else
      $final_data_unit_select .= '<option value="%">%</option>' ;
  }
  // OU
  if ($data_unit == 'ou')
    $final_data_unit_select .= '<option value="ou" selected>OU</option>' ;
  else
  {
    if ($disabled)
      $final_data_unit_select .= '<option value="ou" disabled>OU</option>' ;
    else
      $final_data_unit_select .= '<option value="ou">OU</option>' ;
  }
  // OU/m3
  if ($data_unit == 'ou/m3')
    $final_data_unit_select .= '<option value="ou/m3" selected>OU/m3</option>' ;
  else
  {
    if ($disabled)
      $final_data_unit_select .= '<option value="ou/m3" disabled>OU/m3</option>' ;
    else
      $final_data_unit_select .= '<option value="ou/m3">OU/m3</option>' ;
  }
  // dB
  if ($data_unit == 'db')
    $final_data_unit_select .= '<option value="db" selected>dB</option>' ;
  else
  {
    if ($disabled)
      $final_data_unit_select .= '<option value="db" disabled>dB</option>' ;
    else
      $final_data_unit_select .= '<option value="db">dB</option>' ;
  }
  // Bq
  if ($data_unit == 'bq')
    $final_data_unit_select .= '<option value="bq" selected>Bq</option>' ;
  else
  {
    if ($disabled)
      $final_data_unit_select .= '<option value="bq" disabled>Bq</option>' ;
    else
      $final_data_unit_select .= '<option value="bq">Bq</option>' ;
  }
  // kPA
  if ($data_unit == 'kpa')
    $final_data_unit_select .= '<option value="kpa" selected>kPA</option>' ;
  else
  {
    if ($disabled)
      $final_data_unit_select .= '<option value="kpa" disabled>kPA</option>' ;
    else
      $final_data_unit_select .= '<option value="kpa">kPA</option>' ;
  }
  // mm
  if ($data_unit == 'mm')
    $final_data_unit_select .= '<option value="mm" selected>mm</option>' ;
  else
  {
    if ($disabled)
      $final_data_unit_select .= '<option value="mm" disabled>mm</option>' ;
    else
      $final_data_unit_select .= '<option value="mm">mm</option>' ;
  }
  // Degrees
  if ($data_unit == 'degrees' || $data_unit == '°' || $data_unit == 'D')
    $final_data_unit_select .= '<option value="degrees" selected>°</option>' ;
  else
  {
    if ($disabled)
      $final_data_unit_select .= '<option value="degrees" disabled>°</option>' ;
    else
      $final_data_unit_select .= '<option value="degrees">°</option>' ;
  }
  // W/m2
  if ($data_unit == 'w/m2')
    $final_data_unit_select .= '<option value="w/m2" selected>W/m2</option>' ;
  else
  {
    if ($disabled)
      $final_data_unit_select .= '<option value="w/m2" disabled>W/m2</option>' ;
    else
      $final_data_unit_select .= '<option value="w/m2">W/m2</option>' ;
  }
  // UV Index
  if ($data_unit == 'uv index')
    $final_data_unit_select .= '<option value="uv index" selected>UV Index</option>' ;
  else
  {
    if ($disabled)
      $final_data_unit_select .= '<option value="uv index" disabled>UV Index</option>' ;
    else
      $final_data_unit_select .= '<option value="uv index">UV Index</option>' ;
  }
  // m/s
  if ($data_unit == 'm/s')
    $final_data_unit_select .= '<option value="m/s" selected>m/s</option>' ;
  else
  {
    if ($disabled)
      $final_data_unit_select .= '<option value="m/s" disabled>m/s</option>' ;
    else
      $final_data_unit_select .= '<option value="m/s">m/s</option>' ;
  }
  
  $final_data_unit_select .= '</select>' ;
  
  
  return $final_data_unit_select ;
  
} // buildDataUnitSelectInput

// Select input for Sensor Types
function buildSensorTypeSelectInput($type=false)
{
  $final_sensor_type_select = '' ;
  
  $final_sensor_type_select .= '<select id="type">' ;
  // Chemical
  if ($type == 'chemical')
    $final_sensor_type_select .= '<option value="chemical" selected>Chemical</option>' ;
  else
    $final_sensor_type_select .= '<option value="chemical">Chemical</option>' ;
  // Temperature
  if ($type == 'temperature' || $type == 'temp')
    $final_sensor_type_select .= '<option value="temperature" selected>Temperature</option>' ;
  else
    $final_sensor_type_select .= '<option value="temperature">Temperature</option>' ;
  // Humidity
  if ($type == 'humidity')
    $final_sensor_type_select .= '<option value="humidity" selected>Humidity</option>' ;
  else
    $final_sensor_type_select .= '<option value="humidity">Humidity</option>' ;
  // PID
  if ($type == 'pid')
    $final_sensor_type_select .= '<option value="pid" selected>PID</option>' ;
  else
    $final_sensor_type_select .= '<option value="pid">PID</option>' ;
  
  $final_sensor_type_select .= '</select>' ;
  
  
  return $final_sensor_type_select ;
  
} // buildSensorTypeSelectInput

// Builds and returns the Company list as Input Select
function buildTimeRangeSelectInput($time_range=false)
{
  $time_range_input_select = '<select class="time_range">' ;
  
  // 1 Hour
  if ($time_range == '1 hour')
    $time_range_input_select .= '<option value="1 hour" selected>1 Hour</option>' ;
  else
    $time_range_input_select .= '<option value="1 hour">1 Hour</option>' ;
  // 8 Hour
  if ($time_range == '8 hours')
    $time_range_input_select .= '<option value="8 hours" selected>8 Hours</option>' ;
  else
    $time_range_input_select .= '<option value="8 hours">8 Hours</option>' ;
  // 24 hours
  if ($time_range == '24 hours')
    $time_range_input_select .= '<option value="24 hours" selected>24 Hours</option>' ;
  else
    $time_range_input_select .= '<option value="24 hours">24 Hours</option>' ;
  // 48 Hours
  if ($time_range == '48 hours')
    $time_range_input_select .= '<option value="48 hours" selected>48 Hours</option>' ;
  else
    $time_range_input_select .= '<option value="48 hours">48 Hours</option>' ;
  // 72 Hours
  if ($time_range == '72 hours')
    $time_range_input_select .= '<option value="72 hours" selected>72 Hours</option>' ;
  else
    $time_range_input_select .= '<option value="72 hours">72 Hours</option>' ;
  // 1 Week
  if ($time_range == '1 week')
    $time_range_input_select .= '<option value="1 week" selected>1 Week</option>' ;
  else
    $time_range_input_select .= '<option value="1 week">1 Week</option>' ;
  // 2 Weeks
  if ($time_range == '2 weeks')
    $time_range_input_select .= '<option value="2 weeks" selected>2 Weeks</option>' ;
  else
    $time_range_input_select .= '<option value="2 weeks">2 Weeks</option>' ;
  // 1 Month
  if ($time_range == '1 month')
    $time_range_input_select .= '<option value="1 month" selected>1 Month</option>' ;
  else
    $time_range_input_select .= '<option value="1 month">1 Month</option>' ;
  // 3 Months
  if ($time_range == '3 months')
    $time_range_input_select .= '<option value="3 months" selected>3 Months</option>' ;
  else
    $time_range_input_select .= '<option value="3 months">3 Months</option>' ;
  // 6 Months
  if ($time_range == '6 months')
    $time_range_input_select .= '<option value="6 months" selected>6 Months</option>' ;
  else
    $time_range_input_select .= '<option value="6 months">6 Months</option>' ;
  // 1 Year
  if ($time_range == '1 year')
    $time_range_input_select .= '<option value="1 year" selected>1 Year</option>' ;
  else
    $time_range_input_select .= '<option value="1 year">1 Year</option>' ;
  // Ever
  if ($time_range == 'ever')
    $time_range_input_select .= '<option value="ever" selected>Ever</option>' ;
  else
    $time_range_input_select .= '<option value="ever">Ever</option>' ;
  
  
  $time_range_input_select .= '</select>' ;
  
  return $time_range_input_select ;
} // buildTimeRangeSelectInput

// Builds and returns the Company list as Input Select
function buildCompanyInputSelect($company=false)
{
  // Build the top input bar
  $company_input_select = "" ;
  
  // Connect to db
  $dbc = db_connect_sims() ;
  
  // Get all the Scentinals and the respective company name and build the top input select field
	$query = "SELECT company.id AS company_id, company.name AS company_name, company.city
            FROM company 
            ORDER BY company.id ASC" ;
	$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc)) ;
	if (@mysqli_num_rows($result) != 0) 
  {
    $company_input_select .= "<select id=\"company\" class=\"input_select\" name=\"company\">" ;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
    {
      $company_id = $row['company_id'] ;
      $company_name = $row['company_name'] ;
      $city = $row['city'] ;
      
      // Build the option fields
      if ($company_id == $company)
        $company_input_select .= "<option value=\"" . $company_id . "\" selected>" . $company_name . " -:- (" . $city . ")</option>";
      else
        $company_input_select .= "<option value=\"" . $company_id . "\">" . $company_name . " -:- (" . $city .  ")</option>";

		}
    $company_input_select .= "</select>" ;
	}
  
  // Close db
  db_close($dbc) ;
  
  return $company_input_select ;
} // buildCompanyInputSelect


// Builds and returns the Top Input Fields for Companies (Dates, Sensors Update buttons etc...)
function buildCompanyTopInputFields($company)
{
  // Build the top input bar
  $company_top_input = "" ;
  
  // Connect to db
  $dbc = db_connect_sims() ;
  $dbc_local = db_connect_local() ;
  
  // Get all the Scentinals and the respective company name and build the top input select field
	$query = "SELECT company.id AS company_id, company.name AS company_name, company.city
            FROM company 
            ORDER BY company.id DESC" ;
	$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc)) ;
	if (@mysqli_num_rows($result) != 0) 
  {
    $company_top_input .= "<form method=\"get\" action=\"index.php\">
                  <label>Company:</label>
                  <select class=\"input_select\" name=\"company\">" ;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
    {
      $company_id = $row['company_id'] ;
      $company_name = $row['company_name'] ;
      $city = $row['city'] ;
      
      // Filter companies: If no Sample data don't put it in input fields!
      $query2 = "SELECT lastvalue_equipment.id AS lastvalue_id, sampledat 
               FROM lastvalue_equipment
               WHERE company_id = " . $company_id
             . " AND sampledat IS NOT NULL" ;
      $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
      if (@mysqli_num_rows($result2) != 0) 
      {
        // Build the option fields
        if ($company_id == $company)
          $company_top_input .= "<option value=\"" . $company_id . "\" selected>" . $company_name . " -:- (" . $city . ")</option>";
        else
          $company_top_input .= "<option value=\"" . $company_id . "\">" . $company_name . " -:- (" . $city .  ")</option>";
      }

		}
    $company_top_input .= "</select>" ;
	}
  
  $company_top_input .= "
                  <button class=\"button_update\">Update</button></form>" ;
  
  // Close db
  db_close($dbc) ;
  db_close($dbc_local) ;
  
  return $company_top_input ;
} // buildCompanyTopInputFields 

// Builds and returns the Company list for dropdown menu
function buildCompanyListDropDown()
{
  // Connect to db
  $dbc = db_connect_sims() ;
  $dbc_local = db_connect_local() ;
  
  // Build the top input bar
  $company_list_dropdown = "" ;
  
  
  // Get all the Scentinals and the respective company name and build the top input select field
	$query = "SELECT company.id AS company_id, company.name AS company_name, company.city
            FROM company 
            ORDER BY company.id DESC" ;
	$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc)) ;
	if (@mysqli_num_rows($result) != 0) 
  {
    $company_list_dropdown .= "<ul>" ;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
    {
      $company_id = $row['company_id'] ;
      $company_name = $row['company_name'] ;
      $city = $row['city'] ;
      
      // Filter companies: If no Sample data don't put it in input fields!
      $query2 = "SELECT lastvalue_equipment.id AS lastvalue_id, sampledat 
               FROM lastvalue_equipment
               WHERE company_id = " . $company_id
             . " AND sampledat IS NOT NULL" ;
      $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
      if (@mysqli_num_rows($result2) != 0) 
      {
        // Build the option fields
        $company_list_dropdown .= '<li><a href="http://207.246.86.177/index.php?company=' . $company_id . '"  title="">' . $company_name . '</a></li>' ;
      }

		}
	}
  
  $company_list_dropdown .= "</ul>" ;
  
  
  // Close db
  db_close($dbc) ;
  db_close($dbc_local) ;
  
  return $company_list_dropdown ;
} // buildCompanyListDropDown

// Builds and returns the Top Input Fields for the Analysis page (Dates, Sensors Update buttons etc...)
function buildEquipmentTopInputFieldsForAnalysis($equipment, $date_range, $begin_date, $end_date)
{
  // Build the top input bar
  $equipment_top_input = "" ;
  
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
    $equipment_top_input .= "<form method=\"get\" action=\"index.php\">
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
        $equipment_top_input .= "<option value=\"" . $equipement_id . "\" selected>" . $equipement_name . "</option>";
      else
        $equipment_top_input .= "<option value=\"" . $equipement_id . "\">" . $equipement_name .  "</option>";
		}
    $equipment_top_input .= "</select>" ;
	}
  
  $date_interval = buildIntervalDateSelectInput($dates_submitted, $date_range) ;
  
  $equipment_top_input .= "
                  &emsp;<label>Range:</label> $date_interval
                  &emsp; <label>Begin Date:</label><input type=\"text\" id=\"begin_date\" class=\"input_date\" name=\"begin_date\" value=\"$begin_date\" />  
                  <label>End Date:</label><input type=\"text\" id=\"end_date\" class=\"input_date\" name=\"end_date\" value=\"$end_date\" />
                  <button class=\"button_update\">Update</button></form>" ;
                  
  // Close db
  db_close($dbc) ;
  
  return $equipment_top_input ;
} // buildEquipmentTopInputFieldsForAnalysis

// Builds and returns the Top Input Fields (Dates, Sensors Update buttons etc...)
function buildEquipmentTopInputFields($equipment, $date_range, $begin_date, $end_date)
{
  // Build the top input bar
  $equipment_top_input = "" ;
  
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
    $equipment_top_input .= "<form method=\"get\" action=\"index.php\">
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
        $equipment_top_input .= "<option value=\"" . $equipement_id . "\" selected>" . $equipement_name . " -:- " . $company_name . "</option>";
      else
        $equipment_top_input .= "<option value=\"" . $equipement_id . "\">" . $equipement_name . " -:- " . $company_name .  "</option>";

		}
    $equipment_top_input .= "</select>" ;
	}
  
  $date_interval = buildIntervalDateSelectInput($dates_submitted, $date_range) ;
  
  $equipment_top_input .= "
                  &emsp;<label>Last:</label> $date_interval
                  &emsp; <label>Begin Date:</label><input type=\"text\" id=\"begin_date\" class=\"input_date\" name=\"begin_date\" value=\"$begin_date\" />  
                  <label>End Date:</label><input type=\"text\" id=\"end_date\" class=\"input_date\" name=\"end_date\" value=\"$end_date\" />
                  <button class=\"button_update\">Update</button></form>" ;
                  
  // Close db
  db_close($dbc) ;
  
  return $equipment_top_input ;
} // buildEquipmentTopInputFields 


// Builds and returns the Interval Dates as a select input
function buildIntervalDateSelectInput($dates_submitted, $date_range= false)
{
  // Build the top input bar
  $select_interval = "<select class=\"input_select date_range\" name=\"date_range\">" ;
  
  // Hardcode it for now
  $select_interval .= "<option value=\"\">----</option>";

  if ($date_range == "current")
    $select_interval .= "<option value=\"current\" selected>Current</option>";
  else if (!$date_range && !$dates_submitted)
    $select_interval .= "<option value=\"current\" selected>Current</option>";
  else
    $select_interval .= "<option value=\"current\">Current</option>";

  if ($date_range == "24 hours")
    $select_interval .= "<option value=\"24 hours\" selected>24 Hours</option>";
  else
    $select_interval .= "<option value=\"24 hours\">24 Hours</option>";
  
  if ($date_range == "48 hours")
    $select_interval .= "<option value=\"48 hours\" selected>48 Hours</option>";
  else
    $select_interval .= "<option value=\"48 hours\">48 Hours</option>";
  
  if ($date_range == "72 hours")
    $select_interval .= "<option value=\"72 hours\" selected>72 Hours</option>";
  else
    $select_interval .= "<option value=\"72 hours\">72 Hours</option>";
  
  if ($date_range == "1 week")
    $select_interval .= "<option value=\"1 week\" selected>1 Week</option>";
  else
    $select_interval .= "<option value=\"1 week\">1 Week</option>";
  
  if ($date_range == "2 weeks")
    $select_interval .= "<option value=\"2 weeks\" selected>2 Weeks</option>";
  else
    $select_interval .= "<option value=\"2 weeks\">2 Weeks</option>";
  
  if ($date_range == "1 month")
    $select_interval .= "<option value=\"1 month\" selected>1 Month</option>";
  else
    $select_interval .= "<option value=\"1 month\">1 Month</option>";
  
  if ($date_range == "3 months")
    $select_interval .= "<option value=\"3 months\" selected>3 Months</option>";
  else
    $select_interval .= "<option value=\"3 months\">3 Months</option>";
  /*
  if ($date_range == "6 months")
    $select_interval .= "<option value=\"6 months\" selected>6 Months</option>";
  else
    $select_interval .= "<option value=\"6 months\">6 Months</option>";

  if ($date_range == "1 year")
    $select_interval .= "<option value=\"1 year\" selected>1 Year</option>";
  else
    $select_interval .= "<option value=\"1 year\">1 Year</option>";

  if ($date_range == "2 years")
    $select_interval .= "<option value=\"2 years\" selected>2 Years</option>";
  else
    $select_interval .= "<option value=\"2 years\">2 Years</option>";
  
  if ($date_range == "5 years")
    $select_interval .= "<option value=\"All\" selected>All</option>"; 
  else
    $select_interval .= "<option value=\"All\">All</option>"; 
  
  */
  
  $select_interval .= "</select>" ;

  return $select_interval ;
} // buildIntervalDateSelectInput 

?>