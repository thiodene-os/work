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

// If Notifications are clicked, remove the notification number from the Menu Bar
$_SESSION['notifications'] = 0 ;
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

$activity_tabs = '  <a class="tabs" href="#activity">Activity (Equipments)</a>
' ;
$activity_tabs .= '  <a class="tabs" href="#health">Health (Sensors)</a>
' ;
$activity_tabs .= '  <a class="tabs" href="#alarm">Alarms (Sensors)</a>
' ;
$activity_tabs .= '  <a class="tabs active" href="#log">Logs</a>
' ;

/*---------------------------------------------------------------------Build the top select input------------------------------------------------------------------- */
// Session Company -------------------------
if ($_SESSION['id'] == 10)
  $session_company = false ;
else
  $session_company = $_SESSION['company'] ;

$equipment_select = buildNotificationsTopSelectFields($equipment,$search,$session_company) ;

/*---------------------------------------------------------------------General Activity of Equipments------------------------------------------------------------------- */

$activity_table = '' ; //buildShowNotificationsTable($equipment,$search,$session_company) ;

/*---------------------------------------------------------------------Health------------------------------------------------------------------- */

$health_table = '' ; //buildShowHealthTable($equipment,$search,$session_company) ;

/*---------------------------------------------------------------------Alarm------------------------------------------------------------------- */

$alarm_table = '' ; // buildShowAlarmsTable($equipment,$search,$session_company) ;

/*---------------------------------------------------------------------Logs------------------------------------------------------------------- */

$log_table = buildShowLogsTable($equipment,$search,$session_company) ;

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