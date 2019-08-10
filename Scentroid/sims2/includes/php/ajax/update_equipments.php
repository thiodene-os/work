<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
// This saves all the Settings information for the Equipment
// sends back the script for the Saving status

$dbc = db_connect_sims() ;

$my_ajax_html = '' ;
if (isset($_GET['update'])) 
{
  // Get all the Settings info from JSON
  $equipment_json = $_GET['info'] ;
  
  // Decode JSON
  $equipment_obj = json_decode($equipment_json) ;
  
  // Get all the info (screened by Javascript!)
  $equipment_id = $equipment_obj->equipment_id ;
  $name = $equipment_obj->name ;
  $sn = $equipment_obj->sn ;
  $status = $equipment_obj->status ;
  $company_id = $equipment_obj->company_id ;
  $calibrate_date = strtotime($equipment_obj->calibrate_date) ;
  $createdat = strtotime("now") ;
  $secret = md5(rand())."-".time();
  // For now! Don't forget to build equipment Settings page....
  $extra = '{"SAMPLING_INTERVAL_TIME_KEY_NAME":"","RECORDING_INTERVAL_TIME_KEY_NAME":"","SAMPLING_MOTOR_WAIT_TIME_KEY_NAME":"","MAIN_PUMP_SPEED_FOR_SAMPLING_KEY_NAME":"","USED_SAMPLING_PORT_KEY_NAME":"","TRANSMITTING_INTERVAL_TIME_CLOUD_SERVER_KEY_NAME":"","TRANSMITTING_INTERVAL_TIME_LOCAL_SERVER_KEY_NAME":"","TRANSMITTING_INTERVAL_TIME_ONBOARD_SERVER_KEY_NAME":"","PURGE_INTERVAL_TIME_KEY_NAME":"","PURGE_WITH_OZONE_DURATION_KEY_NAME":"","PURGE_WITHOUT_OZONE_DURATION_KEY_NAME":"","MAIN_PUMP_SPEED_FOR_PURGING_KEY_NAME":"","AC_OFF_TEMPERATURE_KEY_NAME":"","AC_ON_TEMPERATURE_KEY_NAME":"","updatedat":"","seq_num":"","ack":""}';
  
  // First see if there is a user_id or not
  if (strlen($equipment_id) == 0)
  {
    
    // If no previous INSERT the new Equipment
    $query = "INSERT INTO equipement (name, status, sn, secret, extra, company, calibratedate, createdat, creator) VALUES ('" 
              . $name . "', '" . $status . "', '" . $sn . "', '" . $secret . "', '" . $extra . "', '" . $company_id 
              . "', '"  . $calibrate_date . "', '"  . $createdat . "', 10); " ;
    $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc)) ;
    if (mysqli_affected_rows($dbc) == 0)
      $my_ajax_html = '<span style="color:red">Equipment (INSERT) failed!</span>' ;
  }
  else
  {
    // Update the Settings info for that user
    $query = "UPDATE equipement 
               SET name='$name', status= '$status', sn='$sn'
               , company='$company_id', calibratedate='$calibrate_date'
                WHERE id = " . $equipment_id ;
    $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc)) ;
    
    if (mysqli_affected_rows($dbc) == 0)
    {
      $my_ajax_html = '<span style="color:red">Equipment (UPDATE) failed!</span>' ;
    }
    
  }
  
}
else
{
  // If delete 
  if (isset($_GET['delete'])) 
  {
    // Delete the user if it doesn't have "Manager" or "Admin" roles
    $equipment_id = $_GET['equipment_id'] ;
    
    // Simply delete the Equipment (Don't delete id-> 1 Million)
    $query3 = "DELETE FROM equipement WHERE id='$equipment_id' LIMIT 1";
    //$query3 = "DELETE FROM equipement WHERE id='1000000' LIMIT 1";
    $result3 = mysqli_query($dbc, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc));
    if (mysqli_affected_rows($dbc) == 0)
      $my_ajax_html = 'This Equipment could not be deleted!' ;
  }
  else
  {
    // If no equipment ID HTML has to be empty
    $my_ajax_html = '<span style="color:red">Equipment update failed!</span>' ;
  }
}


db_close($dbc) ;

echo $my_ajax_html ;

?>