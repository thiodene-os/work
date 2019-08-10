<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');

$dbc = db_connect_sims() ;
$my_ajax_html = '' ;
if (isset($_GET['show_sensors'])) 
{
  
  // Get the selected company ID from GET
  $equipment_id = $_GET['equipment_id'] ;
  
  // Verify that the email/password belong in SIMS
  // Query the database.
  $query = "SELECT sensor.id AS sensor_id, name, packet_id, type, dataunit 
            FROM sensor 
            WHERE equipement='$equipment_id'";
  
  $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
  if (@mysqli_num_rows($result) > 0) 
  {

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
    {
      $my_ajax_html .= '<tr><td>' . $row['name'] . '</td><td>' . $row['packet_id'] . '</td><td>' . $row['type']
                       . '</td><td>' . $row['dataunit'] 
                       . '</td><td><a style="text-decoration: none;" href="/sensor/edit_sensor.php?equipment_id=' . $equipment_id 
                       . '&id=' . $row['sensor_id'] . '"><span class="button button_edit edit_sensor">Edit</span></a>'
                       . '<a style="text-decoration: none;" href="/sensor/delete_sensor.php?equipment_id=' . $equipment_id 
                       . '&id=' . $row['sensor_id'] . '"><span class="button button_delete delete_sensor">Delete</span></a></td></tr>' ;
    }
    
    
    $my_ajax_html .= '<tr><td colspan="4"></td><td><a style="text-decoration: none;" href="/sensor/add_sensor.php?equipment_id=' . $equipment_id
                      . '"><span class="button button_add add_sensor">Add Sensor</span></a></td></tr>' ;
    
  }
  else
  {
    $my_ajax_html = '<tr><td colspan="4"><span style="color:red;">This equipment doesn\'t have sensors yet!</span></td><td></td></tr>' ;
    $my_ajax_html .= '<tr><td colspan="4"></td><td><a style="text-decoration: none;" href="/sensor/add_sensor.php?equipment_id=' . $equipment_id
                      . '"><span class="button button_add add_sensor">Add Sensor</span></a></td></tr>' ;    
  }
}
else
{
  // If no equipment ID HTML has to be empty
  $my_ajax_html = '<span>Login attempt failed!</span>' ;
}

db_close($dbc) ;

echo $my_ajax_html ;

?>