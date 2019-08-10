<?php

// Main libraries for Data processing features

// Build the Equipments table for Settings page
function buildEquipmentsTable($company)
{
  
  // Connect to db
  $dbc = db_connect_sims() ;
  $dbc_local = db_connect_local() ;
  
  $final_equipment_table = '' ;
  $equipments_table_header  = '<table id="equipments" class="settings_table">
  <thead><tr>
  <th>Name</th>
  <th>Serial Number</th>
  <th>Company</th>
  <th>Status</th>
  <th>Creation</th>
  <th>Calibration</th>
  <th>Actions</th>
  <tr></thead>
  <tbody>' ;
  
  $equipments_table_footer  = '<tr><td colspan="6"></td><td><span class="button button_add add_equipment">Add Equipment</span></td></tr></tbody></table>'
  . '<div id="settings_equipment_container"></div>'  ;
  
  // Select the Users that belongs to the company
  $query = "SELECT equipement.id AS equipment_id, equipement.name AS equipment_name, sn
            , equipement.status, company.name AS company_name, equipement.calibratedate, equipement.createdat
        FROM equipement
        INNER JOIN company ON equipement.company = company.id
        WHERE company.id = " . $company ;
  $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
	if (@mysqli_num_rows($result) != 0) 
  {
    $equipments_table_body = '' ;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
    {
      $equipments_table_body .= '<tr equipment_id="' . $row['equipment_id'] . '"><td>' . $row['equipment_name'] . '</td>
      <td>' . $row['sn'] . '</td>
      <td>' . $row['company_name'] . '</td>
      <td>' . $row['status'] . '</td>
      <td>' . date('Y-m-d H:i:s', $row['createdat']) . '</td>
      <td>' . date('Y-m-d H:i:s', $row['calibratedate']) . '</td><td><span class="button button_edit edit_equipment">Edit</span><span class="button button_delete delete_equipment">Delete</span><span class="button button_view view_sensors">Sensors</span></td></tr>' ;
    }
    
    $final_equipment_table = $equipments_table_header . $equipments_table_body . $equipments_table_footer ;
  
  }
  
  //$final_equipment_table = $equipments_table_header . $equipments_table_body . $equipments_table_footer ;
  
  return $final_equipment_table ;
  
} // buildEquipmentsTable

// Build the Equipment info for Editing/Add New
function buildEquipmentEdit($equipment_id=false)
{
  // Connect to db
  $dbc = db_connect_sims() ;
  
  // If no user ID build 
  // Make the Company's info editable
  if (!$equipment_id)
  {
    
    $final_equip_info = '<fieldset>
    <legend>Equipment Information:</legend>
    <div class="general_input">'
    . '<dl>'
    . '<dt>Name:</dt><dd><input id="name" class="text_field" name="name" value="" type="text"></dd>'
    . '<dt>Serial Number:</dt><dd><input id="sn" class="text_field" name="family" value="" type="text"></dd>'
    . '<dt>Status:</dt><dd><input name="status" value="active" type="radio" checked="checked"> Active
       <input name="status" value="deactive" type="radio"> Inactive</dd>'
    . '<dt>Calibration Date:</dt><dd><input type="text" id="calibrate_date" class="input_date" name="calibrate_date" value="" /></dd>'
    . '<dt>Company:</dt><dd>' . buildCompanyInputSelect() . '</dd>'
    . '<div id="equipment_container"></div>'
    . '<dt>&nbsp;</dt><dd><span class="big_button btn_save_equipment">SAVE</span></dd>'
    . '</dl>'
    . '</div>'
    . '</fieldset>' ;
  }
  else
  {
    
    // Get the Equipment's info from SIMS1
    $query = "SELECT equipement.id AS equipement_id, equipement.name, status, sn, company.id, calibratedate    
          FROM equipement
          INNER JOIN company ON company.id = equipement.company
          WHERE equipement.id = " . $equipment_id ; // Can also be done
    $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
    $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
    //$equipment_id = $row[0];
    $name = $row[1];
    $sn = $row[3];
    $company_id = $row[4];
    $calibratedate = date('Y-m-d H:i:s', $row[5]);
    
    $status = $row[2];
    $acheck = '' ; $dcheck = '' ;
    if ($status == 'active')
      $acheck = 'checked="checked"' ;
    elseif ($status == 'deactive')
      $dcheck = 'checked="checked"' ;
    
    $final_equip_info = '<fieldset>
    <legend>Equipment Information:</legend>
    <div class="general_input">'
    . '<dl>'
    . '<dt>Name:</dt><dd><input id="name" class="text_field" name="name" value="' . $name . '" type="text"></dd>'
    . '<dt>Serial Number:</dt><dd><input id="sn" class="text_field" name="sn" value="' . $sn . '" type="text"></dd>'
    . '<dt>Status:</dt><dd><input name="status" value="active" type="radio" ' . $acheck . '> Active
       <input name="status" value="deactive" type="radio" ' . $dcheck . '> Inactive</dd>'
    . '<dt>Calibration Date:</dt><dd><input type="text" id="calibrate_date" class="input_date" name="calibrate_date" value="' . $calibratedate . '" /></dd>'
    . '<dt>Company:</dt><dd>' . buildCompanyInputSelect($company_id) . '</dd>'
    . '<div id="equipment_container"></div>'
    . '<dt>&nbsp;</dt><dd><span equipment_id="' . $equipment_id . '" class="big_button btn_save_equipment">SAVE</span></dd>'
    . '</dl>'
    . '</div>'
    . '</fieldset>' ;
  }
  
  // If user ID, Fill in the blanks
  db_close($dbc) ;
  
  return $final_equip_info ;
  
} // buildEquipmentEdit

// Build the User info for Editing/Add New
function buildUserEdit($user_id=false)
{
  // Connect to db
  $dbc = db_connect_sims() ;
  
  // If no user ID build 
  // Make the Company's info editable
  if (!$user_id)
  {
    
    $final_user_info = '<fieldset>
    <legend>User Information:</legend>
    <div class="general_input">'
    . '<dl>'
    . '<dt>Name:</dt><dd><input id="name" class="text_field" name="name" value="" type="text"></dd>'
    . '<dt>Family:</dt><dd><input id="family" class="text_field" name="family" value="" type="text"></dd>'
    . '<dt>Gender:</dt><dd><input id="gender" name="gender" value="m" type="radio" checked="checked"> Male
       <input id="gender" name="gender" value="f" type="radio"> Female</dd>'
    . '<dt>Telephone:</dt><dd><input id="phone" class="text_field" name="phone" value="" type="text"></dd>'
    . '<dt>Email:</dt><dd><input id="email" class="text_field" name="email" value="" type="text"></dd>'
    . '<dt>Password:</dt><dd><input id="password" class="text_field" name="password" value="" type="password"></dd>'
    . '<dt>Company:</dt><dd>' . buildCompanyInputSelect() . '</dd>'
    . '<dt>Role:</dt><dd><select id="role"><option value="manager">Manager</option><option value="user">User</option></select></dd>'
    . '<div id="user_container"></div>'
    . '<dt>&nbsp;</dt><dd><span class="big_button btn_save_user">SAVE</span></dd>'
    . '</dl>'
    . '</div>'
    . '</fieldset>' ;
  }
  else
  {
    
    // Get the User's info from SIMS1
    $query = "SELECT user.id AS user_id, email, tel, name, family, company, gender, 
              roles
          FROM user
          WHERE user.id = " . $user_id ; // Can also be done
    $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
    $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
    //$user_id = $row[0];
    $email = $row[1];
    $phone = $row[2];
    $name = $row[3];
    $family = $row[4];
    $company_id = $row[5];
    
    $gender = $row[6];
    $mcheck = '' ; $fcheck = '' ;
    if ($gender == 'm')
      $mcheck = 'checked="checked"' ;
    elseif ($gender == 'f')
      $fcheck = 'checked="checked"' ;
    
    $role = $row[7];
    $mselect = '' ; $uselect = '' ;
    if (strpos($role, 'user') > 0)
      $uselect = 'selected' ;
    elseif (strpos($role, 'manager') > 0)
      $mselect = 'selected' ;
    
    $final_user_info = '<fieldset>
    <legend>User Information:</legend>
    <div class="general_input">'
    . '<dl>'
    . '<dt>Name:</dt><dd><input id="name" class="text_field" name="name" value="' . $name . '" type="text"></dd>'
    . '<dt>Family:</dt><dd><input id="family" class="text_field" name="family" value="' . $family . '" type="text"></dd>'
    . '<dt>Gender:</dt><dd><input id="gender" name="gender" value="m" type="radio" ' . $mcheck . '> Male
       <input id="gender" name="gender" value="f" type="radio" ' . $fcheck . '> Female</dd>'
    . '<dt>Telephone:</dt><dd><input id="phone" class="text_field" name="phone" value="' . $phone . '" type="text"></dd>'
    . '<dt>Email:</dt><dd><input id="email" class="text_field" name="email" value="' . $email . '" type="text"></dd>'
    . '<dt>New Password:</dt><dd><input id="password" class="text_field" name="password" value="" type="password"></dd>'
    . '<dt>Company:</dt><dd>' . buildCompanyInputSelect($company_id) . '</dd>'
    . '<dt>Role:</dt><dd><select id="role"><option value="manager" ' . $mselect . '>Manager</option><option value="user" ' . $uselect . '>User</option></select></dd>'
    . '<div id="user_container"></div>'
    . '<dt>&nbsp;</dt><dd><span user_id="' . $user_id . '" class="big_button btn_save_user">SAVE</span></dd>'
    . '</dl>'
    . '</div>'
    . '</fieldset>' ;
  }
  
  // If user ID, Fill in the blanks
  db_close($dbc) ;
  
  return $final_user_info ;
  
} // buildUserEdit

// Build the User's company info for Editing
function buildCompanyInfoDiv($company)
{
  // Connect to db
  $dbc = db_connect_sims() ;
  $dbc_local = db_connect_local() ;
  
  $query2 = "SELECT settings.id AS setting_id, settings_json
        FROM settings
        WHERE user_id = " . $_SESSION['id'] ;
  $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local));
  $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
  if (@mysqli_num_rows($result2) != 0) 
  {
    $settings_json = $row2[1] ;
    $settings_obj = json_decode($settings_json) ;

    // Get just the AQI data
    if (isset($settings_obj->company))
      $company_obj = $settings_obj->company ;
    else
      $company_obj = false ;
  }
  else
    $company_obj = false ;
  
  $final_company_info = '' ;
  
  if ($company_obj)
  {
    // Make the Company's info editable
    $final_company_info = '<div class="company">'
    . '<dl>'
    . '<dt>Company Name:</dt><dd><input id="company_name" class="text_field" name="company_name" value="' . $company_obj->company_name . '" type="text"></dd>'
    . '<dt>City:</dt><dd><input id="city" class="text_field" name="city" value="' . $company_obj->city . '" type="text"></dd>'
    . '<dt>Timezone:</dt><dd>' . buildTimezoneSelectInput($company_obj->timezones[0],$company_obj->timezones[1]) . '</dd>'
    . '<dt>Alarm Email:</dt><dd><input id="alarm_email" class="text_field" name="alarm_email" value="' . $company_obj->alarm_email . '" type="text"></dd>'
    . '<dt>Address:</dt><dd><textarea id="address" row="3" cols="30">' . $company_obj->address . '</textarea></dd>'
    . '</dl>'
    . '</div>' ;
  }
  else
  {
    // Get the User's Company info from SIMS1
    $query = "SELECT company.id AS company_id, company.name AS company_name, 
              city, timezone, address, alarm_email
          FROM company
          WHERE company.id = " . $company ; // Can also be done
    $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
    $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
    $company_id = $row[0];
    $company_name = $row[1];
    $city = $row[2];
    $timezone = buildTimezoneSelectInput($row[3]);
    $address = $row[4];
    $alarm_email = $row[5];
    
    
    $final_company_info = '<div class="company">'
    . '<dl>'
    . '<dt>Company Name:</dt><dd><input id="company_name" class="text_field" name="company_name" value="' . $company_name . '" type="text"></dd>'
    . '<dt>City:</dt><dd><input id="city" class="text_field" name="city" value="' . $city . '" type="text"></dd>'
    . '<dt>Timezone:</dt><dd>' . $timezone . '</dd>'
    . '<dt>Alarm Email:</dt><dd><input id="alarm_email" class="text_field" name="alarm_email" value="' . $alarm_email . '" type="text"></dd>'
    . '<dt>Address:</dt><dd><textarea id="address" row="3" cols="30">' . $address . '</textarea></dd>'
    . '</dl>'
    . '</div>' ;
  }
  
  // Close db
  db_close($dbc_local) ;
  db_close($dbc) ;
  
  return $final_company_info ;
  
} // buildCompanyInfoDiv

// Build the Users Table in Settings page
function buildUsersTable($company)
{
  
  // Connect to db
  $dbc = db_connect_sims() ;
  $dbc_local = db_connect_local() ;
  
  $final_users_list = '' ;
  $users_table_header  = '<table id="users" class="settings_table">
  <thead><tr>
  <th>Name</th>
  <th>Email</th>
  <th>Company</th>
  <th>Family</th>
  <th>Telephone</th>
  <th>Role</th>
  <th>Actions</th>
  <tr></thead>
  <tbody>' ;
  
  $users_table_footer  = '<tr><td colspan="6"></td><td colspan="2"><span class="button button_add add_user">Add User</span></td></tr></tbody></table>'
  . '<div id="settings_user_container"></div>'  ;
  
  // Select the Users that belongs to the company
  $query = "SELECT user.id AS user_id, user.name AS user_name, email
            , user.tel, user.roles, user.family, company.name AS company_name
        FROM user
        INNER JOIN company ON user.company = company.id
        WHERE company.id = " . $company ;
  $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
	if (@mysqli_num_rows($result) != 0) 
  {
    $users_table_body = '' ;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
    {
      $users_table_body .= '<tr user_id="' . $row['user_id'] . '"><td>' . $row['user_name'] . '</td>
      <td>' . $row['email'] . '</td>
      <td>' . $row['company_name'] . '</td>
      <td>' . $row['family'] . '</td>
      <td>' . $row['tel'] . '</td>
      <td>' . $row['roles'] . '</td><td><span class="button button_edit edit_user">Edit</span><span class="button button_delete delete_user">Delete</span></td></tr>' ;
    }
    
    $final_users_list .= $users_table_header . $users_table_body . $users_table_footer ;
  
  }
  
  
  // Close db
  db_close($dbc_local) ;
  db_close($dbc) ;
  
  return $final_users_list ;
  
} // buildUsersTable

// Build the Concentration to AQI table for each unique GAS (per chemical formula) / all equipments combined
function buildSensorAQITable($company)
{
  // Connect to db
  $dbc = db_connect_sims() ;
  $dbc_local = db_connect_local() ;
  
  $query2 = "SELECT settings.id AS setting_id, settings_json
        FROM settings
        WHERE user_id = " . $_SESSION['id'] ;
  $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local));
  $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
  if (@mysqli_num_rows($result2) != 0) 
  {
    $settings_json = $row2[1] ;
    $settings_obj = json_decode($settings_json) ;

    // Get just the AQI data
    $aqi_obj = $settings_obj->aqi ;
  }
  else
    $aqi_obj = false ;
    
  
  $final_aqi_table = '' ;
  $aqi_array = array() ;
  
  $aqi_table_header = '<table id="aqi"><thead><tr>
  <th colspan="2">AQI</th>
  <th colspan="2" style="background:#00e400;">Good</th>
  <th colspan="2" style="background:#ff0;">Moderate</th>
  <th colspan="2" style="background:#ff7e00;">Sensitive</th>
  <th colspan="2" style="background:#f00; color: #fff;">Unhealthy</th>
  <th colspan="2" style="background:#99004c; color: #fff;">Very Unhealthy</th>
  <th colspan="2"style="background:#7e0023; color: #fff;">Hazardous</th><th></th></tr>
  <tr>
  <th>Sensor</th><th>Average</th>
  <th style="background:#00e400;">0</th>
  <th style="background:#00e400;">50</th>
  <th style="background:#ff0;">51</th>
  <th style="background:#ff0;">100</th>
  <th style="background:#ff7e00;">101</th>
  <th style="background:#ff7e00;">150</th>
  <th style="background:#f00; color: #fff;">151</th>
  <th style="background:#f00; color: #fff;">200</th>
  <th style="background:#99004c; color: #fff;">201</th>
  <th style="background:#99004c; color: #fff;">300</th>
  <th style="background:#7e0023; color: #fff;">301</th>
  <th style="background:#7e0023; color: #fff;">500</th>
  <th>Unit</th></tr></thead>
  <tbody>' ;
  
  $aqi_table_footer = '  <tr><td colspan="15">&nbsp;</td></tr>
  </tbody>
  </table>' ;
  

  // If no saved data just display the default input fields
  $aqi_table_content = '<td><select class="time_average">
  <option value="1">1 Hour</option>
  <option value="8">8 Hours</option>
  <option value="24" selected>24 Hours</option></select></td>
  <td>0</td>
  <td><input type="number" step="0.01" cat="good"></td><td class="good"></td>
  <td><input type="number" step="0.01" cat="moder"></td><td class="moder"></td>
  <td><input type="number" step="0.01" cat="sensi"></td><td class="sensi"></td>
  <td><input type="number" step="0.01" cat="unhea"></td><td class="unhea"></td>
  <td><input type="number" step="0.01" cat="very"></td><td class="very"></td>
  <td><input type="number" step="0.01" cat="hazar"></td>' ;
  
  // Select all the sensors (unique) from all equipments belonging to one Company
  //$formula = shortenSensorName($sensor_name, true) ;
  $query = "SELECT sensor.id AS sensor_id, sensor.name AS sensor_name, sensor.dataunit
        FROM sensor
        INNER JOIN equipement ON sensor.equipement = equipement.id
        INNER JOIN company ON equipement.company = company.id
        WHERE company.id = " . $company ;
  $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
	if (@mysqli_num_rows($result) != 0) 
  {
    $final_aqi_table = '' ;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
    {
      $data_unit = strtolower(trim($row['dataunit'])) ;
      if (strtolower($data_unit) == 'ppm' || strtolower($data_unit) == 'ppb' || strtolower($data_unit) == 'ug/m3')
      {
      
        // Get the sensor name and transform them into Chemical Formula
        // Avoid compound repetition
        //$sensor_formula = shortenSensorName($row['sensor_name'], true) ;
        
        // Avoid special characters  // Beware of the special characters
        $sensor_formula = $row['sensor_name'] ;
        $sensor_formula = utf8_encode($sensor_formula) ;
        $sensor_formula0 = preg_replace('/\s+/', ' ',$sensor_formula) ;
        $sensor_formula = shortenSensorName($sensor_formula0, true) ;
        if (strlen($sensor_formula) == 0)
          $sensor_formula = $sensor_formula0 ;
        
        // If the chemical already registered in the array don't add it
        if (array_key_exists($sensor_formula, $aqi_array))
        {
          // Increment the chemical compound occurence
          $aqi_array[$sensor_formula] += 1 ;
        }
        else
        {
          // Register the chemical compound
          $aqi_array[$sensor_formula] = 1 ;
          //if (strlen($final_aqi_table) != 0)
            //$final_aqi_table .= '; ' ;
          
          // Check if the Chemical formula has its AQI already been saved in Settings
          // If yes, fill the blanks in
          if ($aqi_obj)
          {
            $temp_aqi_table = '<tr chemical="' . $sensor_formula . '"><td>' . $sensor_formula . '</td>' . $aqi_table_content 
            . '<td><input type="text" class="data_unit" size="5" value="' . $data_unit 
            . '" class="field left" readonly="readonly"></td></tr>' ;
            
            foreach ($aqi_obj as $aqi)
            {
              if ($aqi->chemical == $sensor_formula)
              {
                // param(0) -> Time avg
                $timer1 = '' ; $timer8 = '' ; $timer24 = '' ; 
                if ($aqi->parameters[0] == 1)
                  $timer1 = ' selected' ;
                elseif ($aqi->parameters[0] == 8)
                  $timer8 = ' selected' ;
                elseif ($aqi->parameters[0] == 24)
                  $timer24 = ' selected' ;
                // param(1) -> Data Unit
                $temp_aqi_select = "<td><select class=\"time_average\">
  <option value=\"1\"$timer1>1 Hour</option>
  <option value=\"8\"$timer8>8 Hours</option>
  <option value=\"24\"$timer24>24 Hours</option></select></td>" ;
                
                // Param(2 to 7) -> AQI concentration
                $temp_aqi_input = '<tr chemical="' . $aqi->chemical . '"><td>' . $aqi->chemical . '</td>' 
                . $temp_aqi_select
                . '<td>0</td>
                   <td><input type="number" step="0.01" value="' . $aqi->parameters[2] . '" cat="good"></td><td class="good"></td>
                   <td><input type="number" step="0.01" value="' . $aqi->parameters[3] . '" cat="moder"></td><td class="moder"></td>
                   <td><input type="number" step="0.01" value="' . $aqi->parameters[4] . '" cat="sensi"></td><td class="sensi"></td>
                   <td><input type="number" step="0.01" value="' . $aqi->parameters[5] . '" cat="unhea"></td><td class="unhea"></td>
                   <td><input type="number" step="0.01" value="' . $aqi->parameters[6] . '" cat="very"></td><td class="very"></td>
                   <td><input type="number" step="0.01" value="' . $aqi->parameters[7] . '" cat="hazar"></td>'
                . '<td><input type="text" class="data_unit" size="5" value="' . $aqi->parameters[1] 
                . '" class="field left" readonly="readonly"></td></tr>' ;
                
                $temp_aqi_table = $temp_aqi_input ;
              }
            }
            $final_aqi_table .= $temp_aqi_table ;
          }
          else
          {
            $final_aqi_table .= '<tr chemical="' . $sensor_formula . '"><td>' . $sensor_formula . '</td>' . $aqi_table_content 
            . '<td><input type="text" class="data_unit" size="5" value="' . $data_unit 
            . '" class="field left" readonly="readonly"></td></tr>' ;
          }
        }
      }
    }
  
    $final_aqi_table = $aqi_table_header . $final_aqi_table . $aqi_table_footer ;
  }
  
  
  // Close db
  db_close($dbc_local) ;
  db_close($dbc) ;
  
  return $final_aqi_table ;
} // buildSensorAQITable

// Build the Concentration to AQI table for each unique GAS (per chemical formula) / all equipments combined
function buildMetNameList()
{
  // Connect to db
  $dbc = db_connect_sims() ;
  $dbc_local = db_connect_local() ;
  
  $final_met_array = array() ;
  $iter = 0;
  
  // Select all the sensors (unique) from all equipments belonging to one Company
  //$formula = shortenSensorName($sensor_name, true) ;
  $query = "SELECT sensor.id AS sensor_id, sensor.name AS sensor_name, sensor.dataunit
        FROM sensor
        INNER JOIN equipement ON sensor.equipement = equipement.id
        INNER JOIN company ON equipement.company = company.id" ;
        //WHERE company.id = " . $company ;
  $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
	if (@mysqli_num_rows($result) != 0) 
  {
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
    {
      $data_unit = strtolower(trim($row['dataunit'])) ;
      if (strtolower($data_unit) != 'ppm' && strtolower($data_unit) != 'ppb' && strtolower($data_unit) != 'ou'
        && strtolower($data_unit) != 'ug/m3' && strtolower($data_unit) != 'ou/m3')
      {
      
        // Get the sensor name and transform them into Chemical Formula
        // Avoid compound repetition
        //$sensor_formula = shortenSensorName($row['sensor_name'], true) ;
        
        // Avoid special characters  // Beware of the special characters
        $sensor_formula = $row['sensor_name'] ;
        $sensor_formula = utf8_encode($sensor_formula);
        $sensor_formula0 = preg_replace('/\s+/', ' ',$sensor_formula);
        $sensor_formula = shortenSensorName($sensor_formula0, true) ;
        if (strlen($sensor_formula) == 0)
          $sensor_formula = $sensor_formula0 ;
        
        if ($sensor_formula != 'external input 1' && $sensor_formula != 'external input 2' && $sensor_formula != 'External Input 1' && $sensor_formula != 'External Input 2')
        {
          $final_met_array[$iter] = $sensor_formula ;
          $iter++ ;
        }
      }
    }
  
  }
  
  
  // Close db
  db_close($dbc_local) ;
  db_close($dbc) ;
  
  return $final_met_array ;
} // buildMetNameList

// AQI for each compound that are relevant in the last 1Hour/8Hours/24Hours
function calculatePollutantAQI($pollutant, $data_unit, $concentration, $time_avg)
{
  $aqi = false ;
  
  $pollutant = trim(strtolower($pollutant)) ;
  $data_unit = trim(strtolower($data_unit)) ;
  
  // Based on the relevant pollutant calculate AQI
  if ($pollutant == 'o3') // Ozone | O3 (1Hour Average, PPB) || Ozone | O3 (8Hour Average, PPB)
  {
    // If data Unit different from PPB recalculate
    if ($data_unit == 'ppm')
      $concentration = $concentration * 1000; // PPB
    else if ($data_unit == 'ug/m3')
    {
      $molecular_weight = 3 * 16 ; // g/mol
      $concentration = 24.45 * $concentration / $molecular_weight ; // PPB
    }
    
    // Now use the Time Average to calculate the AQI
    if ($time_avg == 1) // Time Average 1Hour
    {
      // From concentration in PPB get the concentration low and high for that concentration
      if ($concentration < 125)
      {
        $aqi = false ;
      }
      elseif ($concentration >= 125 && $concentration <= 164) // 125-164 (1-hr)
      {
        $c_low = 125 ; $c_high = 164 ; $i_low = 101 ; $i_high = 150 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 165 && $concentration <= 204) // 165-204 (1-hr)
      {
        $c_low = 165 ; $c_high = 204 ; $i_low = 151 ; $i_high = 200 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 205 && $concentration <= 404) // 205-404 (1-hr)
      {
        $c_low = 205 ; $c_high = 404 ; $i_low = 201 ; $i_high = 300 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 405 && $concentration <= 504) // 405-504 (1-hr)
      {
        $c_low = 405 ; $c_high = 504 ; $i_low = 301 ; $i_high = 400 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 505 && $concentration <= 604) // 505-604 (1-hr)
      {
        $c_low = 505 ; $c_high = 604 ; $i_low = 401 ; $i_high = 500 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      else
      {
        $aqi = false ;
      }
      
    }
    else if ($time_avg == 8) // Time Average 8Hour
    {
      if ($concentration <= 54) // 0-54 (8-hr)
      {
        $c_low = 0 ; $c_high = 54 ; $i_low = 0 ; $i_high = 50 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 55 && $concentration <= 70) // 55-70 (8-hr)
      {
        $c_low = 55 ; $c_high = 70 ; $i_low = 51 ; $i_high = 100 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 71 && $concentration <= 85) // 71-85 (8-hr)
      {
        $c_low = 71 ; $c_high = 85 ; $i_low = 101 ; $i_high = 150 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 86 && $concentration <= 105) // 86-105 (8-hr)
      {
        $c_low = 86 ; $c_high = 105 ; $i_low = 151 ; $i_high = 200 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 106 && $concentration <= 200) // 106-200 (8-hr)
      {
        $c_low = 106 ; $c_high = 200 ; $i_low = 201 ; $i_high = 300 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      else
      {
        $aqi = false ; // calculate AQI
      }
      
    }
  }
  else if ($pollutant == 'pm2.5')   // PM2.5      (24Hour Average, ug/m3)
  {
    // If data Unit different from ug/m3 recalculate (There is not so far...)
    
    // Now use the Time Average to calculate the AQI
    if ($time_avg == 24) // Time Average 1Hour
    {
    
      // From concentration in ug/m3 get the concentration low and high for that concentration
      if ($concentration <= 12.0) // 0.0-12.0 (24-hr)
      {
        $c_low = 0 ; $c_high = 12.0 ; $i_low = 0 ; $i_high = 50 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 12.1 && $concentration <= 35.4) // 12.1-35.4 (24-hr)
      {
        $c_low = 12.1 ; $c_high = 35.4 ; $i_low = 51 ; $i_high = 100 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 35.5 && $concentration <= 55.4) // 35.5-55.4 (24-hr)
      {
        $c_low = 35.5 ; $c_high = 55.4 ; $i_low = 101 ; $i_high = 150 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 55.5 && $concentration <= 150.4) // 55.5-150.4 (24-hr)
      {
        $c_low = 55.5 ; $c_high = 150.4 ; $i_low = 151 ; $i_high = 200 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 150.5 && $concentration <= 250.4) // 150.5-250.4 (24-hr)
      {
        $c_low = 150.5 ; $c_high = 250.4 ; $i_low = 201 ; $i_high = 300 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 250.5 && $concentration <= 350.4) // 250.5-350.4 (24-hr)
      {
        $c_low = 250.5 ; $c_high = 350.4 ; $i_low = 301 ; $i_high = 400 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 350.5 && $concentration <= 500.4) // 350.5-500.4 (24-hr)
      {
        $c_low = 350.5 ; $c_high = 500.4 ; $i_low = 401 ; $i_high = 500 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      else
      {
        $aqi = false ; // calculate AQI
      }
    
    }
    
  }
  else if ($pollutant == 'pm10')   // PM10      (24Hour Average, ug/m3)
  {
    // If data Unit different from ug/m3 recalculate (There is not so far...)
    
    
    // Now use the Time Average to calculate the AQI
    if ($time_avg == 24) // Time Average 1Hour
    {
      // From concentration in ug/m3 get the concentration low and high for that concentration
      if ($concentration <= 54) // 0-54 (24-hr)
      {
        $c_low = 0 ; $c_high = 54 ; $i_low = 0 ; $i_high = 50 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 55 && $concentration <= 154) // 55-154 (24-hr)
      {
        $c_low = 55 ; $c_high = 154 ; $i_low = 51 ; $i_high = 100 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 155 && $concentration <= 254) // 155-254 (24-hr)
      {
        $c_low = 155 ; $c_high = 254 ; $i_low = 101 ; $i_high = 150 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 255 && $concentration <= 354) // 255-354 (24-hr)
      {
        $c_low = 255 ; $c_high = 354 ; $i_low = 151 ; $i_high = 200 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 355 && $concentration <= 424) // 355-424 (24-hr)
      {
        $c_low = 355 ; $c_high = 424 ; $i_low = 201 ; $i_high = 300 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 425 && $concentration <= 504) // 425-504 (24-hr)
      {
        $c_low = 425 ; $c_high = 504 ; $i_low = 301 ; $i_high = 400 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 505 && $concentration <= 604) // 505-604 (24-hr)
      {
        $c_low = 505 ; $c_high = 604 ; $i_low = 401 ; $i_high = 500 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      else
      {
        $aqi = false ; // calculate AQI
      }
    
    }
    
  }
  else if ($pollutant == 'co')   // Carbon Monoxide | CO (8Hour Average, PPM)
  {
    // If data Unit different from PPM recalculate
    if ($data_unit == 'ppb')
      $concentration = $concentration / 1000; // PPM
    else if ($data_unit == 'ug/m3')
    {
      $molecular_weight = 12 + 16 ; // g/mol
      $concentration = 24.45 * $concentration / $molecular_weight * 1000 ; // PPM
    }
    
    // Now use the Time Average to calculate the AQI
    if ($time_avg == 8) // Time Average 1Hour
    {
    
      // From concentration in PPM get the concentration low and high for that concentration
      if ($concentration <= 4.4) // 0.0-4.4 (8-hr)
      {
        $c_low = 0 ; $c_high = 4.4 ; $i_low = 0 ; $i_high = 50 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 4.5 && $concentration <= 9.4) // 4.5-9.4 (8-hr)
      {
        $c_low = 4.5 ; $c_high = 9.4 ; $i_low = 51 ; $i_high = 100 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 9.5 && $concentration <= 12.4) // 9.5-12.4 (8-hr)
      {
        $c_low = 9.5 ; $c_high = 12.4 ; $i_low = 101 ; $i_high = 150 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 12.5 && $concentration <= 15.4) // 12.5-15.4 (8-hr)
      {
        $c_low = 12.5 ; $c_high = 15.4 ; $i_low = 151 ; $i_high = 200 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 15.5 && $concentration <= 30.4) // 15.5-30.4 (8-hr)
      {
        $c_low = 15.5 ; $c_high = 30.4 ; $i_low = 201 ; $i_high = 300 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 30.5 && $concentration <= 40.4) // 30.5-40.4 (8-hr)
      {
        $c_low = 30.5 ; $c_high = 40.4 ; $i_low = 301 ; $i_high = 400 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 40.5 && $concentration <= 50.4) // 40.5-50.4 (8-hr)
      {
        $c_low = 40.5 ; $c_high = 50.4 ; $i_low = 401 ; $i_high = 500 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      else
      {
        $aqi = false ; // calculate AQI
      }
    
    }
    
  }
  else if ($pollutant == 'so2')  // Sulfur Dioxide | SO2 (1Hour Average, PPB)
  {
    // If data Unit different from PPM recalculate
    if ($data_unit == 'ppm')
      $concentration = $concentration * 1000; // PPB
    else if ($data_unit == 'ug/m3')
    {
      $molecular_weight = 32 + (16 * 2) ; // g/mol
      $concentration = 24.45 * $concentration / $molecular_weight ; // PPB
    }
    
    // Now use the Time Average to calculate the AQI
    if ($time_avg == 1) // Time Average 1Hour
    {
      // From concentration in PPB get the concentration low and high for that concentration
      if ($concentration <= 35) // 0-35 (1-hr)
      {
        $c_low = 0 ; $c_high = 35 ; $i_low = 0 ; $i_high = 50 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 36 && $concentration <= 75) // 36-75 (1-hr)
      {
        $c_low = 36 ; $c_high = 75 ; $i_low = 51 ; $i_high = 100 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 76 && $concentration <= 185) // 76-185 (1-hr)
      {
        $c_low = 76 ; $c_high = 185 ; $i_low = 101 ; $i_high = 150 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 186 && $concentration <= 304) // 186-304 (1-hr)
      {
        $c_low = 186 ; $c_high = 304 ; $i_low = 151 ; $i_high = 200 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      else
      {
        $aqi = false ;
      }
      
    }
    else if ($time_avg == 24) // Time Average 24Hour
    {
      if ($concentration >= 305 && $concentration <= 604) // 305-604 (24-hr)
      {
        $c_low = 305 ; $c_high = 604 ; $i_low = 201 ; $i_high = 300 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 605 && $concentration <= 804) // 605-804 (24-hr)
      {
        $c_low = 605 ; $c_high = 804 ; $i_low = 301 ; $i_high = 400 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 805 && $concentration <= 1004) // 805-1004 (24-hr)
      {
        $c_low = 805 ; $c_high = 1004 ; $i_low = 401 ; $i_high = 500 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      else
      {
        $aqi = false ; // calculate AQI
      }
      
    }
    
  }
  else if ($pollutant == 'no2')  // Nitrogen Dioxide | NO2 (1Hour Average, PPB)
  {
    // If data Unit different from PPM recalculate
    if ($data_unit == 'ppm')
      $concentration = $concentration * 1000; // PPB
    else if ($data_unit == 'ug/m3')
    {
      $molecular_weight = 14 + (16 * 2) ; // g/mol
      $concentration = 24.45 * $concentration / $molecular_weight ; // PPB
    }
    
    // Now use the Time Average to calculate the AQI
    if ($time_avg == 1) // Time Average 1Hour
    {
    
      // From concentration in PPM get the concentration low and high for that concentration
      if ($concentration <= 53) // 00-53 (1-hr)
      {
        $c_low = 0 ; $c_high = 53 ; $i_low = 0 ; $i_high = 50 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 54 && $concentration <= 100) // 54-100 (1-hr)
      {
        $c_low = 54 ; $c_high = 100 ; $i_low = 51 ; $i_high = 100 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 101 && $concentration <= 360) // 101-360 (1-hr)
      {
        $c_low = 101 ; $c_high = 360 ; $i_low = 101 ; $i_high = 150 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 361 && $concentration <= 649) // 361-649 (1-hr)
      {
        $c_low = 361 ; $c_high = 649 ; $i_low = 151 ; $i_high = 200 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 650 && $concentration <= 1249) // 650-1249 (1-hr)
      {
        $c_low = 650 ; $c_high = 1249 ; $i_low = 201 ; $i_high = 300 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 1250 && $concentration <= 1649) // 1250-1649 (1-hr)
      {
        $c_low = 1250 ; $c_high = 1649 ; $i_low = 301 ; $i_high = 400 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 1650 && $concentration <= 2049) // 1650-2049 (1-hr)
      {
        $c_low = 1650 ; $c_high = 2049 ; $i_low = 401 ; $i_high = 500 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      else
      {
        $aqi = false ; // calculate AQI
      }
    
    }
    
  }
  else if ($pollutant == 'nh3')  // Ammonia | NH3 (24Hour Average, ug/m3)
  {
    // If data Unit different from PPM recalculate
    $molecular_weight = 14 + (1 * 3) ; // g/mol
    if ($data_unit == 'ppm')
      $concentration = 0.0409 * $concentration * $molecular_weight * 1000 ; // ug/m3
    else if ($data_unit == 'ppb')
      $concentration = 0.0409 * $concentration * $molecular_weight ; // ug/m3
    
    // Now use the Time Average to calculate the AQI
    if ($time_avg == 24) // Time Average 1Hour
    {
      
      // From concentration in PPM get the concentration low and high for that concentration
      if ($concentration <= 200) // 0-200 (24-hr)
      {
        $c_low = 0 ; $c_high = 200 ; $i_low = 0 ; $i_high = 50 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 201 && $concentration <= 400) // 201–400 (24-hr)
      {
        $c_low = 201 ; $c_high = 400 ; $i_low = 51 ; $i_high = 100 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 401 && $concentration <= 800) // 401–800 (24-hr)
      {
        $c_low = 401 ; $c_high = 800 ; $i_low = 101 ; $i_high = 200 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 801 && $concentration <= 1200) // 801–1200 (24-hr)
      {
        $c_low = 801 ; $c_high = 1200 ; $i_low = 201 ; $i_high = 300 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 1201 && $concentration <= 1800) // 1201–1800 (24-hr)
      {
        $c_low = 1201 ; $c_high = 1800 ; $i_low = 301 ; $i_high = 400 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      else
      {
        $aqi = false ; // calculate AQI
      }
    
    }
    
  }
  
  return $aqi ;
  
} // calculatePollutantAQI

// This function filters the met data parameter name and returns presentable name as well as the icon
function parse_meteo_data($met_parameter)
{
    // Get the useful part of the parameter name
    $met_parameter = strtolower($met_parameter);

    switch ($met_parameter) {
        // External Temperature
        case "External Temperature": $met_parameter = 'Ext. Temperature'; $met_icon = 'icon_temp_inv.png'; break;
        case "external temperature": $met_parameter = 'Ext. Temperature'; $met_icon = 'icon_temp_inv.png'; break;
        case "external temperature ": $met_parameter = 'Ext. Temperature'; $met_icon = 'icon_temp_inv.png'; break;
        case "ambienttemperature": $met_parameter = 'Ext. Temperature'; $met_icon = 'icon_temp_inv.png'; break;
        case "External Temperature ": $met_parameter = 'Ext. Temperature'; $met_icon = 'icon_temp_inv.png'; break;

        // External Humidity
        case "External Humidity": $met_parameter = 'Ext. Humidity'; $met_icon = 'icon_humid_inv.png'; break;
        case "External Humidity ": $met_parameter = 'Ext. Humidity'; $met_icon = 'icon_humid_inv.png'; break;
        case "external humidity": $met_parameter = 'Ext. Humidity'; $met_icon = 'icon_humid_inv.png'; break;
        case "external humidity ": $met_parameter = 'Ext. Humidity'; $met_icon = 'icon_humid_inv.png'; break;
        case "ambienthumidity": $met_parameter = 'Ext. Humidity'; $met_icon = 'icon_humid_inv.png'; break;

        // Internal Temperature
        case "Internal Temperature": $met_parameter = 'Int. Temperature'; $met_icon = 'icon_temp_inv.png'; break;
        case "Internal Temperature ": $met_parameter = 'Int. Temperature'; $met_icon = 'icon_temp_inv.png'; break;
        case "internal temperature": $met_parameter = 'Int. Temperature'; $met_icon = 'icon_temp_inv.png'; break;
        case "internal temperature ": $met_parameter = 'Int. Temperature'; $met_icon = 'icon_temp_inv.png'; break;
        case "inboxtemperature": $met_parameter = 'Int. Temperature'; $met_icon = 'icon_temp_inv.png'; break;

        // Internal Humidity
        case "Internal Humidity": $met_parameter = 'Int. Humidity'; $met_icon = 'icon_humid_inv.png'; break;
        case "Internal Humidity ": $met_parameter = 'Int. Humidity'; $met_icon = 'icon_humid_inv.png'; break;
        case "internal humidity": $met_parameter = 'Int. Humidity'; $met_icon = 'icon_humid_inv.png'; break;
        case "internal humidity ": $met_parameter = 'Int. Humidity'; $met_icon = 'icon_humid_inv.png'; break;
        case "inboxhumidity": $met_parameter = 'Int. Humidity'; $met_icon = 'icon_humid_inv.png'; break;
        case "inbohumidity": $met_parameter = 'Int. Humidity'; $met_icon = 'icon_humid_inv.png'; break;

        // Solar radiation and UV
        case "Solar Radiation": $met_parameter = 'Solar Radiation'; $met_icon = 'icon_sun_inv.png'; break;
        case "solar radiation": $met_parameter = 'Solar Radiation'; $met_icon = 'icon_sun_inv.png'; break;
        case "Solar Radiation ": $met_parameter = 'Solar Radiation'; $met_icon = 'icon_sun_inv.png'; break;
        case "solar radiation ": $met_parameter = 'Solar Radiation'; $met_icon = 'icon_sun_inv.png'; break;
        case "UV Sensor": $met_parameter = 'UV Sensor'; $met_icon = 'icon_uv_inv.png'; break;
        case "uv sensor": $met_parameter = 'UV Sensor'; $met_icon = 'icon_uv_inv.png'; break;
        case "UV Sensor ": $met_parameter = 'UV Sensor'; $met_icon = 'icon_uv_inv.png'; break;
        case "uv sensor ": $met_parameter = 'UV Sensor'; $met_icon = 'icon_uv_inv.png'; break;

        //Wind speed and direction
        case "Wind Speed": $met_parameter = 'Wind Speed'; $met_icon = 'icon_wind_spd_inv.png'; break;
        case "wind speed": $met_parameter = 'Wind Speed'; $met_icon = 'icon_wind_spd_inv.png'; break;
        case "WInd Speed": $met_parameter = 'Wind Speed'; $met_icon = 'icon_wind_spd_inv.png'; break;
        case "Wind Speed ": $met_parameter = 'Wind Speed'; $met_icon = 'icon_wind_spd_inv.png'; break;
        case "wind speed ": $met_parameter = 'Wind Speed'; $met_icon = 'icon_wind_spd_inv.png'; break;
        case "WInd Speed ": $met_parameter = 'Wind Speed'; $met_icon = 'icon_wind_spd_inv.png'; break;
        case "Wind Direction": $met_parameter = 'Wind Direction'; $met_icon = 'icon_wind_dir_inv.png'; break;
        case "wind direction": $met_parameter = 'Wind Direction'; $met_icon = 'icon_wind_dir_inv.png'; break;
        case "WInd Direction": $met_parameter = 'Wind Direction'; $met_icon = 'icon_wind_dir_inv.png'; break;
        case "Wind Direction ": $met_parameter = 'Wind Direction'; $met_icon = 'icon_wind_dir_inv.png'; break;
        case "wind direction ": $met_parameter = 'Wind Direction'; $met_icon = 'icon_wind_dir_inv.png'; break;
        case "WInd Direction ": $met_parameter = 'Wind Direction'; $met_icon = 'icon_wind_dir_inv.png'; break;

        //Daily rain, Barometric Pressure and Noise Sensor
        case "Daily Rain": $met_parameter = 'Daily Rain'; $met_icon = 'icon_rain_inv.png'; break;
        case "daily rain": $met_parameter = 'Daily Rain'; $met_icon = 'icon_rain_inv.png'; break;
        case "Daily Rain ": $met_parameter = 'Daily Rain'; $met_icon = 'icon_rain_inv.png'; break;
        case "daily rain ": $met_parameter = 'Daily Rain'; $met_icon = 'icon_rain_inv.png'; break;
        case "Barometric Pressure": $met_parameter = 'Pressure'; $met_icon = 'icon_press_inv.png'; break;
        case "barometric pressure": $met_parameter = 'Pressure'; $met_icon = 'icon_press_inv.png'; break;
        case "Barometric Pressure ": $met_parameter = 'Pressure'; $met_icon = 'icon_press_inv.png'; break;
        case "barometric pressure ": $met_parameter = 'Pressure'; $met_icon = 'icon_press_inv.png'; break;
        case "Noise Sensor": $met_parameter = 'Noise Sensor'; $met_icon = 'icon_noise_inv.png'; break;
        case "noise sensor": $met_parameter = 'Noise Sensor'; $met_icon = 'icon_noise_inv.png'; break;
        case "Noise Sensor ": $met_parameter = 'Noise Sensor'; $met_icon = 'icon_noise_inv.png'; break;
        case "noise sensor ": $met_parameter = 'Noise Sensor'; $met_icon = 'icon_noise_inv.png'; break;

        // Default/Unrecognizable name
        default: $met_parameter = "Unknown Sensor"; $met_icon = 'icon_meteo.png';
    }

    return array($met_parameter, $met_icon);
}

// This function filters the met data table and returns it with updated grouped temperature and humidity if possible
function parse_temp_hum($met_parameter) {
    $temp = array();
    $is_temp_exist = false;

    $hum = array();
    $is_hum_exist = false;

    $output = array();
    for ($enum = 0; $enum < sizeof($met_parameter); $enum++) {
        if ($met_parameter[$enum][1] == "Int. Temperature" || $met_parameter[$enum][1] == "Ext. Temperature") {
            if ($is_temp_exist) {
                $temp[1] = "Ext/Int Temp.";
                if ($met_parameter[$enum][1] == "Int. Temperature") {
                    $temp[2] = $temp[2] . "/" . $met_parameter[$enum][2];
                } else {
                    $temp[2] = $met_parameter[$enum][2] . "/" . $temp[2];
                }
            } else {
                $temp = $met_parameter[$enum];
                $is_temp_exist = true;
            }
        } else if ($met_parameter[$enum][1] == "Int. Humidity" || $met_parameter[$enum][1] == "Ext. Humidity") {
            if ($is_hum_exist) {
                $hum[1] = "Ext/Int Humid.";
                if ($met_parameter[$enum][1] == "Int. Humidity") {
                    $hum[2] = $hum[2] . "/" . $met_parameter[$enum][2];
                } else {
                    $hum[2] = $met_parameter[$enum][2] . "/" . $hum[2];
                }
            } else {
                $hum = $met_parameter[$enum];
                $is_hum_exist = true;
            }
        }
    }

    if ($is_temp_exist || $is_hum_exist) {
        if ($is_temp_exist) {
            array_push($output, $temp);
        }
        if ($is_hum_exist) {
            array_push($output, $hum);
        }
        for ($enum = 0; $enum < sizeof($met_parameter); $enum++) {
            if ($met_parameter[$enum][1] != "Int. Temperature" && $met_parameter[$enum][1] != "Ext. Temperature" && $met_parameter[$enum][1] != "Int. Humidity" && $met_parameter[$enum][1] != "Ext. Humidity") {
                array_push($output, $met_parameter[$enum]);
            }
        }
    } else {
        $output = $met_parameter;
    }
    return $output;
}

// This function provides the right image for the weather data table
function addMetDataIconToParameter($met_parameter)
{
  // Get the useful part of the parameter name
  $met_parameter = strtolower($met_parameter) ;
  
  switch ($met_parameter)
  {
    case 'internal temperature': $met_icon = 'icon_temp_inv.png' ; break ;
    case 'internaltemperature': $met_icon = 'icon_temp_inv.png' ; break ;
    case 'external temperature': $met_icon = 'icon_temp_inv.png' ; break ;
    case 'externaltemperature': $met_icon = 'icon_temp_inv.png' ; break ;
    case 'ambient temperature': $met_icon = 'icon_temp_inv.png' ; break ;
    case 'ambienttemperature': $met_icon = 'icon_temp_inv.png' ; break ;
    case 'temperature': $met_icon = 'icon_temp_inv.png' ; break ;
    case 'inboxtemperature': $met_icon = 'icon_temp_inv.png' ; break ;
    case 'inbox temperature': $met_icon = 'icon_temp_inv.png' ; break ;
    case 'internal humidity': $met_icon = 'icon_hum_inv.png' ; break ;
    case 'internalhumidity': $met_icon = 'icon_hum_inv.png' ; break ;
    case 'external humidity': $met_icon = 'icon_hum_inv.png' ; break ;
    case 'externalhumidity': $met_icon = 'icon_hum_inv.png' ; break ;
    case 'humidity': $met_icon = 'icon_hum_inv.png' ; break ;
    case 'humidity': $met_icon = 'icon_hum_inv.png' ; break ;
    case 'inboxhumidity': $met_icon = 'icon_hum_inv.png' ; break ;
    case 'inbox humidity': $met_icon = 'icon_hum_inv.png' ; break ;
    case 'ambient humidity': $met_icon = 'icon_hum_inv.png' ; break ;
    case 'ambienthumidity': $met_icon = 'icon_hum_inv.png' ; break ;
    case 'daily rain': $met_icon = 'icon_rain_inv.png' ; break ;
    case 'dailyrain': $met_icon = 'icon_rain_inv.png' ; break ;
    case 'uv radiation': $met_icon = 'icon_uv_inv.png' ; break ;
    case 'uvradiation': $met_icon = 'icon_uv_inv.png' ; break ;
    case 'uv sensor': $met_icon = 'icon_uv_inv.png' ; break ;
    case 'uvsensor': $met_icon = 'icon_uv_inv.png' ; break ;
    case 'noise sensor': $met_icon = 'icon_noise_inv.png' ; break ;
    case 'noisesensor': $met_icon = 'icon_noise_inv.png' ; break ;
    case 'noise': $met_icon = 'icon_noise_inv.png' ; break ;
    case 'solar radiation': $met_icon = 'icon_sun_inv.png' ; break ;
    case 'solarradiation': $met_icon = 'icon_sun_inv.png' ; break ;
    case 'barometric pressure': $met_icon = 'icon_press_inv.png' ; break ;
    case 'barometricpressure': $met_icon = 'icon_press_inv.png' ; break ;
    case 'pressure': $met_icon = 'icon_press_inv.png' ; break ;
    case 'pressure1': $met_icon = 'icon_press_inv.png' ; break ;
    case 'pressure2': $met_icon = 'icon_press_inv.png' ; break ;
    case 'wind speed': $met_icon = 'icon_wind_spd_inv.png' ; break ;
    case 'windspeed': $met_icon = 'icon_wind_spd_inv.png' ; break ;
    case 'wind direction': $met_icon = 'icon_wind_dir_inv.png' ; break ;
    case 'winddirection': $met_icon = 'icon_wind_dir_inv.png' ; break ;
    case 'altitude': $met_icon = 'icon_altitude_inv.png' ; break ;
    case 'radiation': $met_icon = 'icon_radioactive_inv.png' ; break ;
    
    default: $met_icon = 'icon_meteo.png' ; // Met data icon for default
  }
  
  return $met_icon ;
} // addMetDataIconToParameter

function buildMetDiv($equipment, $return_array=false)
{
  
  // Connect to db
  $dbc_local = db_connect_local() ;
  
  $met_box = '' ;
  
  // Prepare array for Ademir
  $final_sensor_table = '' ;
  $final_sensor_array = array() ;
  $final_sensor_iter = 0 ;
  
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
    if (strtolower($value->unit) != 'ppm' && strtolower($value->unit) != 'ppb' && strtolower($value->unit) != 'ou'
      && strtolower($value->unit) != 'ug/m3' && strtolower($value->unit) != 'ou/m3')
    {
      // Start the Met data box
      if (strlen($met_box) == 0)
      {
        $met_box .= '<div id="weather_box">' ;
        // Prepare Array for Ademir
        $final_sensor_array[$final_sensor_iter][0] = 'Parameter' ;
        $final_sensor_array[$final_sensor_iter][1] = 'Value' ;
        $final_sensor_array[$final_sensor_iter][2] = 'Data Unit' ;
        $final_sensor_iter++ ;
      }
      
      // Prepare Array for Ademir
      $final_sensor_array[$final_sensor_iter][0] = $value->name ;
      $final_sensor_array[$final_sensor_iter][1] = $value->value ;
      $final_sensor_array[$final_sensor_iter][2] = $value->unit ;
      $final_sensor_iter++ ;
      
      $met_box .= '  <div class="inner_box">
    <div class="line_image"><img class="image_trans" src="/images/' . addMetDataIconToParameter($value->name) . '"/></div>
    <div class="line_text">' . $value->name . '</div>
    <div class="line_text">' . $value->value . ' ' . $value->unit . '</div>
  </div>' ;
    }
  
  }
  // Finish the Met data Box
  if (strlen($met_box) > 0)
    $met_box .= '</div>' ;
  
  // Close db
  db_close($dbc_local) ;
  
  if (!$return_array)
    return $met_box ;
  else
    return $final_sensor_array ;
} // buildMetDiv

// Change the sensor names with respect to chemical 
function shortenSensorName($sensor_name, $empirical=false)
{ 
  // replace extra spaces around the '-'
  $sensor_name = str_replace(' - ', '-', $sensor_name) ;
  $sensor_name_orig = $sensor_name ;
  $sensor_name = strtolower($sensor_name) ;
  
  switch ($sensor_name)
  {
    case 'ammonia-lc': $shortened_name = 'NH3-LC' ; $formula = 'NH3' ; break ;
    case 'ammonia-a': $shortened_name = 'NH3-A' ; $formula = 'NH3' ; break ;
    case 'ammonia-b': $shortened_name = 'NH3-B' ; $formula = 'NH3' ; break ;
    case 'ammonia': $shortened_name = 'NH3' ; $formula = 'NH3' ; break ;
    case 'nitrogen oxide-lc': $shortened_name = 'NOx-LC' ; $formula = 'NO2' ; break ; // By default give Empirical formula NO2
    case 'nitrogen oxide': $shortened_name = 'NOx' ; $formula = 'NO2' ; break ; // By default give Empirical formula NO2
    case 'nitrogen dioxide-lc': $shortened_name = 'NO2-LC' ; $formula = 'NO2' ; break ;
    case 'nitrogendioxide-lc': $shortened_name = 'NO2-LC' ; $formula = 'NO2' ; break ;
    case 'nitrogen dioxide': $shortened_name = 'NO2' ; $formula = 'NO2' ; break ;
    case 'nitrogen-dioxide': $shortened_name = 'NO2' ; $formula = 'NO2' ; break ;
    case 'nitrogendioxide': $shortened_name = 'NO2' ; $formula = 'NO2' ; break ;
    case 'nitrogen dioxide-lc-d': $shortened_name = 'NO2-LC-D' ; $formula = 'NO2' ; break ;
    case 'nitrogen-dioxide-lc-d': $shortened_name = 'NO2-LC-D' ; $formula = 'NO2' ; break ;
    case 'nitrogen dioxide-lc-c': $shortened_name = 'NO2-LC-C' ; $formula = 'NO2' ; break ;
    case 'nitrogen-dioxide-lc-c': $shortened_name = 'NO2-LC-C' ; $formula = 'NO2' ; break ;
    case 'nitrogen dioxide-lc-b': $shortened_name = 'NO2-LC-B' ; $formula = 'NO2' ; break ;
    case 'nitrogen-dioxide-lc-b': $shortened_name = 'NO2-LC-B' ; $formula = 'NO2' ; break ;
    case 'nitrogendioxide-lc-b': $shortened_name = 'NO2-LC-B' ; $formula = 'NO2' ; break ;
    case 'nitrogen dioxide-lc-a': $shortened_name = 'NO2-LC-A' ; $formula = 'NO2' ; break ;
    case 'nitrogen-dioxide-lc-a': $shortened_name = 'NO2-LC-A' ; $formula = 'NO2' ; break ;
    case 'nitrogendioxide-lc-a': $shortened_name = 'NO2-LC-A' ; $formula = 'NO2' ; break ;
    case 'nitrogen dioxide-hc': $shortened_name = 'NO2-HC' ; $formula = 'NO2' ; break ;
    case 'nitrogendioxide-hc': $shortened_name = 'NO2-HC' ; $formula = 'NO2' ; break ;
    case 'nitrogen-dioxide-hc': $shortened_name = 'NO2-HC' ; $formula = 'NO2' ; break ;
    case 'nitrogen dioxide-hc-a': $shortened_name = 'NO2-HC-A' ; $formula = 'NO2' ; break ;
    case 'nitrogen-dioxide-hc-a': $shortened_name = 'NO2-HC-A' ; $formula = 'NO2' ; break ;
    case 'nitric oxide': $shortened_name = 'NO' ; $formula = 'NO' ; break ;
    case 'nitricoxide': $shortened_name = 'NO' ; $formula = 'NO' ; break ;
    case 'nitric oxide-lc': $shortened_name = 'NO-LC' ; $formula = 'NO' ; break ;
    case 'nitricoxide-lc': $shortened_name = 'NO-LC' ; $formula = 'NO' ; break ;
    
    case 'hydrogen cyanide-lc-d': $shortened_name = 'HCN-LC-D' ; $formula = 'HCN' ; break ;
    case 'hydrogen-cyanide-lc-d': $shortened_name = 'HCN-LC-D' ; $formula = 'HCN' ; break ;
    case 'hydrogen cyanide-lc-c': $shortened_name = 'HCN-LC-C' ; $formula = 'HCN' ; break ;
    case 'hydrogen-cyanide-lc-c': $shortened_name = 'HCN-LC-C' ; $formula = 'HCN' ; break ;
    case 'hydrogen cyanide-lc-b': $shortened_name = 'HCN-LC-B' ; $formula = 'HCN' ; break ;
    case 'hydrogen-cyanide-lc-b': $shortened_name = 'HCN-LC-B' ; $formula = 'HCN' ; break ;
    case 'hydrogen cyanide-lc-a': $shortened_name = 'HCN-LC-A' ; $formula = 'HCN' ; break ;
    case 'hydrogen-cyanide-lc-a': $shortened_name = 'HCN-LC-A' ; $formula = 'HCN' ; break ;
    
    case 'hydrogen sulfide-hc': $shortened_name = 'H2S-HC' ; $formula = 'H2S' ; break ;
    case 'hydrogen-sulfide-hc': $shortened_name = 'H2S-HC' ; $formula = 'H2S' ; break ;
    case 'hydrogensulfide-hc': $shortened_name = 'H2S-HC' ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide-hc-a': $shortened_name = 'H2S-HC-A' ; $formula = 'H2S' ; break ;
    case 'hydrogen-sulfide-hc-a': $shortened_name = 'H2S-HC-A' ; $formula = 'H2S' ; break ;
    case 'hydrogensulfide-hc-a': $shortened_name = 'H2S-HC-A' ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide-hc-b': $shortened_name = 'H2S-HC-B' ; $formula = 'H2S' ; break ;
    case 'hydrogen-sulfide-hc-b': $shortened_name = 'H2S-HC-B' ; $formula = 'H2S' ; break ;
    case 'hydrogensulfide-hc-b': $shortened_name = 'H2S-HC-B' ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide-lc': $shortened_name = 'H2S-LC' ; $formula = 'H2S' ; break ;
    case 'hydrogen-sulfide-lc': $shortened_name = 'H2S-LC' ; $formula = 'H2S' ; break ;
    case 'hydrogensulfide-lc': $shortened_name = 'H2S-LC'  ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide-mc': $shortened_name = 'H2S-MC' ; $formula = 'H2S' ; break ;
    case 'hydrogensulfide-mc': $shortened_name = 'H2S-MC' ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide-lc-a': $shortened_name = 'H2S-LC-A' ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide-lc -a': $shortened_name = 'H2S-LC-A' ; $formula = 'H2S' ; break ;
    case 'hydrogen-sulfide-lc-a': $shortened_name = 'H2S-LC-A' ; $formula = 'H2S' ; break ;
    case 'hydrogensulfide-lc-a': $shortened_name = 'H2S-LC-A' ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide-lc-b': $shortened_name = 'H2S-LC-B' ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide-lc -b': $shortened_name = 'H2S-LC-B' ; $formula = 'H2S' ; break ;
    case 'hydrogen-sulfide-lc-b': $shortened_name = 'H2S-LC-B' ; $formula = 'H2S' ; break ;
    case 'hydrogensulfide-lc-b': $shortened_name = 'H2S-LC-B' ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide-lc-d': $shortened_name = 'H2S-LC-D' ; $formula = 'H2S' ; break ;
    case 'hydrogen-sulfide-lc-d': $shortened_name = 'H2S-LC-D' ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide-lc-c': $shortened_name = 'H2S-LC-C' ; $formula = 'H2S' ; break ;
    case 'hydrogen-sulfide-lc-c': $shortened_name = 'H2S-LC-C' ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide': $shortened_name = 'H2S' ; $formula = 'H2S' ; break ;
    case 'hydrogensulfide': $shortened_name = 'H2S' ; $formula = 'H2S' ; break ;
    case 'hyrdrogen sulfide-b': $shortened_name = 'H2S-B' ; $formula = 'H2S' ; break ; // Typo
    case 'hyrdrogen sulfide-a': $shortened_name = 'H2S-A' ; $formula = 'H2S' ; break ; // Typo
    case 'hyrdrogensulfide-b': $shortened_name = 'H2S-B' ; $formula = 'H2S' ; break ; // Typo
    case 'hyrdrogensulfide-a': $shortened_name = 'H2S-A' ; $formula = 'H2S' ; break ; // Typo
    
    case 'sulfur dioxide': $shortened_name = 'SO2' ; $formula = 'SO2' ; break ;
    case 'sulfur-dioxide': $shortened_name = 'SO2' ; $formula = 'SO2' ; break ;
    case 'sulfurdioxide': $shortened_name = 'SO2' ; $formula = 'SO2' ; break ;
    case 'sulfur dioxide-lc': $shortened_name = 'SO2-LC' ; $formula = 'SO2' ; break ;
    case 'sulfurdioxide-lc': $shortened_name = 'SO2-LC' ; $formula = 'SO2' ; break ;
    case 'sulfur dioxide-mc': $shortened_name = 'SO2-MC' ; $formula = 'SO2' ; break ;
    case 'sulfurdioxide-mc': $shortened_name = 'SO2-MC' ; $formula = 'SO2' ; break ;
    case 'sulfur dioxide-hc': $shortened_name = 'SO2-HC' ; $formula = 'SO2' ; break ;
    case 'sulfur-dioxide-hc': $shortened_name = 'SO2-HC' ; $formula = 'SO2' ; break ;
    case 'sulfurdioxide-hc': $shortened_name = 'SO2-HC' ; $formula = 'SO2' ; break ;
    case 'sulfur dioxide-hc-a': $shortened_name = 'SO2-HC-A' ; $formula = 'SO2' ; break ;
    case 'sulfur-dioxide-hc-a': $shortened_name = 'SO2-HC-A' ; $formula = 'SO2' ; break ;
    case 'sulfur dioxide-lc-d': $shortened_name = 'SO2-LC-D' ; $formula = 'SO2' ; break ;
    case 'sulfur-dioxide-lc-a': $shortened_name = 'SO2-LC-A' ; $formula = 'SO2' ; break ;
    case 'sulfur dioxide-lc-a': $shortened_name = 'SO2-LC-A' ; $formula = 'SO2' ; break ;
    case 'sulfurdioxide-lc-a': $shortened_name = 'SO2-LC-A' ; $formula = 'SO2' ; break ;
    case 'suflurdioxide-lc-a': $shortened_name = 'SO2-LC-A' ; $formula = 'SO2' ; break ; // Typo
    case 'suflurdioxide-lc-b': $shortened_name = 'SO2-LC-B' ; $formula = 'SO2' ; break ; // Typo
    case 'suflur dioxide-lc-a': $shortened_name = 'SO2-LC-A' ; $formula = 'SO2' ; break ; // Typo
    case 'suflur dioxide-lc-b': $shortened_name = 'SO2-LC-B' ; $formula = 'SO2' ; break ; // Typo
    case 'sulfur-dioxide-lc-b': $shortened_name = 'SO2-LC-B' ; $formula = 'SO2' ; break ;
    case 'sulfurdioxide-lc-b': $shortened_name = 'SO2-LC-B' ; $formula = 'SO2' ; break ;
    case 'sulfur-dioxide-lc-c': $shortened_name = 'SO2-LC-C' ; $formula = 'SO2' ; break ;
    case 'sulfur-dioxide-lc-d': $shortened_name = 'SO2-LC-D' ; $formula = 'SO2' ; break ;
    
    case 'carbon monoxide': $shortened_name = 'CO' ; $formula = 'CO' ; break ;
    case 'carbon-monoxide': $shortened_name = 'CO' ; $formula = 'CO' ; break ;
    case 'carbonmonoxide': $shortened_name = 'CO' ; $formula = 'CO' ; break ;
    case 'carbon monoxide-lc': $shortened_name = 'CO-LC' ; $formula = 'CO' ; break ;
    case 'carbonmonoxide-lc': $shortened_name = 'CO-LC' ; $formula = 'CO' ; break ;
    case 'carbon monoxide-lc-ppm': $shortened_name = 'CO-LC' ; $formula = 'CO' ; break ;
    case 'carbonmonoxide-lc-ppm': $shortened_name = 'CO-LC' ; $formula = 'CO' ; break ;
    case 'carbon monoxide-lc-a': $shortened_name = 'CO-LC-A' ; $formula = 'CO' ; break ;
    case 'carbonmonoxide-lc-a': $shortened_name = 'CO-LC-A' ; $formula = 'CO' ; break ;
    case 'carbon monoxide-lc-b': $shortened_name = 'CO-LC-B' ; $formula = 'CO' ; break ;
    case 'carbonmonoxide-lc-b': $shortened_name = 'CO-LC-B' ; $formula = 'CO' ; break ;
    case 'carbon dioxide-lc': $shortened_name = 'CO2-LC' ; $formula = 'CO2' ; break ;
    case 'carbondioxide-lc': $shortened_name = 'CO2-LC' ; $formula = 'CO2' ; break ;
    case 'carbon dioxide': $shortened_name = 'CO2'  ; $formula = 'CO2' ; break ;
    case 'carbondioxide': $shortened_name = 'CO2' ; $formula = 'CO2' ; break ;
    case 'carbon dioxide-lc-ppm': $shortened_name = 'CO2-LC' ; $formula = 'CO2' ; break ;
    case 'carbondioxide-lc-ppm': $shortened_name = 'CO2-LC' ; $formula = 'CO2' ; break ;
    
    case 'ethyl-sh+': $shortened_name = 'Et-SH' ; $formula = 'C2H5-SH' ; break ;
    case 'methane-lc': $shortened_name = 'Me-LC' ; $formula = 'CH4' ; break ;
    case 'methane': $shortened_name = 'Me' ; $formula = 'CH4' ; break ;
    case 'methyl-sh': $shortened_name = 'Me-SH' ; $formula = 'CH3-SH' ; break ;
    case 'trs-methylmercaptan': $shortened_name = 'TRS-Me-SH' ; $formula = 'TRS-CH3-SH' ; break ;
    case 'trs-methyl mercaptan': $shortened_name = 'TRS-Me-SH' ; $formula = 'TRS-CH3-SH' ; break ;
    case 'iso-propyl-sh': $shortened_name = 'iPr-SH' ; $formula = 'C3H7-SH' ; break ;
    case 'n-propyl-sh': $shortened_name = 'nPr-SH' ; $formula = 'C3H7-SH' ; break ;
    case '2-butyl-sh': $shortened_name = '2Bu-SH' ; $formula = 'C4H9-SH' ; break ;
    case 'n-butyl-sh': $shortened_name = 'nBu-SH' ; $formula = 'C4H9-SH' ; break ;
    case 'methane, butane, lpg': $shortened_name = 'Me-Bu-LPG' ; $formula = 'CH4-C4H10-LPG' ; break ;
    case 'methane,butane,lpg': $shortened_name = 'Me-Bu-LPG' ; $formula = 'CH4-C4H10-LPG' ; break ;
    case 'alcohol, ethanol, smoke': $shortened_name = 'Alc-Et-SMK' ; $formula = 'Alc-Et-SMK' ; break ;
    case 'alcohol,ethanol,smoke': $shortened_name = 'Alc-Et-SMK' ; $formula = 'Alc-Et-SMK' ; break ;
    case 'benzene, alcohol, smoke': $shortened_name = 'Bz-Alc-SMK' ; $formula = 'Bz-Alc-SMK' ; break ;
    case 'benzene,alcohol,smoke': $shortened_name = 'Bz-Alc-SMK' ; $formula = 'Bz-Alc-SMK' ; break ;
    
    case 'odour unit': $shortened_name = 'OU' ; $formula = '' ; break ;
    case 'odourunit': $shortened_name = 'OU' ; $formula = '' ; break ;
    case 'odour': $shortened_name = 'OU' ; $formula = '' ; break ;
    case 'odour-a': $shortened_name = 'OU-A' ; $formula = '' ; break ;
    case 'odour-b': $shortened_name = 'OU-B' ; $formula = '' ; break ;
    
    case 'hydrogen': $shortened_name = 'H2' ; $formula = 'H2' ; break ;
    case 'hydrogen chloride': $shortened_name = 'HCl' ; $formula = 'HCl' ; break ;
    case 'hydrogen-chloride': $shortened_name = 'HCl' ; $formula = 'HCl' ; break ;
    case 'hydrogenchloride': $shortened_name = 'HCl' ; $formula = 'HCl' ; break ;
    case 'hydrogen chloride-lc-d': $shortened_name = 'HCl-LC-D' ; $formula = 'HCl' ; break ;
    case 'hydrogen-chloride-lc-d': $shortened_name = 'HCl-LC-D' ; $formula = 'HCl' ; break ;
    case 'hydrogen chloride-lc-c': $shortened_name = 'HCl-LC-C' ; $formula = 'HCl' ; break ;
    case 'hydrogen-chloride-lc-c': $shortened_name = 'HCl-LC-C' ; $formula = 'HCl' ; break ;
    case 'hydrogen chloride-lc-b': $shortened_name = 'HCl-LC-B' ; $formula = 'HCl' ; break ;
    case 'hydrogen-chloride-lc-b': $shortened_name = 'HCl-LC-B' ; $formula = 'HCl' ; break ;
    case 'hydrogen chloride-lc-a': $shortened_name = 'HCl-LC-A' ; $formula = 'HCl' ; break ;
    case 'hydrogen-chloride-lc-a': $shortened_name = 'HCl-LC-A' ; $formula = 'HCl' ; break ;
    case 'hydrogen cloride': $shortened_name = 'HCl' ; $formula = 'HCl' ; break ;
    case 'hydrogen-cloride': $shortened_name = 'HCl' ; $formula = 'HCl' ; break ;
    case 'hydrogencloride': $shortened_name = 'HCl' ; $formula = 'HCl' ; break ;
    case 'chlorine': $shortened_name = 'Cl2' ; $formula = 'Cl2' ; break ;
    case 'chlorine-lc-d': $shortened_name = 'Cl2-LC-D' ; $formula = 'Cl2' ; break ;
    case 'chlorine-lc-c': $shortened_name = 'Cl2-LC-C' ; $formula = 'Cl2' ; break ;
    case 'chlorine-lc-b': $shortened_name = 'Cl2-LC-B' ; $formula = 'Cl2' ; break ;
    case 'chlorine-lc-a': $shortened_name = 'Cl2-LC-A' ; $formula = 'Cl2' ; break ;
    
    case 'ozone & nitrogen dioxide': $shortened_name = 'O3-NO2' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogen dioxide': $shortened_name = 'O3-NO2' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogendioxide': $shortened_name = 'O3-NO2' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogen dioxide-lc-c': $shortened_name = 'O3-NO2-LC-C' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogendioxide-lc-c': $shortened_name = 'O3-NO2-LC-C' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogen dioxide-lc-d': $shortened_name = 'O3-NO2-LC-D' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogendioxide-lc-d': $shortened_name = 'O3-NO2-LC-D' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogen dioxide-lc-a': $shortened_name = 'O3-NO2-LC-A' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogendioxide-lc-a': $shortened_name = 'O3-NO2-LC-A' ; $formula = 'O3-NO2' ; break ;
    case 'ozone & nitrogen dioxide-lc-a': $shortened_name = 'O3-NO2-LC-A' ; $formula = 'O3-NO2' ; break ;
    case 'ozone & nitrogen dioxide-lc-b': $shortened_name = 'O3-NO2-LC-B' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogen dioxide-lc-b': $shortened_name = 'O3-NO2-LC-B' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogendioxide-lc-b': $shortened_name = 'O3-NO2-LC-B' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogen dioxide-lc-b': $shortened_name = 'O3-NO2-LC-B' ; $formula = 'O3-NO2' ; break ;
    case 'ozone + nitrogen dioxide': $shortened_name = 'O3-NO2' ; $formula = 'O3-NO2' ; break ;
    case 'ozone+nitrogendioxide': $shortened_name = 'O3-NO2' ; $formula = 'O3-NO2' ; break ;
    case 'nitrogen dioxide + ozone': $shortened_name = 'NO2-O3' ; $formula = 'NO2-O3' ; break ;
    case 'nitrogendioxide+ozone': $shortened_name = 'NO2-O3' ; $formula = 'NO2-O3' ; break ;
    case 'nitrogen dioxide + ozone-lc': $shortened_name = 'NO2-O3-LC' ; $formula = 'NO2-O3' ; break ;
    case 'nitrogendioxide+ozone-lc': $shortened_name = 'NO2-O3-LC' ; $formula = 'NO2-O3' ; break ;
    case 'ozone + nitrogen dioxide-lc': $shortened_name = 'O3-NO2-LC' ; $formula = 'O3-NO2' ; break ;
    case 'ozone+nitrogendioxide-lc': $shortened_name = 'O3-NO2-LC' ; $formula = 'O3-NO2' ; break ;
    case 'ozone-lc': $shortened_name = 'O3-LC' ; $formula = 'O3' ; break ;
    case 'ozone': $shortened_name = 'O3' ; $formula = 'O3' ; break ;
    
    case 'air contaminants (ammonia, ethanol, toulene)': $shortened_name = 'Air Cont.' ; $formula = '' ; break ;
    case 'aircontaminants(ammonia,ethanol,toulene)': $shortened_name = 'Air Cont.' ; $formula = '' ; break ;
    
    case 'trs & amines': $shortened_name = 'TRS+A' ; $formula = 'TRS+A' ; break ;
    case 'trs&amines': $shortened_name = 'TRS+A' ; $formula = 'TRS+A' ; break ;
    case 'trs and amines': $shortened_name = 'TRS+A' ; $formula = 'TRS+A' ; break ;
    case 'trsandamines': $shortened_name = 'TRS+A' ; $formula = 'TRS+A' ; break ;
    case 'trs+amines': $shortened_name = 'TRS+A' ; $formula = 'TRS+A' ; break ;
    case 'trs + amines': $shortened_name = 'TRS+A' ; $formula = 'TRS+A' ; break ;
    case 'trs+amines-a': $shortened_name = 'TRS+A-A' ; $formula = 'TRS+A' ; break ;
    case 'trs+amines-b': $shortened_name = 'TRS+A-B' ; $formula = 'TRS+A' ; break ;
    case 'trs & amines-lc-a': $shortened_name = 'TRS+A-LC-A' ; $formula = 'TRS+A' ; break ;
    case 'trs&amines-lc-a': $shortened_name = 'TRS+A-LC-A' ; $formula = 'TRS+A' ; break ;
    case 'trs & amines -lc-a': $shortened_name = 'TRS+A-LC-A' ; $formula = 'TRS+A' ; break ;
    case 'trs & amines-lc-b': $shortened_name = 'TRS+A-LC-B' ; $formula = 'TRS+A' ; break ;
    case 'trs&amines-lc-b': $shortened_name = 'TRS+A-LC-B' ; $formula = 'TRS+A' ; break ;
    
    case 'formaldehyde': $shortened_name = 'CH2O' ; $formula = 'CH2O' ; break ;
    case 'formaldehyde-lc-d': $shortened_name = 'CH2O-LC-D' ; $formula = 'CH2O' ; break ;
    case 'formaldehyde-lc-c': $shortened_name = 'CH2O-LC-C' ; $formula = 'CH2O' ; break ;
    case 'formaldehyde-lc-b': $shortened_name = 'CH2O-LC-B' ; $formula = 'CH2O' ; break ;
    case 'formaldehyde-lc-a': $shortened_name = 'CH2O-LC-A' ; $formula = 'CH2O' ; break ;
    case 'formaldahyde-lc-b': $shortened_name = 'CH2O-LC-B' ; $formula = 'CH2O' ; break ; // Typo
    case 'formaldahyde-lc-a': $shortened_name = 'CH2O-LC-A' ; $formula = 'CH2O' ; break ; // Typo
    
    case 'pid sensor': $shortened_name = 'PID' ; $formula = 'PID' ; break ;
    case 'pidsensor': $shortened_name = 'PID' ; $formula = 'PID' ; break ;
    
    case 'voc-lc': $shortened_name = 'VOCs-LC' ; $formula = 'VOCs' ; break ;
    case 'vocs': $shortened_name = 'VOCs' ; $formula = 'VOCs' ; break ;
    case 'vocs-hc-a': $shortened_name = 'VOCs-HC-A' ; $formula = 'VOCs' ; break ;
    case 'vocs-hc-b': $shortened_name = 'VOCs-HC-B' ; $formula = 'VOCs' ; break ;
    case 'vocs-lc': $shortened_name = 'VOCs-LC' ; $formula = 'VOCs' ; break ;
    case 'vocs-lc- a': $shortened_name = 'VOCs-LC-A' ; $formula = 'VOCs' ; break ;
    case 'vocs-lc- b': $shortened_name = 'VOCs-LC-B' ; $formula = 'VOCs' ; break ;
    case 'vocs-lc- c': $shortened_name = 'VOCs-LC-C' ; $formula = 'VOCs' ; break ;
    case 'vocs-lc- d': $shortened_name = 'VOCs-LC-D' ; $formula = 'VOCs' ; break ;
    case 'vocs-lc-a': $shortened_name = 'VOCs-LC-A' ; $formula = 'VOCs' ; break ;
    case 'vocs-lc-b': $shortened_name = 'VOCs-LC-B' ; $formula = 'VOCs' ; break ;
    case 'vocs-lc-c': $shortened_name = 'VOCs-LC-C' ; $formula = 'VOCs' ; break ;
    case 'vocs-lc-d': $shortened_name = 'VOCs-LC-D' ; $formula = 'VOCs' ; break ;
    
    case 'pm1': $shortened_name = 'PM1' ; $formula = 'PM1' ; break ;
    case 'pm2.5': $shortened_name = 'PM2.5' ; $formula = 'PM2.5' ; break ;
    case 'pm10': $shortened_name = 'PM10' ; $formula = 'PM10' ; break ;
    default: $shortened_name = '' ; $formula = '' ; break ;
  }
  
  if (strlen($shortened_name) == 0)
  {
    $shortened_name = $sensor_name_orig ;
    //$shortened_name = $sensor_name ;
  }
  
  if ($empirical)
    return $formula ;
  else
    return $shortened_name ;
} // shortenSensorName

function buildTimezoneSelectInput($timezone=false, $timezone_id=false)
{
  $final_timezone_select = '' ;
  
  // Build the Select inut based on given info
  if ($timezone && !$timezone_id) // If if Timezone value only build based on it
  {
    $final_timezone_select = '<select id="timezone">' ;
    // -12
    if ($timezone == -12)
      $final_timezone_select .= '<option timeZoneId="1" gmtAdjustment="GMT-12:00" useDaylightTime="0" value="-12" selected>(GMT-12:00) International Date Line West</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="1" gmtAdjustment="GMT-12:00" useDaylightTime="0" value="-12">(GMT-12:00) International Date Line West</option>' ;
    // -11
    if ($timezone == -11)
      $final_timezone_select .= '<option timeZoneId="2" gmtAdjustment="GMT-11:00" useDaylightTime="0" value="-11" selected>(GMT-11:00) Midway Island, Samoa</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="2" gmtAdjustment="GMT-11:00" useDaylightTime="0" value="-11">(GMT-11:00) Midway Island, Samoa</option>' ;
    // -10
    if ($timezone == -10)
      $final_timezone_select .= '<option timeZoneId="3" gmtAdjustment="GMT-10:00" useDaylightTime="0" value="-10" selected>(GMT-10:00) Hawaii</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="3" gmtAdjustment="GMT-10:00" useDaylightTime="0" value="-10">(GMT-10:00) Hawaii</option>' ;
    // -9
    if ($timezone == -9)
      $final_timezone_select .= '<option timeZoneId="4" gmtAdjustment="GMT-09:00" useDaylightTime="1" value="-9" selected>(GMT-09:00) Alaska</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="4" gmtAdjustment="GMT-09:00" useDaylightTime="1" value="-9">(GMT-09:00) Alaska</option>' ;
    // -8
    if ($timezone == -8)
      $final_timezone_select .= '<option timeZoneId="5" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8" selected>(GMT-08:00) Pacific Time (US & Canada)</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="5" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8">(GMT-08:00) Pacific Time (US & Canada)</option>' ;
    $final_timezone_select .= '<option timeZoneId="6" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8">(GMT-08:00) Tijuana, Baja California</option>' ;
    // -7
    $final_timezone_select .= '<option timeZoneId="7" gmtAdjustment="GMT-07:00" useDaylightTime="0" value="-7">(GMT-07:00) Arizona</option>' ;
    $final_timezone_select .= '<option timeZoneId="8" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>' ;
    if ($timezone == -7)
      $final_timezone_select .= '<option timeZoneId="9" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7" selected>(GMT-07:00) Mountain Time (US & Canada)</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="9" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7">(GMT-07:00) Mountain Time (US & Canada)</option>' ;
    // -6
    $final_timezone_select .= '<option timeZoneId="10" gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-6">(GMT-06:00) Central America</option>' ;
    if ($timezone == -6)
      $final_timezone_select .= '<option timeZoneId="11" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6" selected>(GMT-06:00) Central Time (US & Canada)</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="11" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6">(GMT-06:00) Central Time (US & Canada)</option>' ;
    $final_timezone_select .= '<option timeZoneId="12" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>' ;
    $final_timezone_select .= '<option timeZoneId="13" gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-6">(GMT-06:00) Saskatchewan</option>' ;
    // -5
    $final_timezone_select .= '<option timeZoneId="14" gmtAdjustment="GMT-05:00" useDaylightTime="0" value="-5">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>' ;
    if ($timezone == -5)
      $final_timezone_select .= '<option timeZoneId="15" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5" selected>(GMT-05:00) Eastern Time (US & Canada)</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="15" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5">(GMT-05:00) Eastern Time (US & Canada)</option>' ;
    $final_timezone_select .= '<option timeZoneId="16" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5">(GMT-05:00) Indiana (East)</option>' ;
    // -4
    if ($timezone == -4)
      $final_timezone_select .= '<option timeZoneId="17" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4" selected>(GMT-04:00) Atlantic Time (Canada)</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="17" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4">(GMT-04:00) Atlantic Time (Canada)</option>' ;
    $final_timezone_select .= '<option timeZoneId="18" gmtAdjustment="GMT-04:00" useDaylightTime="0" value="-4">(GMT-04:00) Caracas, La Paz</option>' ;
    $final_timezone_select .= '<option timeZoneId="19" gmtAdjustment="GMT-04:00" useDaylightTime="0" value="-4">(GMT-04:00) Manaus</option>' ;
    $final_timezone_select .= '<option timeZoneId="20" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4">(GMT-04:00) Santiago</option>' ;
    // -3.5
    if ($timezone == -3.5)
      $final_timezone_select .= '<option timeZoneId="21" gmtAdjustment="GMT-03:30" useDaylightTime="1" value="-3.5" selected>(GMT-03:30) Newfoundland</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="21" gmtAdjustment="GMT-03:30" useDaylightTime="1" value="-3.5">(GMT-03:30) Newfoundland</option>' ;
    // -3
    $final_timezone_select .= '<option timeZoneId="22" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3">(GMT-03:00) Brasilia</option>' ;
    if ($timezone == -3)
      $final_timezone_select .= '<option timeZoneId="23" gmtAdjustment="GMT-03:00" useDaylightTime="0" value="-3" selected>(GMT-03:00) Buenos Aires, Georgetown</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="23" gmtAdjustment="GMT-03:00" useDaylightTime="0" value="-3">(GMT-03:00) Buenos Aires, Georgetown</option>' ;
    $final_timezone_select .= '<option timeZoneId="24" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3">(GMT-03:00) Greenland</option>' ;
    $final_timezone_select .= '<option timeZoneId="25" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3">(GMT-03:00) Montevideo</option>' ;
    // -2
    if ($timezone == -2)
      $final_timezone_select .= '<option timeZoneId="26" gmtAdjustment="GMT-02:00" useDaylightTime="1" value="-2" selected>(GMT-02:00) Mid-Atlantic</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="26" gmtAdjustment="GMT-02:00" useDaylightTime="1" value="-2">(GMT-02:00) Mid-Atlantic</option>' ;
    // -1
    if ($timezone == -1)
      $final_timezone_select .= '<option timeZoneId="27" gmtAdjustment="GMT-01:00" useDaylightTime="0" value="-1" selected>(GMT-01:00) Cape Verde Is.</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="27" gmtAdjustment="GMT-01:00" useDaylightTime="0" value="-1">(GMT-01:00) Cape Verde Is.</option>' ;
    $final_timezone_select .= '<option timeZoneId="28" gmtAdjustment="GMT-01:00" useDaylightTime="1" value="-1">(GMT-01:00) Azores</option>' ;
    // 0
    $final_timezone_select .= '<option timeZoneId="29" gmtAdjustment="GMT+00:00" useDaylightTime="0" value="0">(GMT+00:00) Casablanca, Monrovia, Reykjavik</option>' ;
    if ($timezone == 0)
      $final_timezone_select .= '<option timeZoneId="30" gmtAdjustment="GMT+00:00" useDaylightTime="1" value="0" selected>(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="30" gmtAdjustment="GMT+00:00" useDaylightTime="1" value="0">(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>' ;
    // +1
    if ($timezone == 1)
      $final_timezone_select .= '<option timeZoneId="31" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1" selected>(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="31" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>' ;
    $final_timezone_select .= '<option timeZoneId="32" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>' ;
    $final_timezone_select .= '<option timeZoneId="33" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>' ;
    $final_timezone_select .= '<option timeZoneId="34" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>' ;
    $final_timezone_select .= '<option timeZoneId="35" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) West Central Africa</option>' ;
    // +2
    $final_timezone_select .= '<option timeZoneId="36" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Amman</option>' ;
    if ($timezone == 2)
      $final_timezone_select .= '<option timeZoneId="37" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2" selected>(GMT+02:00) Athens, Bucharest, Istanbul</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="37" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Athens, Bucharest, Istanbul</option>' ;
    $final_timezone_select .= '<option timeZoneId="38" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Beirut</option>' ;
    $final_timezone_select .= '<option timeZoneId="39" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Cairo</option>' ;
    $final_timezone_select .= '<option timeZoneId="40" gmtAdjustment="GMT+02:00" useDaylightTime="0" value="2">(GMT+02:00) Harare, Pretoria</option>' ;
    $final_timezone_select .= '<option timeZoneId="41" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>' ;
    $final_timezone_select .= '<option timeZoneId="42" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Jerusalem</option>' ;
    $final_timezone_select .= '<option timeZoneId="43" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Minsk</option>' ;
    $final_timezone_select .= '<option timeZoneId="44" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Windhoek</option>' ;
    // +3
    $final_timezone_select .= '<option timeZoneId="45" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3">(GMT+03:00) Kuwait, Riyadh, Baghdad</option>' ;
    if ($timezone == 3)
      $final_timezone_select .= '<option timeZoneId="46" gmtAdjustment="GMT+03:00" useDaylightTime="1" value="3" selected>(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="46" gmtAdjustment="GMT+03:00" useDaylightTime="1" value="3">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>' ;
    $final_timezone_select .= '<option timeZoneId="47" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3">(GMT+03:00) Nairobi</option>' ;
    $final_timezone_select .= '<option timeZoneId="48" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3">(GMT+03:00) Tbilisi</option>' ;
    // +3.5
    if ($timezone == 3.5)
      $final_timezone_select .= '<option timeZoneId="49" gmtAdjustment="GMT+03:30" useDaylightTime="1" value="3.5" selected>(GMT+03:30) Tehran</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="49" gmtAdjustment="GMT+03:30" useDaylightTime="1" value="3.5">(GMT+03:30) Tehran</option>' ;
    // +4
    if ($timezone == 4)
      $final_timezone_select .= '<option timeZoneId="50" gmtAdjustment="GMT+04:00" useDaylightTime="0" value="4" selected>(GMT+04:00) Abu Dhabi, Muscat</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="50" gmtAdjustment="GMT+04:00" useDaylightTime="0" value="4">(GMT+04:00) Abu Dhabi, Muscat</option>' ;
    $final_timezone_select .= '<option timeZoneId="51" gmtAdjustment="GMT+04:00" useDaylightTime="1" value="4">(GMT+04:00) Baku</option>' ;
    $final_timezone_select .= '<option timeZoneId="52" gmtAdjustment="GMT+04:00" useDaylightTime="1" value="4">(GMT+04:00) Yerevan</option>' ;
    // +4.5
    if ($timezone == 4.5)
      $final_timezone_select .= '<option timeZoneId="53" gmtAdjustment="GMT+04:30" useDaylightTime="0" value="4.5" selected>(GMT+04:30) Kabul</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="53" gmtAdjustment="GMT+04:30" useDaylightTime="0" value="4.5">(GMT+04:30) Kabul</option>' ;
    // +5
    $final_timezone_select .= '<option timeZoneId="54" gmtAdjustment="GMT+05:00" useDaylightTime="1" value="5">(GMT+05:00) Yekaterinburg</option>' ;
    if ($timezone == 5)
      $final_timezone_select .= '<option timeZoneId="55" gmtAdjustment="GMT+05:00" useDaylightTime="0" value="5" selected>(GMT+05:00) Islamabad, Karachi, Tashkent</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="55" gmtAdjustment="GMT+05:00" useDaylightTime="0" value="5">(GMT+05:00) Islamabad, Karachi, Tashkent</option>' ;
    // +5.5
    $final_timezone_select .= '<option timeZoneId="56" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="5.5">(GMT+05:30) Sri Jayawardenapura</option>' ;
    if ($timezone == 5.5)
      $final_timezone_select .= '<option timeZoneId="57" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="5.5" selected>(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="57" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="5.5">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>' ;
    // +5.75
    if ($timezone == 5.75)
      $final_timezone_select .= '<option timeZoneId="58" gmtAdjustment="GMT+05:45" useDaylightTime="0" value="5.75" selected>(GMT+05:45) Kathmandu</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="58" gmtAdjustment="GMT+05:45" useDaylightTime="0" value="5.75">(GMT+05:45) Kathmandu</option>' ;
    // +6
    if ($timezone == 6)
      $final_timezone_select .= '<option timeZoneId="59" gmtAdjustment="GMT+06:00" useDaylightTime="1" value="6" selected>(GMT+06:00) Almaty, Novosibirsk</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="59" gmtAdjustment="GMT+06:00" useDaylightTime="1" value="6">(GMT+06:00) Almaty, Novosibirsk</option>' ;
    $final_timezone_select .= '<option timeZoneId="60" gmtAdjustment="GMT+06:00" useDaylightTime="0" value="6">(GMT+06:00) Astana, Dhaka</option>' ;
    // +6.5
    if ($timezone == 6.5)
      $final_timezone_select .= '<option timeZoneId="61" gmtAdjustment="GMT+06:30" useDaylightTime="0" value="6.5" selected>(GMT+06:30) Yangon (Rangoon)</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="61" gmtAdjustment="GMT+06:30" useDaylightTime="0" value="6.5">(GMT+06:30) Yangon (Rangoon)</option>' ;
    // + 7
    if ($timezone == 7)
      $final_timezone_select .= '<option timeZoneId="62" gmtAdjustment="GMT+07:00" useDaylightTime="0" value="7" selected>(GMT+07:00) Bangkok, Hanoi, Jakarta</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="62" gmtAdjustment="GMT+07:00" useDaylightTime="0" value="7">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>' ;
    $final_timezone_select .= '<option timeZoneId="63" gmtAdjustment="GMT+07:00" useDaylightTime="1" value="7">(GMT+07:00) Krasnoyarsk</option>' ;
    // +8
    if ($timezone == 8)
      $final_timezone_select .= '<option timeZoneId="64" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8" selected>(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="64" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>' ;
    $final_timezone_select .= '<option timeZoneId="65" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Kuala Lumpur, Singapore</option>' ;
    $final_timezone_select .= '<option timeZoneId="66" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Irkutsk, Ulaan Bataar</option>' ;
    $final_timezone_select .= '<option timeZoneId="67" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Perth</option>' ;
    $final_timezone_select .= '<option timeZoneId="68" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Taipei</option>' ;
    // +9
    if ($timezone == 9)
      $final_timezone_select .= '<option timeZoneId="69" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="9" selected>(GMT+09:00) Osaka, Sapporo, Tokyo</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="69" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="9">(GMT+09:00) Osaka, Sapporo, Tokyo</option>' ;
    $final_timezone_select .= '<option timeZoneId="70" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="9">(GMT+09:00) Seoul</option>' ;
    $final_timezone_select .= '<option timeZoneId="71" gmtAdjustment="GMT+09:00" useDaylightTime="1" value="9">(GMT+09:00) Yakutsk</option>' ;
    // +9.5
    if ($timezone == 9.5)
      $final_timezone_select .= '<option timeZoneId="72" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="9.5" selected>(GMT+09:30) Adelaide</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="72" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="9.5">(GMT+09:30) Adelaide</option>' ;
    $final_timezone_select .= '<option timeZoneId="73" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="9.5">(GMT+09:30) Darwin</option>' ;
    // +10
    if ($timezone == 10)
      $final_timezone_select .= '<option timeZoneId="74" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="10" selected>(GMT+10:00) Brisbane</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="74" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="10">(GMT+10:00) Brisbane</option>' ;
    $final_timezone_select .= '<option timeZoneId="75" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10">(GMT+10:00) Canberra, Melbourne, Sydney</option>' ;
    $final_timezone_select .= '<option timeZoneId="76" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10">(GMT+10:00) Hobart</option>' ;
    $final_timezone_select .= '<option timeZoneId="77" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="10">(GMT+10:00) Guam, Port Moresby</option>' ;
    $final_timezone_select .= '<option timeZoneId="78" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10">(GMT+10:00) Vladivostok</option>' ;
    // +11
    if ($timezone == 11)
      $final_timezone_select .= '<option timeZoneId="79" gmtAdjustment="GMT+11:00" useDaylightTime="1" value="11" selected>(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="79" gmtAdjustment="GMT+11:00" useDaylightTime="1" value="11">(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>' ;
    // +12
    if ($timezone == 12)
      $final_timezone_select .= '<option timeZoneId="80" gmtAdjustment="GMT+12:00" useDaylightTime="1" value="12" selected>(GMT+12:00) Auckland, Wellington</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="80" gmtAdjustment="GMT+12:00" useDaylightTime="1" value="12">(GMT+12:00) Auckland, Wellington</option>' ;
    $final_timezone_select .= '<option timeZoneId="81" gmtAdjustment="GMT+12:00" useDaylightTime="0" value="12">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>' ;
    // +13
    if ($timezone == 13)
      $final_timezone_select .= '<option timeZoneId="82" gmtAdjustment="GMT+13:00" useDaylightTime="0" value="13" selected>(GMT+13:00) Nuku\'alofa</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="82" gmtAdjustment="GMT+13:00" useDaylightTime="0" value="13">(GMT+13:00) Nuku\'alofa</option>' ;
    
    $final_timezone_select .= '</select>	' ;
  }
  elseif ($timezone && $timezone_id) // If timezone ID is provided build based on it
  {
    $final_timezone_select = '<select id="timezone">' ;
    
    if ($timezone_id == 1)
      $final_timezone_select .= '<option timeZoneId="1" gmtAdjustment="GMT-12:00" useDaylightTime="0" value="-12" selected>(GMT-12:00) International Date Line West</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="1" gmtAdjustment="GMT-12:00" useDaylightTime="0" value="-12">(GMT-12:00) International Date Line West</option>' ;
    
    if ($timezone_id == 2)
      $final_timezone_select .= '<option timeZoneId="2" gmtAdjustment="GMT-11:00" useDaylightTime="0" value="-11" selected>(GMT-11:00) Midway Island, Samoa</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="2" gmtAdjustment="GMT-11:00" useDaylightTime="0" value="-11">(GMT-11:00) Midway Island, Samoa</option>' ;
    
    if ($timezone_id == 3)
      $final_timezone_select .= '<option timeZoneId="3" gmtAdjustment="GMT-10:00" useDaylightTime="0" value="-10" selected>(GMT-10:00) Hawaii</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="3" gmtAdjustment="GMT-10:00" useDaylightTime="0" value="-10">(GMT-10:00) Hawaii</option>' ;
    
    if ($timezone_id == 4)
      $final_timezone_select .= '<option timeZoneId="4" gmtAdjustment="GMT-09:00" useDaylightTime="1" value="-9" selected>(GMT-09:00) Alaska</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="4" gmtAdjustment="GMT-09:00" useDaylightTime="1" value="-9">(GMT-09:00) Alaska</option>' ;
    
    if ($timezone_id == 5)
      $final_timezone_select .= '<option timeZoneId="5" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8" selected>(GMT-08:00) Pacific Time (US & Canada)</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="5" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8">(GMT-08:00) Pacific Time (US & Canada)</option>' ;
    
    if ($timezone_id == 6)
      $final_timezone_select .= '<option timeZoneId="6" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8" selected>(GMT-08:00) Tijuana, Baja California</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="6" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8">(GMT-08:00) Tijuana, Baja California</option>' ;
    
    if ($timezone_id == 7)
      $final_timezone_select .= '<option timeZoneId="7" gmtAdjustment="GMT-07:00" useDaylightTime="0" value="-7" selected>(GMT-07:00) Arizona</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="7" gmtAdjustment="GMT-07:00" useDaylightTime="0" value="-7">(GMT-07:00) Arizona</option>' ;
    
    if ($timezone_id == 8)
      $final_timezone_select .= '<option timeZoneId="8" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7" selected>(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="8" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>' ;
    
    if ($timezone_id == 9)
      $final_timezone_select .= '<option timeZoneId="9" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7" selected>(GMT-07:00) Mountain Time (US & Canada)</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="9" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7">(GMT-07:00) Mountain Time (US & Canada)</option>' ;
    
    if ($timezone_id == 10)
      $final_timezone_select .= '<option timeZoneId="10" gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-6" selected>(GMT-06:00) Central America</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="10" gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-6">(GMT-06:00) Central America</option>' ;
    
    if ($timezone_id == 11)
      $final_timezone_select .= '<option timeZoneId="11" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6" selected>(GMT-06:00) Central Time (US & Canada)</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="11" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6">(GMT-06:00) Central Time (US & Canada)</option>' ;
    
    if ($timezone_id == 12)
      $final_timezone_select .= '<option timeZoneId="12" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6" selected>(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="12" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>' ;
    
    if ($timezone_id == 13)
      $final_timezone_select .= '<option timeZoneId="13" gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-6" selected>(GMT-06:00) Saskatchewan</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="13" gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-6">(GMT-06:00) Saskatchewan</option>' ;
    
    if ($timezone_id == 14)
      $final_timezone_select .= '<option timeZoneId="14" gmtAdjustment="GMT-05:00" useDaylightTime="0" value="-5" selected>(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="14" gmtAdjustment="GMT-05:00" useDaylightTime="0" value="-5">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>' ;
    
    if ($timezone_id == 15)
      $final_timezone_select .= '<option timeZoneId="15" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5" selected>(GMT-05:00) Eastern Time (US & Canada)</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="15" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5">(GMT-05:00) Eastern Time (US & Canada)</option>' ;
    
    if ($timezone_id == 16)
      $final_timezone_select .= '<option timeZoneId="16" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5" selected>(GMT-05:00) Indiana (East)</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="16" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5">(GMT-05:00) Indiana (East)</option>' ;
    
    if ($timezone_id == 17)
      $final_timezone_select .= '<option timeZoneId="17" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4" selected>(GMT-04:00) Atlantic Time (Canada)</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="17" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4">(GMT-04:00) Atlantic Time (Canada)</option>' ;
     
    if ($timezone_id == 18)
      $final_timezone_select .= '<option timeZoneId="18" gmtAdjustment="GMT-04:00" useDaylightTime="0" value="-4" selected>(GMT-04:00) Caracas, La Paz</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="18" gmtAdjustment="GMT-04:00" useDaylightTime="0" value="-4">(GMT-04:00) Caracas, La Paz</option>' ;
    
    if ($timezone_id == 19)
      $final_timezone_select .= '<option timeZoneId="19" gmtAdjustment="GMT-04:00" useDaylightTime="0" value="-4" selected>(GMT-04:00) Manaus</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="19" gmtAdjustment="GMT-04:00" useDaylightTime="0" value="-4">(GMT-04:00) Manaus</option>' ;
    
    if ($timezone_id == 20)
      $final_timezone_select .= '<option timeZoneId="20" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4" selected>(GMT-04:00) Santiago</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="20" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4">(GMT-04:00) Santiago</option>' ;
    
    if ($timezone_id == 21)
      $final_timezone_select .= '<option timeZoneId="21" gmtAdjustment="GMT-03:30" useDaylightTime="1" value="-3.5" selected>(GMT-03:30) Newfoundland</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="21" gmtAdjustment="GMT-03:30" useDaylightTime="1" value="-3.5">(GMT-03:30) Newfoundland</option>' ;
    
    if ($timezone_id == 22)
      $final_timezone_select .= '<option timeZoneId="22" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3" selected>(GMT-03:00) Brasilia</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="22" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3">(GMT-03:00) Brasilia</option>' ;
    
    if ($timezone_id == 23)
      $final_timezone_select .= '<option timeZoneId="23" gmtAdjustment="GMT-03:00" useDaylightTime="0" value="-3" selected>(GMT-03:00) Buenos Aires, Georgetown</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="23" gmtAdjustment="GMT-03:00" useDaylightTime="0" value="-3">(GMT-03:00) Buenos Aires, Georgetown</option>' ;
    
    if ($timezone_id == 24)
      $final_timezone_select .= '<option timeZoneId="24" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3" selected>(GMT-03:00) Greenland</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="24" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3">(GMT-03:00) Greenland</option>' ;
    
    if ($timezone_id == 25)
      $final_timezone_select .= '<option timeZoneId="25" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3" selected>(GMT-03:00) Montevideo</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="25" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3">(GMT-03:00) Montevideo</option>' ;
    
    if ($timezone_id == 26)
      $final_timezone_select .= '<option timeZoneId="26" gmtAdjustment="GMT-02:00" useDaylightTime="1" value="-2" selected>(GMT-02:00) Mid-Atlantic</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="26" gmtAdjustment="GMT-02:00" useDaylightTime="1" value="-2">(GMT-02:00) Mid-Atlantic</option>' ;
    
    if ($timezone_id == 27)
      $final_timezone_select .= '<option timeZoneId="27" gmtAdjustment="GMT-01:00" useDaylightTime="0" value="-1" selected>(GMT-01:00) Cape Verde Is.</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="27" gmtAdjustment="GMT-01:00" useDaylightTime="0" value="-1">(GMT-01:00) Cape Verde Is.</option>' ;
    
    if ($timezone_id == 28)
      $final_timezone_select .= '<option timeZoneId="28" gmtAdjustment="GMT-01:00" useDaylightTime="1" value="-1" selected>(GMT-01:00) Azores</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="28" gmtAdjustment="GMT-01:00" useDaylightTime="1" value="-1">(GMT-01:00) Azores</option>' ;
    
    if ($timezone_id == 29)
      $final_timezone_select .= '<option timeZoneId="29" gmtAdjustment="GMT+00:00" useDaylightTime="0" value="0" selected>(GMT+00:00) Casablanca, Monrovia, Reykjavik</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="29" gmtAdjustment="GMT+00:00" useDaylightTime="0" value="0">(GMT+00:00) Casablanca, Monrovia, Reykjavik</option>' ;
    
    if ($timezone_id == 30)
      $final_timezone_select .= '<option timeZoneId="30" gmtAdjustment="GMT+00:00" useDaylightTime="1" value="0" selected>(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="30" gmtAdjustment="GMT+00:00" useDaylightTime="1" value="0">(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>' ;
    
    if ($timezone_id == 31)
      $final_timezone_select .= '<option timeZoneId="31" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1" selected>(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="31" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>' ;
    
    if ($timezone_id == 32)
      $final_timezone_select .= '<option timeZoneId="32" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1" selected>(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="32" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>' ;
    
    if ($timezone_id == 33)
      $final_timezone_select .= '<option timeZoneId="33" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1" selected>(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="33" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>' ;
    
    if ($timezone_id == 34)
      $final_timezone_select .= '<option timeZoneId="34" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1" selected>(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="34" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>' ;
    
    if ($timezone_id == 35)
      $final_timezone_select .= '<option timeZoneId="35" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1" selected>(GMT+01:00) West Central Africa</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="35" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) West Central Africa</option>' ;
    
    if ($timezone_id == 36)
      $final_timezone_select .= '<option timeZoneId="36" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2" selected>(GMT+02:00) Amman</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="36" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Amman</option>' ;
    
    if ($timezone_id == 37)
      $final_timezone_select .= '<option timeZoneId="37" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2" selected>(GMT+02:00) Athens, Bucharest, Istanbul</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="37" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Athens, Bucharest, Istanbul</option>' ;
    
    if ($timezone_id == 38)
      $final_timezone_select .= '<option timeZoneId="38" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2" selected>(GMT+02:00) Beirut</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="38" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Beirut</option>' ;
    
    if ($timezone_id == 39)
      $final_timezone_select .= '<option timeZoneId="39" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2" selected>(GMT+02:00) Cairo</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="39" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Cairo</option>' ;
    
    if ($timezone_id == 40)
      $final_timezone_select .= '<option timeZoneId="40" gmtAdjustment="GMT+02:00" useDaylightTime="0" value="2" selected>(GMT+02:00) Harare, Pretoria</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="40" gmtAdjustment="GMT+02:00" useDaylightTime="0" value="2">(GMT+02:00) Harare, Pretoria</option>' ;
    
    if ($timezone_id == 41)
      $final_timezone_select .= '<option timeZoneId="41" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2" selected>(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="41" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>' ;
    
    if ($timezone_id == 42)
      $final_timezone_select .= '<option timeZoneId="42" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2" selected>(GMT+02:00) Jerusalem</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="42" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Jerusalem</option>' ;
    
    if ($timezone_id == 43)
      $final_timezone_select .= '<option timeZoneId="43" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2" selected>(GMT+02:00) Minsk</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="43" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Minsk</option>' ;
    
    if ($timezone_id == 44)
      $final_timezone_select .= '<option timeZoneId="44" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2" selected>(GMT+02:00) Windhoek</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="44" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Windhoek</option>' ;
    
    if ($timezone_id == 45)
      $final_timezone_select .= '<option timeZoneId="45" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3" selected>(GMT+03:00) Kuwait, Riyadh, Baghdad</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="45" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3">(GMT+03:00) Kuwait, Riyadh, Baghdad</option>' ;
    
    if ($timezone_id == 46)
      $final_timezone_select .= '<option timeZoneId="46" gmtAdjustment="GMT+03:00" useDaylightTime="1" value="3" selected>(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="46" gmtAdjustment="GMT+03:00" useDaylightTime="1" value="3">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>' ;
    
    if ($timezone_id == 47)
      $final_timezone_select .= '<option timeZoneId="47" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3" selected>(GMT+03:00) Nairobi</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="47" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3">(GMT+03:00) Nairobi</option>' ;
    
    if ($timezone_id == 48)
      $final_timezone_select .= '<option timeZoneId="48" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3" selected>(GMT+03:00) Tbilisi</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="48" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3">(GMT+03:00) Tbilisi</option>' ;
    
    if ($timezone_id == 49)
      $final_timezone_select .= '<option timeZoneId="49" gmtAdjustment="GMT+03:30" useDaylightTime="1" value="3.5" selected>(GMT+03:30) Tehran</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="49" gmtAdjustment="GMT+03:30" useDaylightTime="1" value="3.5">(GMT+03:30) Tehran</option>' ;
    
    if ($timezone_id == 50)
      $final_timezone_select .= '<option timeZoneId="50" gmtAdjustment="GMT+04:00" useDaylightTime="0" value="4" selected>(GMT+04:00) Abu Dhabi, Muscat</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="50" gmtAdjustment="GMT+04:00" useDaylightTime="0" value="4">(GMT+04:00) Abu Dhabi, Muscat</option>' ;
    
    if ($timezone_id == 51)
      $final_timezone_select .= '<option timeZoneId="51" gmtAdjustment="GMT+04:00" useDaylightTime="1" value="4" selected>(GMT+04:00) Baku</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="51" gmtAdjustment="GMT+04:00" useDaylightTime="1" value="4">(GMT+04:00) Baku</option>' ;
    
    if ($timezone_id == 52)
      $final_timezone_select .= '<option timeZoneId="52" gmtAdjustment="GMT+04:00" useDaylightTime="1" value="4" selected>(GMT+04:00) Yerevan</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="52" gmtAdjustment="GMT+04:00" useDaylightTime="1" value="4">(GMT+04:00) Yerevan</option>' ;
    
    if ($timezone_id == 53)
      $final_timezone_select .= '<option timeZoneId="53" gmtAdjustment="GMT+04:30" useDaylightTime="0" value="4.5" selected>(GMT+04:30) Kabul</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="53" gmtAdjustment="GMT+04:30" useDaylightTime="0" value="4.5">(GMT+04:30) Kabul</option>' ;
    
    if ($timezone_id == 54)
      $final_timezone_select .= '<option timeZoneId="54" gmtAdjustment="GMT+05:00" useDaylightTime="1" value="5" selected>(GMT+05:00) Yekaterinburg</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="54" gmtAdjustment="GMT+05:00" useDaylightTime="1" value="5">(GMT+05:00) Yekaterinburg</option>' ;
    
    if ($timezone_id == 55)
      $final_timezone_select .= '<option timeZoneId="55" gmtAdjustment="GMT+05:00" useDaylightTime="0" value="5" selected>(GMT+05:00) Islamabad, Karachi, Tashkent</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="55" gmtAdjustment="GMT+05:00" useDaylightTime="0" value="5">(GMT+05:00) Islamabad, Karachi, Tashkent</option>' ;
    
    if ($timezone_id == 56)
      $final_timezone_select .= '<option timeZoneId="56" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="5.5" selected>(GMT+05:30) Sri Jayawardenapura</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="56" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="5.5">(GMT+05:30) Sri Jayawardenapura</option>' ;
    
    if ($timezone_id == 57)
      $final_timezone_select .= '<option timeZoneId="57" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="5.5" selected>(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="57" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="5.5">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>' ;
    
    if ($timezone_id == 58)
      $final_timezone_select .= '<option timeZoneId="58" gmtAdjustment="GMT+05:45" useDaylightTime="0" value="5.75" selected>(GMT+05:45) Kathmandu</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="58" gmtAdjustment="GMT+05:45" useDaylightTime="0" value="5.75">(GMT+05:45) Kathmandu</option>' ;
    
    if ($timezone_id == 59)
      $final_timezone_select .= '<option timeZoneId="59" gmtAdjustment="GMT+06:00" useDaylightTime="1" value="6" selected>(GMT+06:00) Almaty, Novosibirsk</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="59" gmtAdjustment="GMT+06:00" useDaylightTime="1" value="6">(GMT+06:00) Almaty, Novosibirsk</option>' ;
    
    if ($timezone_id == 60)
      $final_timezone_select .= '<option timeZoneId="60" gmtAdjustment="GMT+06:00" useDaylightTime="0" value="6" selected>(GMT+06:00) Astana, Dhaka</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="60" gmtAdjustment="GMT+06:00" useDaylightTime="0" value="6">(GMT+06:00) Astana, Dhaka</option>' ;
    
    if ($timezone_id == 61)
      $final_timezone_select .= '<option timeZoneId="61" gmtAdjustment="GMT+06:30" useDaylightTime="0" value="6.5" selected>(GMT+06:30) Yangon (Rangoon)</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="61" gmtAdjustment="GMT+06:30" useDaylightTime="0" value="6.5">(GMT+06:30) Yangon (Rangoon)</option>' ;
    
    if ($timezone_id == 62)
      $final_timezone_select .= '<option timeZoneId="62" gmtAdjustment="GMT+07:00" useDaylightTime="0" value="7" selected>(GMT+07:00) Bangkok, Hanoi, Jakarta</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="62" gmtAdjustment="GMT+07:00" useDaylightTime="0" value="7">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>' ;
    
    if ($timezone_id == 63)
      $final_timezone_select .= '<option timeZoneId="63" gmtAdjustment="GMT+07:00" useDaylightTime="1" value="7" selected>(GMT+07:00) Krasnoyarsk</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="63" gmtAdjustment="GMT+07:00" useDaylightTime="1" value="7">(GMT+07:00) Krasnoyarsk</option>' ;
    
    if ($timezone_id == 64)
      $final_timezone_select .= '<option timeZoneId="64" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8" selected>(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="64" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>' ;
    
    if ($timezone_id == 65)
      $final_timezone_select .= '<option timeZoneId="65" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8" selected>(GMT+08:00) Kuala Lumpur, Singapore</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="65" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Kuala Lumpur, Singapore</option>' ;
    
    if ($timezone_id == 66)
      $final_timezone_select .= '<option timeZoneId="66" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8" selected>(GMT+08:00) Irkutsk, Ulaan Bataar</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="66" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Irkutsk, Ulaan Bataar</option>' ;
    
    if ($timezone_id == 67)
      $final_timezone_select .= '<option timeZoneId="67" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8" selected>(GMT+08:00) Perth</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="67" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Perth</option>' ;
    
    if ($timezone_id == 68)
      $final_timezone_select .= '<option timeZoneId="68" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8" selected>(GMT+08:00) Taipei</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="68" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Taipei</option>' ;
    
    if ($timezone_id == 69)
      $final_timezone_select .= '<option timeZoneId="69" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="9" selected>(GMT+09:00) Osaka, Sapporo, Tokyo</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="69" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="9">(GMT+09:00) Osaka, Sapporo, Tokyo</option>' ;
    
    if ($timezone_id == 70)
      $final_timezone_select .= '<option timeZoneId="70" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="9" selected>(GMT+09:00) Seoul</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="70" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="9">(GMT+09:00) Seoul</option>' ;
    
    if ($timezone_id == 71)
      $final_timezone_select .= '<option timeZoneId="71" gmtAdjustment="GMT+09:00" useDaylightTime="1" value="9" selected>(GMT+09:00) Yakutsk</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="71" gmtAdjustment="GMT+09:00" useDaylightTime="1" value="9">(GMT+09:00) Yakutsk</option>' ;
    
    if ($timezone_id == 72)
      $final_timezone_select .= '<option timeZoneId="72" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="9.5" selected>(GMT+09:30) Adelaide</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="72" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="9.5">(GMT+09:30) Adelaide</option>' ;
    
    if ($timezone_id == 73)
      $final_timezone_select .= '<option timeZoneId="73" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="9.5" selected>(GMT+09:30) Darwin</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="73" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="9.5">(GMT+09:30) Darwin</option>' ;
    
    if ($timezone_id == 74)
      $final_timezone_select .= '<option timeZoneId="74" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="10" selected>(GMT+10:00) Brisbane</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="74" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="10">(GMT+10:00) Brisbane</option>' ;
    
    if ($timezone_id == 75)
      $final_timezone_select .= '<option timeZoneId="75" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10" selected>(GMT+10:00) Canberra, Melbourne, Sydney</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="75" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10">(GMT+10:00) Canberra, Melbourne, Sydney</option>' ;
    
    if ($timezone_id == 76)
      $final_timezone_select .= '<option timeZoneId="76" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10" selected>(GMT+10:00) Hobart</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="76" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10">(GMT+10:00) Hobart</option>' ;
    
    if ($timezone_id == 77)
      $final_timezone_select .= '<option timeZoneId="77" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="10" selected>(GMT+10:00) Guam, Port Moresby</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="77" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="10">(GMT+10:00) Guam, Port Moresby</option>' ;
    
    if ($timezone_id == 78)
      $final_timezone_select .= '<option timeZoneId="78" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10" selected>(GMT+10:00) Vladivostok</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="78" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10">(GMT+10:00) Vladivostok</option>' ;
    
    if ($timezone_id == 79)
      $final_timezone_select .= '<option timeZoneId="79" gmtAdjustment="GMT+11:00" useDaylightTime="1" value="11" selected>(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="79" gmtAdjustment="GMT+11:00" useDaylightTime="1" value="11">(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>' ;
    
    if ($timezone_id == 80)
      $final_timezone_select .= '<option timeZoneId="80" gmtAdjustment="GMT+12:00" useDaylightTime="1" value="12" selected>(GMT+12:00) Auckland, Wellington</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="80" gmtAdjustment="GMT+12:00" useDaylightTime="1" value="12">(GMT+12:00) Auckland, Wellington</option>' ;
    
    if ($timezone_id == 81)
      $final_timezone_select .= '<option timeZoneId="81" gmtAdjustment="GMT+12:00" useDaylightTime="0" value="12" selected>(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="81" gmtAdjustment="GMT+12:00" useDaylightTime="0" value="12">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>' ;
    
    if ($timezone_id == 82)
      $final_timezone_select .= '<option timeZoneId="82" gmtAdjustment="GMT+13:00" useDaylightTime="0" value="13" selected>(GMT+13:00) Nuku\'alofa</option>' ;
    else
      $final_timezone_select .= '<option timeZoneId="82" gmtAdjustment="GMT+13:00" useDaylightTime="0" value="13">(GMT+13:00) Nuku\'alofa</option>' ;
    
    
    $final_timezone_select .= '</select>	' ;
  }
  else // If no timezone at all build default select input
  {
    $final_timezone_select = '<select id="timezone">
    <option timeZoneId="1" gmtAdjustment="GMT-12:00" useDaylightTime="0" value="-12">(GMT-12:00) International Date Line West</option>
    <option timeZoneId="2" gmtAdjustment="GMT-11:00" useDaylightTime="0" value="-11">(GMT-11:00) Midway Island, Samoa</option>
    <option timeZoneId="3" gmtAdjustment="GMT-10:00" useDaylightTime="0" value="-10">(GMT-10:00) Hawaii</option>
    <option timeZoneId="4" gmtAdjustment="GMT-09:00" useDaylightTime="1" value="-9">(GMT-09:00) Alaska</option>
    <option timeZoneId="5" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8">(GMT-08:00) Pacific Time (US & Canada)</option>
    <option timeZoneId="6" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8">(GMT-08:00) Tijuana, Baja California</option>
    <option timeZoneId="7" gmtAdjustment="GMT-07:00" useDaylightTime="0" value="-7">(GMT-07:00) Arizona</option>
    <option timeZoneId="8" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
    <option timeZoneId="9" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7">(GMT-07:00) Mountain Time (US & Canada)</option>
    <option timeZoneId="10" gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-6">(GMT-06:00) Central America</option>
    <option timeZoneId="11" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6">(GMT-06:00) Central Time (US & Canada)</option>
    <option timeZoneId="12" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
    <option timeZoneId="13" gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-6">(GMT-06:00) Saskatchewan</option>
    <option timeZoneId="14" gmtAdjustment="GMT-05:00" useDaylightTime="0" value="-5">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
    <option timeZoneId="15" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5">(GMT-05:00) Eastern Time (US & Canada)</option>
    <option timeZoneId="16" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5">(GMT-05:00) Indiana (East)</option>
    <option timeZoneId="17" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4">(GMT-04:00) Atlantic Time (Canada)</option>
    <option timeZoneId="18" gmtAdjustment="GMT-04:00" useDaylightTime="0" value="-4">(GMT-04:00) Caracas, La Paz</option>
    <option timeZoneId="19" gmtAdjustment="GMT-04:00" useDaylightTime="0" value="-4">(GMT-04:00) Manaus</option>
    <option timeZoneId="20" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4">(GMT-04:00) Santiago</option>
    <option timeZoneId="21" gmtAdjustment="GMT-03:30" useDaylightTime="1" value="-3.5">(GMT-03:30) Newfoundland</option>
    <option timeZoneId="22" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3">(GMT-03:00) Brasilia</option>
    <option timeZoneId="23" gmtAdjustment="GMT-03:00" useDaylightTime="0" value="-3">(GMT-03:00) Buenos Aires, Georgetown</option>
    <option timeZoneId="24" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3">(GMT-03:00) Greenland</option>
    <option timeZoneId="25" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3">(GMT-03:00) Montevideo</option>
    <option timeZoneId="26" gmtAdjustment="GMT-02:00" useDaylightTime="1" value="-2">(GMT-02:00) Mid-Atlantic</option>
    <option timeZoneId="27" gmtAdjustment="GMT-01:00" useDaylightTime="0" value="-1">(GMT-01:00) Cape Verde Is.</option>
    <option timeZoneId="28" gmtAdjustment="GMT-01:00" useDaylightTime="1" value="-1">(GMT-01:00) Azores</option>
    <option timeZoneId="29" gmtAdjustment="GMT+00:00" useDaylightTime="0" value="0">(GMT+00:00) Casablanca, Monrovia, Reykjavik</option>
    <option timeZoneId="30" gmtAdjustment="GMT+00:00" useDaylightTime="1" value="0">(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>
    <option timeZoneId="31" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
    <option timeZoneId="32" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
    <option timeZoneId="33" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
    <option timeZoneId="34" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
    <option timeZoneId="35" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) West Central Africa</option>
    <option timeZoneId="36" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Amman</option>
    <option timeZoneId="37" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Athens, Bucharest, Istanbul</option>
    <option timeZoneId="38" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Beirut</option>
    <option timeZoneId="39" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Cairo</option>
    <option timeZoneId="40" gmtAdjustment="GMT+02:00" useDaylightTime="0" value="2">(GMT+02:00) Harare, Pretoria</option>
    <option timeZoneId="41" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
    <option timeZoneId="42" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Jerusalem</option>
    <option timeZoneId="43" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Minsk</option>
    <option timeZoneId="44" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Windhoek</option>
    <option timeZoneId="45" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3">(GMT+03:00) Kuwait, Riyadh, Baghdad</option>
    <option timeZoneId="46" gmtAdjustment="GMT+03:00" useDaylightTime="1" value="3">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
    <option timeZoneId="47" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3">(GMT+03:00) Nairobi</option>
    <option timeZoneId="48" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3">(GMT+03:00) Tbilisi</option>
    <option timeZoneId="49" gmtAdjustment="GMT+03:30" useDaylightTime="1" value="3.5">(GMT+03:30) Tehran</option>
    <option timeZoneId="50" gmtAdjustment="GMT+04:00" useDaylightTime="0" value="4">(GMT+04:00) Abu Dhabi, Muscat</option>
    <option timeZoneId="51" gmtAdjustment="GMT+04:00" useDaylightTime="1" value="4">(GMT+04:00) Baku</option>
    <option timeZoneId="52" gmtAdjustment="GMT+04:00" useDaylightTime="1" value="4">(GMT+04:00) Yerevan</option>
    <option timeZoneId="53" gmtAdjustment="GMT+04:30" useDaylightTime="0" value="4.5">(GMT+04:30) Kabul</option>
    <option timeZoneId="54" gmtAdjustment="GMT+05:00" useDaylightTime="1" value="5">(GMT+05:00) Yekaterinburg</option>
    <option timeZoneId="55" gmtAdjustment="GMT+05:00" useDaylightTime="0" value="5">(GMT+05:00) Islamabad, Karachi, Tashkent</option>
    <option timeZoneId="56" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="5.5">(GMT+05:30) Sri Jayawardenapura</option>
    <option timeZoneId="57" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="5.5">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
    <option timeZoneId="58" gmtAdjustment="GMT+05:45" useDaylightTime="0" value="5.75">(GMT+05:45) Kathmandu</option>
    <option timeZoneId="59" gmtAdjustment="GMT+06:00" useDaylightTime="1" value="6">(GMT+06:00) Almaty, Novosibirsk</option>
    <option timeZoneId="60" gmtAdjustment="GMT+06:00" useDaylightTime="0" value="6">(GMT+06:00) Astana, Dhaka</option>
    <option timeZoneId="61" gmtAdjustment="GMT+06:30" useDaylightTime="0" value="6.5">(GMT+06:30) Yangon (Rangoon)</option>
    <option timeZoneId="62" gmtAdjustment="GMT+07:00" useDaylightTime="0" value="7">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
    <option timeZoneId="63" gmtAdjustment="GMT+07:00" useDaylightTime="1" value="7">(GMT+07:00) Krasnoyarsk</option>
    <option timeZoneId="64" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
    <option timeZoneId="65" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Kuala Lumpur, Singapore</option>
    <option timeZoneId="66" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
    <option timeZoneId="67" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Perth</option>
    <option timeZoneId="68" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Taipei</option>
    <option timeZoneId="69" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="9">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
    <option timeZoneId="70" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="9">(GMT+09:00) Seoul</option>
    <option timeZoneId="71" gmtAdjustment="GMT+09:00" useDaylightTime="1" value="9">(GMT+09:00) Yakutsk</option>
    <option timeZoneId="72" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="9.5">(GMT+09:30) Adelaide</option>
    <option timeZoneId="73" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="9.5">(GMT+09:30) Darwin</option>
    <option timeZoneId="74" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="10">(GMT+10:00) Brisbane</option>
    <option timeZoneId="75" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10">(GMT+10:00) Canberra, Melbourne, Sydney</option>
    <option timeZoneId="76" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10">(GMT+10:00) Hobart</option>
    <option timeZoneId="77" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="10">(GMT+10:00) Guam, Port Moresby</option>
    <option timeZoneId="78" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10">(GMT+10:00) Vladivostok</option>
    <option timeZoneId="79" gmtAdjustment="GMT+11:00" useDaylightTime="1" value="11">(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>
    <option timeZoneId="80" gmtAdjustment="GMT+12:00" useDaylightTime="1" value="12">(GMT+12:00) Auckland, Wellington</option>
    <option timeZoneId="81" gmtAdjustment="GMT+12:00" useDaylightTime="0" value="12">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
    <option timeZoneId="82" gmtAdjustment="GMT+13:00" useDaylightTime="0" value="13">(GMT+13:00) Nuku\'alofa</option>
    </select>	' ;
  }
  
  return $final_timezone_select ;
  
} // buildTimezoneSelectInput

?>