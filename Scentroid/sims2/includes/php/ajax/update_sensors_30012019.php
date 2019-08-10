<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
// This saves all the Settings information for the Equipment
// sends back the script for the Saving status

$dbc = db_connect_sims() ;

$my_ajax_html = '' ;
if (isset($_GET['update'])) 
{
  // Get all the Sensors info from JSON
  $sensor_json = $_GET['info'] ;
  
  // Decode JSON
  $sensor_obj = json_decode($sensor_json) ;
  
  // Get all the info (screened by Javascript!)
  $equipment_id = $sensor_obj->equipment_id ;
  $sensor_id = $sensor_obj->sensor_id ;
  $name = $sensor_obj->name ;
  $packet_id = $sensor_obj->packet_id ;
  $type = $sensor_obj->type ;
  $dataunit = $sensor_obj->dataunit ;
  
  $alarm_max_value = $sensor_obj->alarm_max_value ;
  $alarm_value_total = $sensor_obj->alarm_value_total ;
  $alarm_value_num = $sensor_obj->alarm_value_num ;
  
  $zero_offset = $sensor_obj->zero_offset ;
  $sensitivity = $sensor_obj->sensitivity ;
  $max_sensitivity = $sensor_obj->max_sensitivity ;
  $min_sensitivity = $sensor_obj->min_sensitivity ;
  $relay_trigger_limit = $sensor_obj->relay_trigger_limit ;
  $relay_trigger_comparison = $sensor_obj->relay_trigger_comparison ;
  $relay_number = $sensor_obj->relay_number ;
  
  $updatedat = strtotime("now") ;
  $createdat = strtotime("now") ;
  // For now! Don't forget to build equipment Settings page....
  $extra = '{"ZERO_OFFSET_VOLTAGE_KEY_NAME":"' . $zero_offset . '"'
  . ',"SENSOR_SENSITIVITY_KEY_NAME":"' . $sensitivity . '"'
  . ',"MAXIMUM_SENSITIVITY_RANGE_KEY_NAME":"' . $max_sensitivity . '"'
  . ',"MINIMUM_SENSITIVITY_RANGE_KEY_NAME":"' . $min_sensitivity . '"'
  . ',"RELAY_TRIGGER_LIMIT_KEY_NAME":"' . $relay_trigger_limit . '"'
  . ',"RELAY_TRIGGER_COMPARISON_KEY_NAME":"' . $relay_trigger_comparison . '"'
  . ',"ASSOCIATED_RELAY_NUMBER_KEY_NAME":"' . $relay_number . '"'
  . ',"updatedat":' . $updatedat . '}' ;
  
  // First see if there is a user_id or not
  if (strlen($sensor_id) == 0)
  {
    
    // If no previous INSERT the new Sensor
    $query = "INSERT INTO sensor (name, packet_id, equipement, type, dataunit, calibrationfactors, "
              . "alarm_max_value, alarm_value_total, alarm_value_num, extra, createdat, creator) VALUES ('" 
              . $name . "', '" . $packet_id . "', '" . $equipment_id . "', '" 
              .  $type . "', '" . $dataunit . "', '[\"\",\"\"]', '"
              . $alarm_max_value . "', '" . $alarm_value_total . "', '" . $alarm_value_num
              . "', '"  . $extra . "', '"  . $createdat . "', 10); " ;
    $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc)) ;
    if (mysqli_affected_rows($dbc) == 0)
      $my_ajax_html = '<span style="color:red">Sensor (INSERT) failed!</span>' ;
  }
  else
  {
    // Update the Settings info for that user
    $query = "UPDATE sensor 
               SET name='$name', packet_id= '$packet_id', equipement='$equipment_id', type='$type' ,dataunit='$dataunit'
               , alarm_max_value='$alarm_max_value', alarm_value_total='$alarm_value_total', alarm_value_num='$alarm_value_num'
               , extra='$extra'
                WHERE id = " . $sensor_id ;
    $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc)) ;
    
    if (mysqli_affected_rows($dbc) == 0)
    {
      $my_ajax_html = '<span style="color:red">Sensor (UPDATE) failed!</span>' ;
    }
    
  }
  
}
else
{
  // If delete 
  if (isset($_GET['delete'])) 
  {
    // Delete the sensor
    $sensor_id = $_GET['sensor_id'] ;
    
    // Simply delete the Sensor (Don't delete id-> 1 Million)
    $query3 = "DELETE FROM sensor WHERE id='$sensor_id' LIMIT 1";
    //$query3 = "DELETE FROM sensor WHERE id='1000000' LIMIT 1";
    $result3 = mysqli_query($dbc, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc));
    if (mysqli_affected_rows($dbc) == 0)
      $my_ajax_html = 'This Sensor could not be deleted!' ;
  }
  else
  {
    // If no successful update print message
    $my_ajax_html = '<span style="color:red">Sensor update failed!</span>' ;
  }
}


db_close($dbc) ;

echo $my_ajax_html ;

?>