<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
//require_once(TEMPLATES_PATH . "/template_main.php");

// Connect to db
$dbc_local = db_connect_local() ;

// Test equipment value
$equipment = 43 ;
$sensor_id = 240 ;

$query2 = "SELECT lastvalue_equipment.id AS lastvalue_id, value_per_sensor, sampledat
      FROM lastvalue_equipment
      WHERE equipment_id = " . $equipment ;
$result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local));
$row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
if (@mysqli_num_rows($result2) != 0) 
{
  $sensor_json = $row2[1] ;
  //$sensor_json = preg_replace('/\s+/', '',$sensor_json);
  $sensor_json = utf8_encode($sensor_json);
  $sensor_obj = json_decode($sensor_json) ;

  // Get the last updated value and construct the other values based on it (for now!)
  foreach ($sensor_obj as $sensor)
  {
    if ($sensor->id == $sensor_id)
      $last_sample_value = $sensor->value ;
  }
  $last_sample_time = timeElapsedString($row2[2]) ;

  echo $last_sample_value . '; ' ;
  echo $last_sample_time ;
  var_dump($sensor_json) ;
}
else
{
  echo 'Nothing!' ;
}

// Close db
db_close($dbc_local) ;

?>