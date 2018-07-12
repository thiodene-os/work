<?php
# CRONTAB | PHP 7.0
# This script is used to clean up bad record with typical timestamp data from table SAMPLE
//require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/mysql_connect.php') ; // Connect to the database (Change it asap!)
// Define the Database parameters
define ('DB_USER', 'root') ;
define ('DB_HOST', '167.114.10.49') ;
define ('DB_PASSWORD', 'GAhftYwxCvw93L5e');
define ('DB_NAME', 'sims');
// Try the connection to MySQL  + Select the relevant database
$dbc = @mysqli_connect(DB_HOST, DB_USER , DB_PASSWORD) ;
if ($dbc)
{
  if(!mysqli_select_db($dbc, DB_NAME))
  {
    trigger_error("Could not select the database!<br>MYSQL Error:" . mysqli_error($dbc)) ;
    exit();
  }
}
else
{
  trigger_error("Could not connect to MySQL!<br>MYSQL Error:" . mysqli_error($dbc));
  exit();
}
$num_deleted = 0;

// First select all the sensor with GPS sampledat value from SAMPLE table
// Usually GPS timestamp is 4 digits long or less
$query = "SELECT id, sensor, sampledat from sample WHERE sampledat>='2000000000' LIMIT 5000";
$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MYSQL Error: " . mysqli_error($dbc));
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

  $sample_id = $row['id'] ;
  $sensor_id = $row['sensor'] ;
  $timestamp = $row['sampledat'] ;

  // Now check if the sensor has more than one data recorded in SAMPLE
  $query2 = "SELECT sampledat from sample WHERE sensor =" . $sensor_id;
  $result2 = mysqli_query($dbc, $query2) or trigger_error("Query: $query2\n<br>MYSQL Error: " . mysqli_error($dbc));
  $row2 = mysqli_fetch_array($result2, MYSQLI_NUM);
  if (mysqli_num_rows($result2) > 1)
  {
    // If yes delete the GPS timestamp record
    $query3 = "DELETE FROM sample WHERE id=" . $sample_id ;
    $result3 = mysqli_query($dbc, $query3) or trigger_error("Query: $query3\n<br>MYSQL Error: " . mysqli_error($dbc)) ;
    //echo "ID: " . mysqli_affected_rows() . "; ";
    //echo "Timestamp: " . $timestamp . "; ";
    $num_deleted++;
  }
}
echo "Deleted: " . $num_deleted . "; ";
echo "Script: OK \n";
mysqli_close($dbc) ;
?>
