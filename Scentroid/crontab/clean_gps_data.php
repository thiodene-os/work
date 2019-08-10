<?php       
# CRONTAB
# This script is used to clean up GPS record with typical timestamp data from table SAMPLE 
# Data with very GPS timestamp like < 4 digit long needs to be removed
# However if no real sensor data has been entered yet we leave the unwanted GPS calibration data in SAMPLE 


//require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/mysql_connect.php') ; // Connect to the database (Change it asap!)

// Define the Database parameters 
define ('DB_USER', 'root') ;
define ('DB_HOST', 'localhost') ;   
define ('DB_PASSWORD', 'salam6060');
define ('DB_NAME', 'sims');

// Try the connection to MySQL  + Select the relevant database
if ($dbc = @mysql_connect(DB_HOST, DB_USER , DB_PASSWORD))
{    
  if(!mysql_select_db(DB_NAME))
  {
    trigger_error("Could not select the database!<br>MYSQL Error:" . mysql_error()) ; 
    exit();   
  }
}
else
{
  trigger_error("Could not connect to MySQL!<br>MYSQL Error:" . mysql_error());    
  exit();
}

$num_deleted = 0;
 
// First select all the sensor with GPS sampledat value from SAMPLE table
// Usually GPS timestamp is 4 digits long or less 
$query = "SELECT id, sensor, sampledat from sample WHERE sampledat<='9999'"; 
$result = mysql_query($query) or trigger_error("Query: $query\n<br>MYSQL Error: " . mysql_error());

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { 
   
  $sample_id = $row['id'] ;
  $sensor_id = $row['sensor'] ;
  $timestamp = $row['sampledat'] ;
    
  // Now check if the sensor has more than one data recorded in SAMPLE    
  $query2 = "SELECT sampledat from sample WHERE sensor =" . $sensor_id; 
  $result2 = mysql_query($query2) or trigger_error("Query: $query2\n<br>MYSQL Error: " . mysql_error());  
  $row2 = mysql_fetch_array($result2, MYSQL_NUM); 
  if (mysql_num_rows($result2) > 1)
  {
    // If yes delete the GPS timestamp record 
    $query3 = "DELETE FROM sample WHERE id=" . $sample_id ; 
    $result3 = mysql_query($query3) or trigger_error("Query: $query3\n<br>MYSQL Error: " . mysql_error()) ;
    //echo "ID: " . mysqli_affected_rows() . "; "; 
    echo "Timestamp: " . $timestamp . "; "; 
    $num_deleted++;
  }


}



echo "Deleted: " . $num_deleted . "; ";  
echo "Script: OK <br>";    

mysql_close($dbc) ;
?>
