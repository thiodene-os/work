<?php

require_once('/var/www/html/includes/php/crontab/common_cron.php') ;

// Connect to db
$dbc = db_connect_sims() ;
$dbc_local = db_connect_local() ;

// Last Data obtained / get the ID
$date_range = 'Current' ;
$query = "SELECT date_range.id AS daterange_id FROM date_range WHERE daterange = '" . $date_range . "'" ;
$result = mysqli_query($dbc_local, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
$row = mysqli_fetch_array($result, MYSQLI_NUM) ;

$daterange_id = $row[0] ;

// Select the equipment with the oldest update
$query2 = "SELECT canvas_js_equipment.id AS canvasjs_id, equipment_id 
           FROM canvas_js_equipment
           WHERE daterange_id = " . $daterange_id
      . " ORDER BY updated_dt ASC LIMIT 1" ;
$result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
$row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;

$update_vanvasjs_id = $row2[0] ;
$equipment = $row2[1] ;


// Calculate the timestamps for last data date and 1 month prior
// First get the last recorded data date for that equipment
$query4 = "SELECT sample.id AS sample_id, sampledat
          FROM sample
          WHERE equipement = " . $equipment
          . " ORDER BY sampledat DESC LIMIT 1" ;
$result4 = mysqli_query($dbc, $query4) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysqli_error($dbc)) ;
if (mysqli_num_rows($result4) == 0)
{
  $row4 = mysqli_fetch_array($result4, MYSQLI_NUM) ;
  $last_data_date = $row4[1] ;
}
else
{
  // If no recorded date use today
  $last_data_date = strtotime("now") ;
}

$yesterdate = strtotime('-2 weeks', $last_data_date) ;

// Datetime format for date
$begin_date =  date("Y-m-d H:i:s", $yesterdate) ;
$end_date = date("Y-m-d H:i:s", $last_data_date) ;


// Calculate its CanvasJS values.
$chart_number = 1 ;
list ($chart_container_js, $series_to_plot_js) = buildSensorDataCanvasJS($equipment, $begin_date, $end_date, $chart_number) ;
$chart_number = 2 ;
list ($chart_container_js2, $series_to_plot_js2) = buildSensorDataCanvasJS($equipment, $begin_date, $end_date, $chart_number, true) ;

// Update the CanvasJS fields for this equipment
$query3 = "UPDATE canvas_js_equipment 
           SET chart_container= '$chart_container_js', series_plot= '$series_to_plot_js'
           ,chart_container_met= '$chart_container_js2', series_plot_met= '$series_to_plot_js2', updated_dt=NOW()"
          . " WHERE id = " . $update_vanvasjs_id ;
$result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
//$row3 = mysqli_fetch_array($result3, MYSQLI_NUM) ;

// Close db
db_close($dbc) ;
db_close($dbc_local) ;

echo "Current:OK; \n" ;

?>