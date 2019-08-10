<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
require_once(TEMPLATES_PATH . "/template_main.php");


// Connect to db
$dbc = db_connect_local() ;

$current = 31 ;
// Get the last sample measured by that Equipment
$query = "SELECT lastvalue_equipment.id AS lastvalue_id, value_per_sensor
      FROM lastvalue_equipment
      WHERE id = " . $current ;
$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
$row = mysqli_fetch_array($result, MYSQLI_NUM) ;

$json = $row[1] ;
$obj = json_decode($json) ;

//foreach ($obj as $key => $value)
foreach ($obj as $value)
{
  //echo "Key:" . $key . ", Value:" . strtolower($value->name) . " <br />" ;
  echo "Value:" . strtolower($value->name) . " <br />" ;
}

//echo $obj->VOCs[0] ;

// Close db
db_close($dbc) ;

?>