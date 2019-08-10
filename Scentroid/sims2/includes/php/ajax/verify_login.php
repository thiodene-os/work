<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
// This verifies the login credentials of the user after Login attempt
// sends back the script for the login_container

$dbc = db_connect_sims() ;
$dbc_local = db_connect_local() ;

// Admin ID
$admin_id = 10 ;

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
  $query = "SELECT id, email, username, name, company 
            FROM user 
            WHERE (email='$e' AND password='$p')";
  
  $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
  if (@mysqli_num_rows($result) == 1) 
  { // A match was made.

    $row = mysqli_fetch_array($result, MYSQLI_NUM) ;

    // Register the values & redirect.
    mysqli_free_result($result) ;
    $_SESSION['loggedin'] = True ;
    $user_id = $row[0] ;
    $_SESSION['id'] = $user_id ;
    $_SESSION['email'] = $row[1] ;
    // Add corresponding company for personalized navigation
    $company_id = $row[4] ;
    $_SESSION['company'] = $company_id ;
    
    // Build a session unique ID
    $session_id = md5($row[0] . '_' . strtotime("now")) ;
    $_SESSION['session_id'] = $session_id ;
    
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
    
    // Update the user_login local table
    // -----------------------------------------------------------------
    $query2 = "SELECT user_login.id AS login_id, last_login_dt
              FROM user_login WHERE user_id = " . $user_id ;
    $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
    if (@mysqli_num_rows($result2) == 0) 
    { // if no records INSERT
      $query3 = "INSERT INTO user_login (user_id, company_id, last_session, last_login_dt, created_dt) VALUES ('" 
                . $user_id . "', '". $company_id . "', '" . $session_id . "',NOW(),NOW()); " ;
      $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
      
      // Create a last login for Notification check
      $last_login = '1000-01-01 00:00:00' ;
    }
    else
    { // If previous record UPDATE
      $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
      $login_id = $row2[0] ;
      $last_login = $row2[1] ;
      
      $query3 = "UPDATE user_login SET last_session='$session_id', company_id='$company_id', last_login_dt=NOW() WHERE id = " . $login_id ;
      $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
    }
    
    // -----------------------------------------------------------------
    // Check the number of Notifications missed from last login from this company (or overall)
    if ($user_id == $admin_id)
    {
      // All the Notifications for Admin
      $query4 = "SELECT COUNT(*) FROM activity_equipment"
                 . " WHERE created_dt >= '" . $last_login . "'" ;
    }
    else
    {
      // All the specific company notifications for non-Admin
      $query4 = "SELECT COUNT(*) FROM activity_equipment WHERE company_id = " . $company_id
                 . " AND created_dt >= '" . $last_login . "'" ;
    }
    $result4 = mysqli_query($dbc_local, $query4) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
    $row4 = mysqli_fetch_array($result4, MYSQLI_NUM) ;
    $num_notifications = $row4[0] ;
    $_SESSION['notifications'] = $num_notifications ;
    
    // If never logged in before -> start the Tour
    if ($last_login == '1000-01-01 00:00:00')
      $my_ajax_html = 'T' ;
    else
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
db_close($dbc_local) ;

echo $my_ajax_html ;

?>