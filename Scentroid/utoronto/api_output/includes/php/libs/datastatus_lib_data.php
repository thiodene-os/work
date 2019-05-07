<?php

// SQL + Email or SMS handling and sending
function dataReceivingStatusSwitcher($receiving_status=false)
{
  $data_status_json = '' ;
  $data_handler_id = 1 ;
  
  // Connect to db
  $dbc = db_connect_ut() ;
  
  // If receiving data set handler to 'r': receiving
  // If not set handler to NULL
  if ($receiving_status)
  {
    // UPDATE session small data table
    $query = "UPDATE data_handling SET handler='r'"
              . " WHERE id = " . $data_handler_id ;
    $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc)) ;
  }
  else
  {
    // UPDATE session small data table
    $query = "UPDATE data_handling SET handler=NULL"
              . " WHERE id = " . $data_handler_id ;
    $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc)) ;
  }
  
  // Now check the current status of the Handler in the DB
  $query2 = "SELECT data_handling.id AS data_id, handler
             FROM data_handling
        WHERE id = " . $data_handler_id ;
  $result2 = mysqli_query($dbc, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc));
  if (@mysqli_num_rows($result2) != 0)
  {
    $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
    $handler = $row2[0] ;
    // Build the json output correspondign to the receiving status of the system
    if ($handler)
      $data_status_json = '{"data_receiving": true}' ;
    else
      $data_status_json = '{"data_receiving": false}' ;
  }
  else
  {
    $data_status_json = '{"data_receiving": false}' ;
  }
  
  // Close db
  db_close($dbc) ;
  
  return $data_status_json ; 
  
} // dataReceivingStatusSwitcher

?>
