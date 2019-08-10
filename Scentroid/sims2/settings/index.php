<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
require_once(TEMPLATES_PATH . "/template_settings.php");

/*
    Now you can handle all your php logic outside of the template
    file which makes for very clean code!
*/

//-----------------------------------------------------------VERIFY LOGIN---------------------------------------------------------------------------------

// If no loggedin session, redirect the user.
if(!isset($_SESSION['loggedin'])) 
{

	// Start defining the URL.
	$url = 'http://' . $_SERVER['HTTP_HOST'] ;
	// Check for a trailing slash.
	if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
		$url = substr ($url, 0, -1) ;
		// Chop off the slash.
	}
	// Add the page.
	$url .='/login' ;
	
	ob_end_clean() ; // Delete the buffer.
	header("Location: $url") ;
	exit() ; // Quit the script.
	
}

//-----------------------------------------------------------GET / SESSIONS---------------------------------------------------------------------------------
// Equipment before putting it to GET session (Top Bar session variables)
//$equipment = 51 ; // By default use the Scentroid one 51

if (isset($_GET['company'])) 
{
  $company = $_GET['company'] ;
}
else
{
  //$company = 43 ; // For example
  //$company = 42 ; 
  //$company = 22 ;
  $company = getSessionCompanyIDFromUser($_SESSION['id']) ; // Useless because company_id is in user table!
}

$settings = true ;
$setup = false ;

/*----------------------------------------------------------Update input Field top bar------------------------------------------------------------------- */

$update_company_select_field = buildCompanyTopInputFields($company) ;

/*---------------------------------------------------------------------Users------------------------------------------------------------------- */

$user_list_table = buildUsersTable($company) ;

/*---------------------------------------------------------------------Equipments------------------------------------------------------------------- */

$equipment_list_table = buildEquipmentsTable($company) ;

/*---------------------------------------------------------------------Sensors------------------------------------------------------------------- */

$sensor_list_table = buildSensorsTable($company) ;

/*----------------------------------------------------------Company Information------------------------------------------------------------------- */

$company_info_div = buildCompanyInfoDiv($company) ;

/*----------------------------------------------------------Notifications------------------------------------------------------------------- */

$notification_table = buildNotificationsTable($company) ;

/*----------------------------------------------------------AQI Table for Sensors------------------------------------------------------------------- */

$aqi_table = buildSensorAQITable($company) ; // use Company or Equipment?

/*----------------------------------------------------------Company List------------------------------------------------------------------- */

$company_list_table = buildCompanyTable() ;

/*-----------------------------------Collect Variables and Send all------------------------------------ */

// Must pass in variables (as an array) to use in template
$variables = array(
    'settings' => $settings,
    'company_info_div' => $company_info_div,
    'user_list_table' => $user_list_table,
    'equipment_list_table' => $equipment_list_table,
    'sensor_list_table' => $sensor_list_table,
    'notification_table' => $notification_table,
    'company_list_table' => $company_list_table,
    'aqi_table' => $aqi_table,
    'update_company_select_field' => $update_company_select_field
);

renderLayoutWithContentFile("template_home_settings.php", $variables) ;

?>