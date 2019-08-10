<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
//require_once(TEMPLATES_PATH . "/template_main.php");

// Connect to db
$dbc = db_connect_sims() ;

$query = "SELECT sensor.id AS sensor_id, dataunit
      FROM sensor
      WHERE BINARY dataunit = 'PPB'" // Case sensitive use BINARY
      . " ORDER BY id ASC LIMIT 5" ; // Same for 'PPM'
$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
{
  $sensor_id = $row['sensor_id'] ;
  
  // Update the Sensor info for Data Unit
  $query2 = "UPDATE sensor 
             SET dataunit='ppb'
              WHERE id = " . $sensor_id ;
  $result2 = mysqli_query($dbc, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc)) ;
  
  if (mysqli_affected_rows($dbc) == 0)
    echo '<span style="color:red">Sensor (UPDATE) failed!</span>' . "<br />" ;
  else
    echo 'Sensor ID: ' . $sensor_id . "<br />";
  
}

// Close db
db_close($dbc) ;

?>