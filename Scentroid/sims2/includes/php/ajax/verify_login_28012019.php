<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
// This verifies the login credentials of the user after Login attempt
// sends back the script for the login_container

$dbc = db_connect_sims() ;

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
  $p = md5(trim($_GET['password'])) ;
  
  // Verify that the email/password belong in SIMS
  // Query the database.
  $query = "SELECT id, email, username, name 
            FROM user 
            WHERE (email='$e' AND password='$p')";
  
  $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
  if (@mysqli_num_rows($result) == 1) 
  { // A match was made.

    $row = mysqli_fetch_array($result, MYSQLI_NUM) ;

    // Register the values & redirect.
    mysqli_free_result($result);
    $_SESSION['loggedin'] = True ;
    $_SESSION['id'] = $row[0];
    $_SESSION['email'] = $row[1];
    
    $username = $row[2];
    $name = $row[3];
    
    // Set up the username for Display
    if (strlen($username) == 0)
      $uname = $name ;
    else
      $uname = $username ;
    // If no username at all make one up
    if (strlen($username) == 0 && strlen($name) == 0)
      $uname = 'User' . $row[0] ;
    
    $_SESSION['username'] = $uname ;
    
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

db_close($dbc) ;

echo $my_ajax_html ;

?>