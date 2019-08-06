<?php

// Error rendering
function displayErrorMessages()
{
  $error_json = '' ;
  
  // Connect to db
  $dbc = db_connect_ut() ;
  
  // Check the error_ahndling table for any current ERROR message
  // Current error message have end_timestamp = NULL
  // Check if there is an awaiting polly tracker data to be saved
  $query = "SELECT error_handling.id AS error_id, error_code.code, error_code.description
             FROM error_handling
             INNER JOIN error_code ON error_code.id = error_handling.errorcode_id
        WHERE end_timestamp IS NULL"
        . " ORDER BY begin_timestamp DESC LIMIT 1" ;
  $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
  if (@mysqli_num_rows($result) != 0)
  {
    // Build the JSON output for the current errors
    $error_json .= '{"errors": [' ;
    $error_list = '' ;
    
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
    {
      if (strlen($error_list) == 0)
        $error_list .= '{"' . $row['code'] . '":"' . $row['description'] . '"}' ;
      else
        $error_list .= ',{"' . $row['code'] . '":"' . $row['description'] . '"}' ;
    }
    
    $error_json .= $error_list . ']' ;
    
    // Add the status messages to complete the JSON error file
    $error_json .= ',"message":' . '"' . $msg . '"'  ;
    $error_json .= ',"data_sending": true'  ;
    
    // Finish the error JSON file
    $error_json .= '}'  ;
    
  }
  else
  {
    $error_json = '{"errors": "","message": "Data sending normally from PolluTrackers", "data_sending": true}' ;
  }
  
  // Close db
  db_close($dbc) ;
  
  return $error_json ; 
}

?>
