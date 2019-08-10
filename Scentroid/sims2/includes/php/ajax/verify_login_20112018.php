<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
// This verifies the login credentials of the user after Login attempt
// sends back the script for the login_container

//$dbc = db_connect_sims() ;

if (isset($_GET['login'])) 
{
  /*
  // Get the selected company ID from GET
  $e = $_GET['email'] ;
  $p = $_GET['password'] ;
  
  $query = "SELECT id, email, username, name 
            FROM user 
            WHERE (email='$e' AND password=SHA('$p'))";
  */
  
  // Get the selected company ID from GET
  $e = trim(strtolower($_GET['email'])) ;
  //$p = md5(trim($_GET['password'])) ;
  $p = 'ides1980' ;
  
  if ($e == 'admin' && $p == 'ides1980')
  {
    $_SESSION['loggedin'] = True ;
    $_SESSION['id'] = 10 ;
    $_SESSION['email'] = 'admin';
    $_SESSION['username'] = 'Admin' ;
    
    $my_ajax_html = '' ;
  }
  else
  {
    $my_ajax_html = '<span>The provided Login credentials are wrong, try again!</span>' ; 
  }
}
else
{
  // If no equipment ID HTML has to be empty
  $my_ajax_html = '<span>Login attempt failed!</span>' ;
}

//db_close($dbc) ;

echo $my_ajax_html ;

?>