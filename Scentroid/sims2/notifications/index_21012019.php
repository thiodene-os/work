<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
require_once(TEMPLATES_PATH . "/template_notifications.php");

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

$notifications = true ;

/*---------------------------------------------------------------------GET equipment ID------------------------------------------------------------------- */

if (isset($_GET['equipment'])) 
{
  if (strlen($_GET['equipment']) > 0)
    $equipment = $_GET['equipment'] ;
  else
    $equipment = false ;
}
else
{
  $equipment = false ;
}

// from Search field
if (isset($_GET['search'])) 
{
  if (strlen(trim($_GET['search'])) > 0)
    $search = $_GET['search'] ;
  else
    $search = false ;
}
else
{
  $search = false ;
}

/*--------------------------------------------------------------Build the Menu Tabs of Notifications------------------------------------------------------------------- */

$activity_tabs = '  <a class="tabs active" href="#activity">Activity (Equipments)</a>
' ;
$activity_tabs .= '  <a class="tabs" href="#health">Health (Sensors)</a>
' ;
$activity_tabs .= '  <a class="tabs" href="#alarm">Alarms (Sensors)</a>
' ;
$activity_tabs .= '  <a class="tabs" href="#log">Logs</a>
' ;

/*---------------------------------------------------------------------Build the top select input------------------------------------------------------------------- */

$equipment_select = buildNotificationsTopSelectFields($equipment,$search) ;

/*---------------------------------------------------------------------General Activity of Equipments------------------------------------------------------------------- */

$activity_table = buildShowNotificationsTable($equipment,$search) ;

/*---------------------------------------------------------------------Health------------------------------------------------------------------- */

$health_table = '' ; //buildShowHealthTable($equipment,$search) ;

/*---------------------------------------------------------------------Alarm------------------------------------------------------------------- */

$alarm_table = '' ; // buildAlarmsTable($equipment,$search) ;

/*---------------------------------------------------------------------Logs------------------------------------------------------------------- */

$log_table = '' ; // buildLogsTable($equipment,$search) ;

/*-----------------------------------Collect Variables and Send all------------------------------------ */

// Must pass in variables (as an array) to use in template
$variables = array(
    'activity_table' => $activity_table,
    'health_table' => $health_table,
    'log_table' => $log_table,
    'alarm_table' => $alarm_table,
    'activity_tabs' => $activity_tabs,
    'equipment_select' => $equipment_select
);

renderLayoutWithContentFile("template_home_notifications.php", $variables) ;

?>