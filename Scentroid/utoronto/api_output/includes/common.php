<?php

// PHP 7.0

// Start output buffering.
ob_start();
// Initialize a session.
session_start();

// Try the connection to the local FTP  + with Root user
function ftp_connect_ut()
{
  // MySQL database connection
  // Define the Database parameters
  $ftp_user = 'scentroid' ;
  $ftp_server = '127.0.0.1' ;
  $ftp_password = 'scentroid' ;

  // set up a connection or die
  if ($ftpc = ftp_connect($ftp_server)) 
  {

    // try to login
    if (!ftp_login($ftpc, $ftp_user, $ftp_password)) 
    {
      echo "Couldn't connect as FTP_USER\n";
      exit();
    }
    
  }
  else 
  { // If it couldn't connect to FTP.
    
    // Print a message to the user, include the footer, and kill the script.
    echo "Couldn't connect to FTP_SERVER"; 
    exit();
    
  } // End of $dbc IF.
  
  return $ftpc ;
}

// Disconnect from FTP
function ftp_close_ut($ftpc)
{
  // Close the local FTP connection
  ftp_close($ftpc);
}

// Try the connection to SIMS 1.0 MySQL  + Select the relevant database SIMS
function db_connect_ut()
{
  // MySQL database connection
  // Define the Database parameters
  $db_user = 'scentroid' ;
  $db_host = 'localhost' ;
  $db_password = 'scentroid' ;
  $db_name = 'utoronto' ;
  
  $dbc = new mysqli($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection failed: %s\n". $dbc -> error) ;
  return $dbc ;
}

// Disconnect from MySQL Database
function db_close($dbc)
{
  // Close this MySQL connection
  $dbc -> close() ;
}

// Website images and files folder
define ("IMAGES_FOLDER","/var/www/html/images") ;

// All the necessary includes for the global page to display
include ($_SERVER['DOCUMENT_ROOT'] . '/includes/php/constants.php') ;




?>
