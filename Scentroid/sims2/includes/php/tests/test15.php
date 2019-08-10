<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
//require_once(TEMPLATES_PATH . "/template_main.php");

// Connect to db
$dbc_local = db_connect_local() ;

$query2 = "SELECT settings.id AS setting_id, settings_json
      FROM settings
      WHERE user_id = " . 10 ;
$result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local));
$row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
if (@mysqli_num_rows($result2) != 0) 
{
  $sensor_json = $row2[1] ;
  $sensor_obj = json_decode($sensor_json) ;

  // Get just the AQI data
  $aqi_obj = $sensor_obj->aqi ;
  

  // Get the last updated value and construct the other values based on it (for now!)
  foreach ($aqi_obj as $aqi)
  {
    echo $aqi->chemical . ', ' ;
    //echo $aqi->parameters[0]  . ', ' . $aqi->parameters[1]  . '; ' ;
    $param_obj = $aqi->parameters ;
    foreach ($param_obj as $param)
      echo $param . ', '; 
      //$last_sample_value = $sensor->value ;
  }
  //$last_sample_time = timeElapsedString($row2[2]) ;

  //echo $last_sample_value . '; ' ;
  //echo $last_sample_time ;

  
  //var_dump($aqi) ;
  
}
else
{
  echo 'Nothing!' ;
}


// Close db
db_close($dbc_local) ;

?>