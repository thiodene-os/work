<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
require_once(TEMPLATES_PATH . "/template_main.php");


// Connect to db
$dbc = db_connect_local() ;

$current = 1 ;
// Get the last sample measured by that Equipment
$query = "SELECT date_range.id AS range_id, dt_range
      FROM date_range
      WHERE id = " . $current ;
$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
$row = mysqli_fetch_array($result, MYSQLI_NUM) ;

$range = $row[1] ;

echo 'Range: ' . $range ;

//$test_date_range = strtotime("-1 week") ;

// Close db
db_close($dbc) ;

?>