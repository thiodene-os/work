<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/mysql_connect.php'); // Connect to the db
require_once ($_SERVER['DOCUMENT_ROOT'] . '/includes/php/ftp_connect.php'); // Connect to the FTP

$query = "SELECT json FROM sims WHERE sim_id='1'";
$result = mysql_query($query) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysql_error());
$row = mysql_fetch_array($result, MYSQL_NUM);
$sim_json =  $row[0];

$sensor_list = json_decode($sim_json) ;

foreach ($sensor_list as $sensor_value){
	
	$sensor_name = $sensor_value[0]['sensor'] ;
	echo $sensor_name '; ' ;
	
}

echo 'OK!' ;

//echo $sensor_list ;

?>
