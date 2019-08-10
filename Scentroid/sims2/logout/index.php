<?php

// Start output buffering.
ob_start();
// Initialize a session.
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');

// If no first_name variable exists, redirect the user.
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
	$url .='/index.php';
	
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
} 
else 
{
  // Destroy this session!
	$_SESSION = array(); // Destroy the variables.
	session_destroy(); // Destroy the session itself.
	setcookie (session_name(), '', time()-300, '/', '', 0); // Destroy the cookie.
  
  // Redirect the user to the Login page
  // Start defining the URL.
  $url = 'http://' . $_SERVER['HTTP_HOST'];
  // Check for a trailing slash.
  if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
    $url = substr ($url, 0, -1);
    // Chop off the slash.
  }
  $url .= '/login/index.php';
  
  ob_end_clean(); // Delete the buffer.
  header("Location: $url");
  exit(); // Quit the script.
	
}

// Flush the buffered output.
ob_end_flush();

?>

