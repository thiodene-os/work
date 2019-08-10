<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
// This saves all the Settings information chosen by the user
// sends back the script for the Saving status

// Connect to db
$dbc = db_connect_sims() ;
$dbc_local = db_connect_local() ;

if (isset($_POST['save'])) 
{
  $my_ajax_html = '' ;
  // Get all the Settings info from JSON
  $json_settings = $_POST['info'] ;
  
  // First save the company information
  $settings_obj = json_decode($json_settings) ;
  $company_obj = $settings_obj->company ;
  
  // UPDATE the company info to SIMS1
  $query3 = "UPDATE company
             SET name= '" . $company_obj->company_name . "', city='" . $company_obj->city . "',"
             . "timezone= '" . $company_obj->timezones[0] . "', alarm_email='" . $company_obj->alarm_email . "',"
             . "address='" . $company_obj->address . "'"
             . " WHERE company.id = " . $company_obj->company_id ;
  $result3 = mysqli_query($dbc, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc)) ;
  
  
  // First SELECT the Settings for that user if exists
  $query = "SELECT settings.id AS setting_id, user_id
        FROM settings
        WHERE user_id = " . $_SESSION['id'] ;
  $result = mysqli_query($dbc_local, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
  if (mysqli_num_rows($result) == 0)
  {
    // If no previous Settings record INSERT the JSON Data as it is (for now)
    $query2 = "INSERT INTO settings (user_id, settings_json, notification_dt, updated_dt) VALUES (" 
              . $_SESSION['id'] . ", '" . $json_settings . "', NOW(), NOW()" . "); " ;
    $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
    if (mysqli_affected_rows($dbc_local) == 0)
      $my_ajax_html = '<span>Settings saving (INSERT) failed!</span>' ;
  }
  else
  {
    $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
    $user_id = $row[1] ;
    // If exists UPDATE the Settings info
    // Query the database.
    
    // Update the Settings info for that user
    $query2 = "UPDATE settings 
               SET settings_json= '$json_settings', updated_dt=NOW()"
              . " WHERE user_id = " . $user_id ;
    $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
    
    if (@mysqli_num_rows($result2) != 0)
    {
      $my_ajax_html = '<span>Settings saving (UPDATE) failed!</span>' ;
    }
    
  }
  
}
else
{
  // If no equipment ID HTML has to be empty
  $my_ajax_html = '<span>Settings saving failed!</span>' ;
}

// Close db
db_close($dbc) ;
db_close($dbc_local) ;

echo $my_ajax_html ;

?>