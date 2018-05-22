<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/mysql_connect.php'); // Connect to the db
require_once ($_SERVER['DOCUMENT_ROOT'] . '/includes/php/ftp_connect.php'); // Connect to the FTP

$iter = 0;
$sensor = 'Internal Temperature' ;
$new_json = '[' ;
$query = "SELECT json FROM sims WHERE sim_id<='2070'";
$result = mysql_query($query) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysql_error());
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	//$row = mysql_fetch_array($result, MYSQL_NUM);
	$iter++;
	$sim_json =  $row['json'];
	
	$array = json_decode($sim_json) ;
	$right_sensor = false ;
	foreach ($array as $key => $jsons) { // This will search in the 2 jsons
		 foreach($jsons as $key => $value) {
			 if ($key == 'sensor' && $value == $sensor)
			 {
				if (strlen($new_json) > 1)
				  $new_json .= ',' ;
				$right_sensor = true;
				$iter++;
			}
				
			 if ($key == 'time' && $right_sensor){
			 	$new_json .= '{"time":"' . $value . '"';
				$iter++;
			 }
			 if ($key == 'value' && $right_sensor){
			 	$new_json .= ',' ;
			 	$new_json .= '"value":"' . $value . '"';
				$new_json .= '}' ;
				$right_sensor = false ;
				$iter++;
			 }
		}
	}
}
$new_json .= ']' ;

$query1 = "SELECT sensor_id FROM sensors WHERE name='$sensor'";
$result1 = mysql_query($query1) or trigger_error("Query: $query1\n<br>MySQL Error: " . mysql_error());
$row1 = mysql_fetch_array($result1, MYSQL_NUM);
$seid = $row1[0];

$query2 = "UPDATE sensors SET json='$new_json' WHERE sensor_id='$seid'";
$result2 = mysql_query ($query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysql_error());

//echo strlen($new_json) . '; ' . $new_json ;
echo $new_json ;

//echo $sensor_list ;

?>
