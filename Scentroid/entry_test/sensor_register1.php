<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/mysql_connect.php'); // Connect to the db
require_once ($_SERVER['DOCUMENT_ROOT'] . '/includes/php/ftp_connect.php'); // Connect to the FTP

$query = "SELECT json FROM sims WHERE sim_id='1'";
$result = mysql_query($query) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysql_error());
$row = mysql_fetch_array($result, MYSQL_NUM);
$sim_json =  $row[0];

$array = json_decode($sim_json) ;

foreach ($array as $key => $jsons) { // This will search in the 2 jsons
     foreach($jsons as $key => $value) {
         if ($key == 'sensor')
		   echo $value . ' ;';
    }
}

echo 'OK!' ;

//echo $sensor_list ;

?>
