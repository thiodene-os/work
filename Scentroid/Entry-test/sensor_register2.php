<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/mysql_connect.php'); // Connect to the db
require_once ($_SERVER['DOCUMENT_ROOT'] . '/includes/php/ftp_connect.php'); // Connect to the FTP

$iter = 0;
$query = "SELECT json FROM sims WHERE sim_id<='1'";
$result = mysql_query($query) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysql_error());
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	//$row = mysql_fetch_array($result, MYSQL_NUM);
	$iter++;
	$sim_json =  $row['json'];
	
	$array = json_decode($sim_json) ;
	
	foreach ($array as $key => $jsons) { // This will search in the 2 jsons
		 foreach($jsons as $key => $value) {
			 if ($key == 'sensor')
			 {
				// echo $value . ' ;';
				$query1 = "SELECT sensor_id FROM sensors WHERE name='$value'";
				$result1 = mysql_query($query1) or trigger_error("Query: $query1\n<br>MySQL Error: " . mysql_error());
				if (mysql_num_rows($result1) == 0) {
					$query2 = "INSERT INTO sensors (name) VALUES ('$value')";
					$result2 = mysql_query ($query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysql_error());
				}
			 }
		}
	}
}

echo 'OK! (' . $iter . ')' ;

//echo $sensor_list ;

?>
