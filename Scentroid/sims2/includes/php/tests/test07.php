<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
//require_once(TEMPLATES_PATH . "/template_main.php");

// Connect to db
$dbc = db_connect_sims() ;

$equipment = 88 ;

// Calculate the timestamps for last data date and 1 month prior
// First get the last recorded data date for that equipment
$query = "SELECT sample.id AS sample_id, sampledat
          FROM sample
          WHERE equipement = " . $equipment
          . " ORDER BY sampledat DESC LIMIT 1" ;
$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc)) ;
if (mysqli_num_rows($result) != 0)
{
  $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
  $last_data_date = $row[1] ;
}
else
{
  // If no recorded date use today
  //$last_data_date = strtotime("now") ;
  $last_data_date = 'Rien!' ;
}

echo $last_data_date ;
echo floatval("00.000");

// Close db
db_close($dbc) ;

?>