<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
//require_once(TEMPLATES_PATH . "/template_main.php");

// Connect to db
$dbc_local = db_connect_local() ;

$query = "SELECT DISTINCT (equipment_id) 
          FROM activity_equipment" ;
$result = mysqli_query($dbc_local, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc_local));
if (@mysqli_num_rows($result) != 0) 
{
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
  {
    $equipment_id = $row['equipment_id'] ;
    $iter = 1 ;
    // Now select the very last Notification and turn it to active
    $query2 = "SELECT activity_equipment.id AS activity_id, status_id, category_id
          FROM activity_equipment
          WHERE equipment_id = " . $equipment_id
         . " ORDER BY activity_equipment.id DESC" ;
    $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
    while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC))
    {
      $activity_id = $row2['activity_id'] ;
      
      // Update the last received record from that equipment
      // Not the first record!
      if ($iter > 1)
      {
        $query3 = "UPDATE activity_equipment SET inactive='y' WHERE id = " . $activity_id ;
        $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
        // Display
        //echo 'equipment_id = ' . $equipment_id . '; '  ;
      }
      // Iteration for the previous records
      $iter++ ;
    }
  }
}
else
{
  echo 'Nothing!' ;
}

echo 'OK; '  ;

// Close db
db_close($dbc_local) ;

?>