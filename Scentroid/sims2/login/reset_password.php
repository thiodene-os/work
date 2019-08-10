<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
require_once(TEMPLATES_PATH . "/template_reset.php");

/*
    Now you can handle all your php logic outside of the template
    file which makes for very clean code!
*/

// If the user already loggedin redirect to home/overview!
if(isset($_SESSION['loggedin'])) 
{
	// Start defining the URL.
	$url = 'http://' . $_SERVER['HTTP_HOST'];
	// Check for a trailing slash.
	if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
		$url = substr ($url, 0, -1);
		// Chop off the slash.
	}
	// Add the page.
	$url .='/overview'; // Overview page for now.
	
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
}

//-----------------------------------------------------------POST / SESSIONS-------------------------------------------------------------------------

if (isset($_POST['reset']))
{
  // Set Reset Password variable if needed
  $reset = True ;
}
else
{
  // Set Reset Password variable
  $reset = False ;
}

/*-----------------------------------Collect Variables and Send all------------------------------------ */

// Must pass in variables (as an array) to use in template
$variables = array(
    'reset' => $reset,
);

renderLayoutWithContentFile("template_home_reset.php", $variables) ;

?>