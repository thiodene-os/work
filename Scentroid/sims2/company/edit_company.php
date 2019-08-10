<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
require_once(TEMPLATES_PATH . "/template_navigation.php");

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

$company = 41 ; // For example
//$company = 22 ;

/*---------------------------------------------------------------------Page Title------------------------------------------------------------------- */

if (isset($_GET['id'])) 
{
  $company_id = $_GET['id'] ;

}

$page_title = 'Edit a Company' ;

/*---------------------------------------------------------------------Page Content------------------------------------------------------------------- */

$page_content = buildCompanyEdit($company_id) ; // $equipment_id to edit, no $equipment_id to add new!

/*-----------------------------------Collect Variables and Send all------------------------------------ */

// Must pass in variables (as an array) to use in template
$variables = array(
    'page_title' => $page_title,
    'page_content' => $page_content,
    'company_id' => $company_id
);

renderLayoutWithContentFile("template_home_navigation.php", $variables) ;

?>