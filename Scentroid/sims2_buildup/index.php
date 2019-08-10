<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
require_once(TEMPLATES_PATH . "/template_main.php");

/*
    Now you can handle all your php logic outside of the template
    file which makes for very clean code!
*/

//-----------------------------------------------------------GET / SESSIONS---------------------------------------------------------------------------------
// Equipment before putting it to GET session (Top Bar session variables)
//$equipment = 82 ; // By default use the Scentroid one 26

if (isset($_GET['equipment'])) 
{
  $equipment = $_GET['equipment'] ;
  $begin_date = $_GET['begin_date'] ;
  $end_date = $_GET['end_date'] ;
}
else
{
  $equipment = 82 ;
  $begin_date = '' ;
  $end_date = '' ;
}


// Start at 1 for physical number of Charts
$chart_number = 1 ;
$chart_container_js = '' ;
$series_to_plot_js = '' ;

/*----------------------------------------------------------Update input Field top bar------------------------------------------------------------------- */

$update_input_field = buildTopInputFields($equipment, $begin_date, $end_date) ;

/*----------------------------------------------------------Left Side Chart Data------------------------------------------------------------------------- */
// Sensor Data & Charts
$left_side_data = buildSensorTable($equipment, $chart_number) ;
$left_side_data .= displaySensorChart($chart_number) ;
list ($chart_container_js, $series_to_plot_js) = buildSensorDataCanvasJS($equipment, $begin_date, $end_date, $chart_number) ;
// Increment Chart Number for next chart (if any!)
$chart_number++ ;

/*----------------------------------------------------------Right Side Chart Data------------------------------------------------------------------------ */
// Main Map
$right_side_data = buildMainMap() ;
$equipment_geoposition_js = buildGeoPosition($equipment);

// Met Data & Charts
$right_side_data .= buildMetTable($equipment, $chart_number) ;
$right_side_data .= displayMetChart($chart_number) ;
list ($chart_container_js2, $series_to_plot_js2) = buildSensorDataCanvasJS($equipment, $begin_date, $end_date, $chart_number, true) ;

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
    'equipment_geoposition_js' => $equipment_geoposition_js
);

renderLayoutWithContentFile("template_home.php", $variables) ;

?>