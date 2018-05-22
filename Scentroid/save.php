<?php 
#usage: 
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/mysql_connect.php'); // Connect to the db
require_once ($_SERVER['DOCUMENT_ROOT'] . '/includes/php/ftp_connect.php'); // Connect to the FTP

//$url = $row['url'];

// Start with Page = 1 of the March first 2018
// And then add a page until there is no content left
// if no conent left go to the next day and restart page counter to 1
$query1 = "SELECT next_page, sim_date FROM sims_status ORDER BY sstatus_id DESC LIMIT 1";
$result1 = mysql_query($query1) or trigger_error("Query: $query1\n<br>MySQL Error: " . mysql_error());
$row1 = mysql_fetch_array($result1, MYSQL_NUM);
$page = $row1[0];
//$date_begin_day = '2018-05-04 00:00:00' ;
//$date_end_day = '2018-03-01 23:59:59' ;
$date_day = $row1[1];

if ($date_day != '2018-05-04')
{

	// Get the timestamps for that date
	$query3 = "SELECT begin_timestamp, end_timestamp, stimestamp_id FROM sims_timestamp WHERE sim_date='$date_day'";
	$result3 = mysql_query($query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysql_error());
	$row3 = mysql_fetch_array($result3, MYSQL_NUM);
	$begin_tmstp = $row3[0];
	$end_tmstp = $row3[1];
	$next_stmstp_id = $row3[2] + 1;
	
	//$tmstp_begin_day = '2018-03-01 00:00:00' ;
	//$tmstp_end_day = '2018-03-01 23:59:59' ;
	
	$url = 'https://sims.scentroid.com/do/api/v1.data?sn=sl121602&secret=f767c21e4b6fd5474e3899324e4e9862&from=' . $begin_tmstp . '&to=' . $end_tmstp . '&p=' . $page;
	//$url = 'http://www.crypster.cc/';
	
	$contents = file_get_contents("$url"); 
	if ($contents) {
		
		
		if (strlen($contents) >  5) { // A match was made.
			if (json_decode($contents))
			{
				// Add the show.
				$query = "INSERT INTO sims (json, sim_date, page, added_by, added_datetime) VALUES ('$contents', '$date_day', '$page', '1', NOW())";
				$result = mysql_query ($query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysql_error());
				
				$next_page = $page + 1 ;
				$query2 = "INSERT INTO sims_status (next_page, sim_date) VALUES ('$next_page', '$date_day')";
				$result2 = mysql_query ($query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysql_error());
			}
			
		}
		else
		{
		  // Restart with page = 1 of the next day!
		  // Update sims_status
		  $next_page = 1 ;
		  
		  $query4 = "SELECT sim_date FROM sims_timestamp WHERE stimestamp_id='$next_stmstp_id'";
		  $result4 = mysql_query($query4) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysql_error());
		  $row4 = mysql_fetch_array($result4, MYSQL_NUM);
		  $next_date =  $row4[0];
		  
		  // Add new next status for next sample
		  $query2 = "INSERT INTO sims_status (next_page, sim_date) VALUES ('$next_page', '$next_date')";
		  $result2 = mysql_query ($query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysql_error());
		  
		}
	
	}

}

// ---------------------------------------SLEEP---------------------------------------------------------------------------------------
sleep(15) ;

$query1 = "SELECT next_page, sim_date FROM sims_status ORDER BY sstatus_id DESC LIMIT 1";
$result1 = mysql_query($query1) or trigger_error("Query: $query1\n<br>MySQL Error: " . mysql_error());
$row1 = mysql_fetch_array($result1, MYSQL_NUM);
$page = $row1[0];
//$date_begin_day = '2018-03-01 00:00:00' ;
//$date_end_day = '2018-03-01 23:59:59' ;
$date_day = $row1[1];

if ($date_day != '2018-05-04')
{

	
	// Get the timestamps for that date
	$query3 = "SELECT begin_timestamp, end_timestamp, stimestamp_id FROM sims_timestamp WHERE sim_date='$date_day'";
	$result3 = mysql_query($query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysql_error());
	$row3 = mysql_fetch_array($result3, MYSQL_NUM);
	$begin_tmstp = $row3[0];
	$end_tmstp = $row3[1];
	$next_stmstp_id = $row3[2] + 1;
	
	//$tmstp_begin_day = '2018-03-01 00:00:00' ;
	//$tmstp_end_day = '2018-03-01 23:59:59' ;
	
	$url = 'https://sims.scentroid.com/do/api/v1.data?sn=sl121602&secret=f767c21e4b6fd5474e3899324e4e9862&from=' . $begin_tmstp . '&to=' . $end_tmstp . '&p=' . $page;
	//$url = 'http://www.crypster.cc/';
	
	$contents = file_get_contents("$url"); 
	if ($contents) {
		
		
		if (strlen($contents) >  5) { // A match was made.
			if (json_decode($contents))
			{
				// Add the show.
				$query = "INSERT INTO sims (json, sim_date, page, added_by, added_datetime) VALUES ('$contents', '$date_day', '$page', '1', NOW())";
				$result = mysql_query ($query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysql_error());
				
				$next_page = $page + 1 ;
				$query2 = "INSERT INTO sims_status (next_page, sim_date) VALUES ('$next_page', '$date_day')";
				$result2 = mysql_query ($query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysql_error());
			}
			
		}
		else
		{
		  // Restart with page = 1 of the next day!
		  // Update sims_status
		  $next_page = 1 ;
		  
		  $query4 = "SELECT sim_date FROM sims_timestamp WHERE stimestamp_id='$next_stmstp_id'";
		  $result4 = mysql_query($query4) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysql_error());
		  $row4 = mysql_fetch_array($result4, MYSQL_NUM);
		  $next_date =  $row4[0];
		  
		  // Add new next status for next sample
		  $query2 = "INSERT INTO sims_status (next_page, sim_date) VALUES ('$next_page', '$next_date')";
		  $result2 = mysql_query ($query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysql_error());
		  
		}
	
	}

}

// ---------------------------------------SLEEP---------------------------------------------------------------------------------------
sleep(15) ;

$query1 = "SELECT next_page, sim_date FROM sims_status ORDER BY sstatus_id DESC LIMIT 1";
$result1 = mysql_query($query1) or trigger_error("Query: $query1\n<br>MySQL Error: " . mysql_error());
$row1 = mysql_fetch_array($result1, MYSQL_NUM);
$page = $row1[0];
//$date_begin_day = '2018-03-01 00:00:00' ;
//$date_end_day = '2018-03-01 23:59:59' ;
$date_day = $row1[1];

if ($date_day != '2018-05-04')
{
	
	
	// Get the timestamps for that date
	$query3 = "SELECT begin_timestamp, end_timestamp, stimestamp_id FROM sims_timestamp WHERE sim_date='$date_day'";
	$result3 = mysql_query($query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysql_error());
	$row3 = mysql_fetch_array($result3, MYSQL_NUM);
	$begin_tmstp = $row3[0];
	$end_tmstp = $row3[1];
	$next_stmstp_id = $row3[2] + 1;
	
	//$tmstp_begin_day = '2018-03-01 00:00:00' ;
	//$tmstp_end_day = '2018-03-01 23:59:59' ;
	
	$url = 'https://sims.scentroid.com/do/api/v1.data?sn=sl121602&secret=f767c21e4b6fd5474e3899324e4e9862&from=' . $begin_tmstp . '&to=' . $end_tmstp . '&p=' . $page;
	//$url = 'http://www.crypster.cc/';
	
	$contents = file_get_contents("$url"); 
	if ($contents) {
		
		
		if (strlen($contents) >  5) { // A match was made.
			if (json_decode($contents))
			{
				// Add the show.
				$query = "INSERT INTO sims (json, sim_date, page, added_by, added_datetime) VALUES ('$contents', '$date_day', '$page', '1', NOW())";
				$result = mysql_query ($query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysql_error());
				
				$next_page = $page + 1 ;
				$query2 = "INSERT INTO sims_status (next_page, sim_date) VALUES ('$next_page', '$date_day')";
				$result2 = mysql_query ($query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysql_error());
			}
			
		}
		else
		{
		  // Restart with page = 1 of the next day!
		  // Update sims_status
		  $next_page = 1 ;
		  
		  $query4 = "SELECT sim_date FROM sims_timestamp WHERE stimestamp_id='$next_stmstp_id'";
		  $result4 = mysql_query($query4) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysql_error());
		  $row4 = mysql_fetch_array($result4, MYSQL_NUM);
		  $next_date =  $row4[0];
		  
		  // Add new next status for next sample
		  $query2 = "INSERT INTO sims_status (next_page, sim_date) VALUES ('$next_page', '$next_date')";
		  $result2 = mysql_query ($query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysql_error());
		  
		}
	
	}
	
}

ftp_close($ftpc);
mysql_close(); // Close the database connection.

echo "OK";
?> 
