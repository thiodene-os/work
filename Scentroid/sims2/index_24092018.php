<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
require_once(TEMPLATES_PATH . "/template_main.php");

/*
    Now you can handle all your php logic outside of the template
    file which makes for very clean code!
*/

//-----------------------------------------------------------GET / SESSIONS---------------------------------------------------------------------------------
// Equipment before putting it to GET session (Top Bar session variables)
//$equipment = 51 ; // By default use the Scentroid one 51

if (isset($_GET['equipment'])) 
{
  $equipment = $_GET['equipment'] ;
  // The other parameters don't have to be set!
  if (isset($_GET['begin_date']))
    $begin_date = $_GET['begin_date'] ;
  else
    $begin_date = '' ;
  
  if (isset($_GET['end_date']))
    $end_date = $_GET['end_date'] ;
  else
    $end_date= '' ;
  
  if (isset($_GET['date_range']))
    $date_range = $_GET['date_range'] ;
  else
    $date_range = false ;
  //// If the interval date is empty, the begin date and the end date too, then calculate a new interval from when sample data ends - 1 week (for now)!
  //$new_interval_date = calcDateRangeFromLastSample($equipement) ;
}
else
{
  $equipment = 51 ;
  $begin_date = '' ;
  $end_date = '' ;
  $date_range = '' ; // Could also calculate from where the data has been recorded for the last time
}

// Start at 1 for physical number of Charts
$chart_number = 1 ;
$chart_container_js = '' ;
$series_to_plot_js = '' ;

/*----------------------------------------------------------Update input Field top bar------------------------------------------------------------------- */

$update_input_field = buildTopInputFields($equipment, $date_range, $begin_date, $end_date) ;

/*----------------------------------------------------------Left Side Chart Data------------------------------------------------------------------------- */
// Sensor Data & Charts
$left_side_data = buildSensorTable($equipment, $chart_number) ;
$left_side_data .= displaySensorChart($chart_number) ;
if ($date_range)
  list ($chart_container_js, $series_to_plot_js) = getDbSensorDataCanvasJS($equipment, $date_range) ;
elseif ($begin_date)
  list ($chart_container_js, $series_to_plot_js) = buildSensorDataCanvasJS($equipment, $begin_date, $end_date, $chart_number) ;
else
  list ($chart_container_js, $series_to_plot_js) = getDbSensorDataCanvasJS($equipment, 'current') ;

// Increment Chart Number for next chart (if any!)
$chart_number++ ;

/*----------------------------------------------------------Right Side Chart Data------------------------------------------------------------------------ */
// Main Map
$right_side_data = buildMainMap() ;
$equipment_geoposition_js = buildGeoPosition($equipment) ;
list ($equipment_wind_triangle_js, $color_code) = buildWindPolygon($equipment) ;
list ($notice, $listener, $aqi) = addNotificationToMap($equipment) ;

// Met Data & Charts
$right_side_data .= buildSensorTable($equipment, $chart_number, true) ;
$right_side_data .= displaySensorChart($chart_number, true) ;
if ($date_range)
  list ($chart_container_js2, $series_to_plot_js2) = getDbSensorDataCanvasJS($equipment, $date_range, true) ;
elseif ($begin_date)
  list ($chart_container_js2, $series_to_plot_js2) = buildSensorDataCanvasJS($equipment, $begin_date, $end_date, $chart_number, true) ;
else
  list ($chart_container_js2, $series_to_plot_js2) = getDbSensorDataCanvasJS($equipment, 'current', true) ;

$chart_container_js .= $chart_container_js2 ;
$series_to_plot_js .= $series_to_plot_js2 ;

// Increment Chart Number for next chart (if any!)
$chart_number++ ;

/*-----------------------------------Collect Variables and Send all------------------------------------ */

// Must pass in variables (as an array) to use in template
$variables = array(
    'update_input_field' => $update_input_field,
    'left_side_data' => $left_side_data,
    'right_side_data' => $right_side_data,
    'chart_container_js' => $chart_container_js,
    'series_to_plot_js' => $series_to_plot_js,
    'equipment_geoposition_js' => $equipment_geoposition_js,
    'equipment_wind_triangle_js' => $equipment_wind_triangle_js,
    'color_code' => $color_code,
    'notice' => $notice,
    'listener' => $listener,
    'aqi' => $aqi
);

renderLayoutWithContentFile("template_home.php", $variables) ;

?>