<?php

// PHP 7.0

// Try the connection to the local FTP  + with Root user
function ftp_connect_local()
{
  // MySQL database connection
  // Define the Database parameters
  $ftp_user = 'root' ;
  $ftp_server = '207.246.86.177' ;
  $ftp_password = '+2Hn-MT{kW8k(hq#' ;

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
function ftp_close_local($ftpc)
{
  // Close the local FTP connection
  ftp_close($ftpc);
}

// Try the connection to SIMS 1.0 MySQL  + Select the relevant database SIMS
function db_connect_sims()
{
  // MySQL database connection
  // Define the Database parameters
  $db_user = 'sims' ;
  $db_host = '149.248.50.12' ;
  $db_password = 'l6xYQQRnLAmty1ex' ;
  $db_name = 'sims' ;

  $dbc = new mysqli($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection failed: %s\n". $dbc -> error) ;
  return $dbc ;
}

// Try the connection to DRIMS MySQL  + Select the relevant database DRIMS
function db_connect_drims()
{
  // MySQL database connection
  // Define the Database parameters
  $db_user = 'sims' ;
  $db_host = '149.248.50.12' ;
  $db_password = 'l6xYQQRnLAmty1ex' ;
  $db_name = 'drims' ;

  $dbc = new mysqli($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection failed: %s\n". $dbc -> error) ;
  return $dbc ;
}

// Try the connection to the local MySQL  + Select the relevant database SIMS
function db_connect_local()
{
  // MySQL database connection
  // Define the Database parameters
  $db_user = 'phpmyadmin' ;
  $db_host = 'localhost' ;
  $db_password = 'EQ$ua.12' ;
  $db_name = 'sims' ;

  $dbc = new mysqli($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection failed: %s\n". $dbc -> error) ;
  return $dbc ;
}

// Disconnect from MySQL Database
function db_close($dbc)
{
  // Close this MySQL connection
  $dbc -> close() ;
}

// All the necessary includes for the global page to display
include ('/var/www/html/includes/php/constants.php') ;
include ('/var/www/html/includes/php/libs/lib_main.php') ;
include ('/var/www/html/includes/php/libs/lib_chart.php') ;
include ('/var/www/html/includes/php/libs/lib_map.php') ;
include ('/var/www/html/includes/php/libs/lib_data.php') ;
include ('/var/www/html/includes/php/libs/lib_sims2.php') ;
include ('/var/www/html/includes/php/libs/lib_notification.php') ;
include ('/var/www/html/ademir/libs/lib_data_ademir.php');

?>
