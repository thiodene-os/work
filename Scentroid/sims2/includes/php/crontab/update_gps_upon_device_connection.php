<?php
# Test get the last connected IP information from SIMS1 Logs
require_once('/var/www/html/includes/php/crontab/common_cron.php') ;

// Connect to db
$dbc = db_connect_sims() ;
$dbc_local = db_connect_local() ;

// Select the equipment ordered by the id (no preference)
$query2 = "SELECT lastvalue_equipment.id AS lastvalue_id, equipment_id, company_id 
           FROM lastvalue_equipment"
       . " ORDER BY id ASC" ;
$result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local));
if (mysqli_num_rows($result2) != 0)
{
  while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC))
  {
    // Get the equipment ID and the equipment name
    $lastvalue_id = $row2['lastvalue_id'] ;
    $equipment_id = $row2['equipment_id'] ;
    
    $query4 = "SELECT equipement.name AS equipment_name, sn
          FROM equipement
          WHERE equipement.id = " . $equipment_id ;
    $result4 = mysqli_query($dbc, $query4) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysqli_error($dbc));
    $row4 = mysqli_fetch_array($result4, MYSQLI_NUM) ;
    $equipment_name = $row4[0] ;
    $sn = $row4[1] ;
    
    //$equipment_name = 'SL041804' ;
    //$equipment_id = '89' ;
    
    // Go through the logs and get the device connection details for that equipment
    // -> If the device is connected then the GPS value is accurate! (If GPS value given)
    $query = "SELECT log.id AS log_id, message
          FROM log
          WHERE (MATCH(message) AGAINST ('+device +connected +array +$sn ' IN BOOLEAN MODE))
          ORDER BY id DESC LIMIT 1" ;
    $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
    if (@mysqli_num_rows($result) != 0)
    {
      $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
      $log_message = $row[1] ;
      //echo $log_message ;
      
      // Transform the log message and get only LON and LAT
      // Look for a serial number in the log string
      if (strpos($log_message,'[lat]'))
      {
        $log_arr = explode('[lat]', $log_message) ;
        $log_str = $log_arr[1] ;
        $log_arr = explode('[alt]', $log_str) ;
        $log_str = $log_arr[0] ;
        
        $log_str = str_replace(' ', '', $log_str) ;
        $log_str = str_replace('=>', '', $log_str) ;
        $gps_arr = explode('[lon]', $log_str) ;
        $lat = $gps_arr[0] ;
        $lon = $gps_arr[1] ;
        
        // LAT and LON are obtained, clean them up!
        $lon = trim($lon) ;
        $lat = trim($lat) ;
        // Be sure to get the LAT and LON to update the records
        if (strlen($lat) > 0 && strlen($lon) > 0)
        {
          // Now update the equipment's GPS position locally
          $query3 = "UPDATE lastvalue_equipment
                     SET lat_connected='$lat', lon_connected='$lon'"
                    . " WHERE id = " . $lastvalue_id ;
          $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
        }
        
        //echo $sn . ' ,' ;
        //echo '{' . $log_message . '}' ;
        //echo 'LAT: ' . $lat ;
        //echo 'LON: ' . $lon . '; ' . "\r\n" ;
      }
    }
  }
}
// Close db
db_close($dbc) ;
db_close($dbc_local) ;

echo "Update GPS with connected Device:OK; \n" ;

?>
