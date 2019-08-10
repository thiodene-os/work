<?php

require_once('/var/www/html/includes/php/crontab/common_cron.php') ;

// Connect to db
$dbc = db_connect_sims() ;
$dbc_local = db_connect_local() ;

// Last 24 Hours / get the ID
$date_range = '24 hours' ;
$query = "SELECT date_range.id AS daterange_id FROM date_range WHERE daterange = '" . $date_range . "'" ;
$result = mysqli_query($dbc_local, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
$row = mysqli_fetch_array($result, MYSQLI_NUM) ;

$daterange_id = $row[0] ;

// Calculate the timestamps for today and 24 Hours ago
$today = strtotime("now") ;
$yesterdate = strtotime('-1 day', $today) ;
// Datetime format for date
$begin_date =  date("Y-m-d H:i:s", $yesterdate) ;
$end_date = date("Y-m-d H:i:s", $today) ;

// Select the equipment with the oldest update
$query2 = "SELECT canvasjs_equipment.id AS canvasjs_id, equipment_id 
           FROM canvasjs_equipment
           WHERE daterange_id = " . $daterange_id
      . " ORDER BY updated_dt ASC LIMIT 1" ;
$result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
$row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;

$update_vanvasjs_id = $row2[0] ;
$equipment = $row2[1] ;

// Calculate its CanvasJS values.
// GAS Sensors----------------------------------------------------------------------------------------------------------------
$chart_number = 1 ;
$met_ext = '' ;
list ($chart_container_js, $series_to_plot_js) = buildSensorDataCanvasJS($equipment, $begin_date, $end_date, $chart_number) ;
$file_chart = "series_plot". $met_ext . "_id" . $equipment . "_dr" . $daterange_id . ".txt" ;
$file_series = "chart_container". $met_ext . "_id" . $equipment . "_dr" . $daterange_id . ".txt" ;

// Chart
$filepath = "/var/www/html/includes/php/crontab/files/" . $file_chart;
if ($chart_container_js)
{
  // If previous file then remove it and write new
  if (file_exists($filepath)) 
  {
    unlink($filepath);
  }
  
  $filepath1 = "/var/www/html/includes/php/crontab/files/" . $file_chart;
  
  // Open and write content to file
  $fp = fopen($filepath1, "w") or die ("Couldn't open $filepath1");
  fwrite($fp, $chart_container_js);
  fclose($fp);
}
// Series
$filepath = "/var/www/html/includes/php/crontab/files/" . $file_series;
if ($series_to_plot_js)
{
  // If previous file then remove it and write new
  if (file_exists($filepath)) 
  {
    unlink($filepath);
  }
  
  $filepath1 = "/var/www/html/includes/php/crontab/files/" . $file_series;
  
  // Open and write content to file
  $fp = fopen($filepath1, "w") or die ("Couldn't open $filepath1");
  fwrite($fp, $series_to_plot_js);
  fclose($fp);
}


// MET Sensors----------------------------------------------------------------------------------------------------------------
$chart_number = 2 ;
$met_ext = '_met' ;
list ($chart_container_js2, $series_to_plot_js2) = buildSensorDataCanvasJS($equipment, $begin_date, $end_date, $chart_number, true) ;
$file_chart_met = "series_plot". $met_ext . "_id" . $equipment . "_dr" . $daterange_id . ".txt" ;
$file_series_met = "chart_container". $met_ext . "_id" . $equipment . "_dr" . $daterange_id . ".txt" ;

// Chart
$filepath = "/var/www/html/includes/php/crontab/files/" . $file_chart_met;
if ($chart_container_js2)
{
  // If previous file then remove it and write new
  if (file_exists($filepath)) 
  {
    unlink($filepath);
  }
  
  $filepath1 = "/var/www/html/includes/php/crontab/files/" . $file_chart_met;
  
  // Open and write content to file
  $fp = fopen($filepath1, "w") or die ("Couldn't open $filepath1");
  fwrite($fp, $chart_container_js2);
  fclose($fp);
}
// Series
$filepath = "/var/www/html/includes/php/crontab/files/" . $file_series_met;
if ($series_to_plot_js2)
{
  // If previous file then remove it and write new
  if (file_exists($filepath)) 
  {
    unlink($filepath);
  }
  
  $filepath1 = "/var/www/html/includes/php/crontab/files/" . $file_series_met;
  
  // Open and write content to file
  $fp = fopen($filepath1, "w") or die ("Couldn't open $filepath1");
  fwrite($fp, $series_to_plot_js2);
  fclose($fp);
}

// ALL Sensors (with 2 initially displayed)----------------------------------------------------------------------------------------
$met_ext = '_all' ;
list ($chart_container_js3, $series_to_plot_js3) = buildAllSensorsDataCanvasJS($equipment, $begin_date, $end_date, $chart_number, true) ;
$file_chart_met = "series_plot". $met_ext . "_id" . $equipment . "_dr" . $daterange_id . ".txt" ;
$file_series_met = "chart_container". $met_ext . "_id" . $equipment . "_dr" . $daterange_id . ".txt" ;

// Chart
$filepath = "/var/www/html/includes/php/crontab/files/" . $file_chart_met;
if ($chart_container_js3)
{
  // If previous file then remove it and write new
  if (file_exists($filepath)) 
  {
    unlink($filepath);
  }
  
  $filepath1 = "/var/www/html/includes/php/crontab/files/" . $file_chart_met;
  
  // Open and write content to file
  $fp = fopen($filepath1, "w") or die ("Couldn't open $filepath1");
  fwrite($fp, $chart_container_js3);
  fclose($fp);
}
// Series
$filepath = "/var/www/html/includes/php/crontab/files/" . $file_series_met;
if ($series_to_plot_js3)
{
  // If previous file then remove it and write new
  if (file_exists($filepath)) 
  {
    unlink($filepath);
  }
  
  $filepath1 = "/var/www/html/includes/php/crontab/files/" . $file_series_met;
  
  // Open and write content to file
  $fp = fopen($filepath1, "w") or die ("Couldn't open $filepath1");
  fwrite($fp, $series_to_plot_js3);
  fclose($fp);
}

// Update the date priority on the DB
$query3 = "UPDATE canvasjs_equipment 
           SET updated_dt=NOW()"
          . " WHERE id = " . $update_vanvasjs_id ;
$result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;

// Close db
db_close($dbc) ;
db_close($dbc_local) ;

echo "24 Hours Disk:OK; \n" ;

?>