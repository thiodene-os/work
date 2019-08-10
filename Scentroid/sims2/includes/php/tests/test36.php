<?php
# Test get the last connected IP information from SIMS1 Logs
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');

// Connect to db
$dbc = db_connect_sims() ;
$dbc_local = db_connect_local() ;

//$equipment_name = 'sl121601' ;
//$equipment_id = '34' ;

$equipment_name = 'SL041804' ;
$equipment_id = '89' ;

$query = "SELECT log.id AS log_id, message
      FROM log
      WHERE (MATCH(message) AGAINST ('+device +connected +array +$equipment_name ' IN BOOLEAN MODE))
      ORDER BY id DESC LIMIT 1" ;
$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
if (@mysqli_num_rows($result) != 0)
{
  $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
  $log_message = $row[1] ;
  //echo $log_message ;
  
  // Transform the log message and get only LON and LAT
  $log_arr = explode('[lat]', $log_message) ;
  $log_str = $log_arr[1] ;
  $log_arr = explode('[alt]', $log_str) ;
  $log_str = $log_arr[0] ;
  
  $log_str = str_replace(' ', '', $log_str) ; 
  $log_str = str_replace('=>', '', $log_str) ; 
  $gps_arr = explode('[lon]', $log_str) ;
  $lat = $gps_arr[0] ;
  $lon = $gps_arr[1] ;
  
  /*
  // Now update the equipment locally
  $query2 = "UPDATE lastvalue_equipment 
             SET lat_connected='$lat', lon_connected='$lon', updated_dt=NOW()"
            . " WHERE id = " . $update_value_id ;
  $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
  */
  
  echo 'LAT: ' . $lat ;
  echo 'LON: ' . $lon ;
}

// Close db
db_close($dbc) ;
db_close($dbc_local) ;

?>
