<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
require_once(TEMPLATES_PATH . "/template_old_overview.php");

/*
    Now you can handle all your php logic outside of the template
    file which makes for very clean code!
*/

//-----------------------------------------------------------VERIFY LOGIN---------------------------------------------------------------------------------

// If no loggedin session, redirect the user.
if(!isset($_SESSION['loggedin'])) 
{

	// Start defining the URL.
	$url = 'http://' . $_SERVER['HTTP_HOST'];
	// Check for a trailing slash.
	if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
		$url = substr ($url, 0, -1);
		// Chop off the slash.
	}
	// Add the page.
	$url .='/login';
	
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
} 

//-----------------------------------------------------------GET / SESSIONS---------------------------------------------------------------------------------
// Company before putting it to GET session (Top Bar session variables)
//$company = 31 ; // By default use the SI Analytics 31 

if (isset($_GET['company'])) 
{
  // Get the selected company ID from GET
  $company = $_GET['company'] ;
}
else
{
  // Use one by default
  $company = 31 ;
}

/*----------------------------------------------------------Update input Field top bar------------------------------------------------------------------- */

$update_input_field = buildCompanyTopInputFields($company) ;

$left_side_data = '' ;

/*----------------------------------------------------------Right Side Chart Data------------------------------------------------------------------------ */
// Main Map
$right_side_data = buildMainMap() ;
$equipment = getMainEquipmentID($company) ;
$equipment_geoposition_js = buildGeoPosition($equipment) ;
list ($equipment_wind_triangle_js, $color_code) = buildWindPolygon($equipment) ;
list ($notice, $listener, $aqi) = addNotificationToMap($equipment) ;
list ($all_equipments_geoposition_js, $all_equipments_marker_js) = buildMultiGeoPositions($company) ;

/*-----------------------------------Collect Variables and Send all------------------------------------ */

// Must pass in variables (as an array) to use in template
$variables = array(
    'update_input_field' => $update_input_field,
    'left_side_data' => $left_side_data,
    'right_side_data' => $right_side_data,
    'equipment_geoposition_js' => $equipment_geoposition_js,
    'equipment_wind_triangle_js' => $equipment_wind_triangle_js,
    'all_equipments_geoposition_js' => $all_equipments_geoposition_js,
    'all_equipments_marker_js' => $all_equipments_marker_js,
    'color_code' => $color_code,
    'notice' => $notice,
    'listener' => $listener,
    'aqi' => $aqi
);

renderLayoutWithContentFile("template_home_old_overview.php", $variables) ;

?>