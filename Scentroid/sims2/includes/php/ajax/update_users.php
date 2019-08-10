<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
// This saves all the Settings information chosen by the user
// sends back the script for the Saving status

$dbc = db_connect_sims() ;

$my_ajax_html = '' ;
if (isset($_GET['update'])) 
{
  // Get all the Settings info from JSON
  $user_json = $_GET['info'] ;
  
  // Decode JSON
  $user_obj = json_decode($user_json) ;
  
  // Get all the info (screened by Javascript!)
  $user_id = $user_obj->user_id ;
  $e = trim(strtolower($user_obj->email)) ;
  $p = trim($user_obj->password) ;
  if (strlen($p) > 0)
  {
    // Calculate MD5 here, if not it will provide an MD5 of even an empty string!!
    $p = md5($p) ;
    $p_query = ", password='$p'" ;
  }
  else
    $p_query = '' ;
  $name = $user_obj->name ;
  $family = $user_obj->family ;
  $phone = $user_obj->phone ;
  $role = '["' . $user_obj->role . '"]' ;
  $company_id = $user_obj->company_id ;
  $timestamp = strtotime("now") ;
  
  // First see if there is a user_id or not
  if (strlen($user_id) == 0)
  {
    // First verify a user with the same email is not already registered
    $query2 = "SELECT user.id AS user_id, email, username, name 
              FROM user WHERE (email='$e')";
    
    $result2 = mysqli_query($dbc, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc));
    if (@mysqli_num_rows($result2) == 0) 
    { // A match was made.
    
      // If no previous User record INSERT the new user
      $query = "INSERT INTO user (email, tel, username, name, family, image, company, birthdate, gender, location, roles, extra, password, createdat, expireat) 
              VALUES ('" . $e . "', '" . $phone . "', '', '" . $name . "', '" . $family . "', '', " . $company_id . ", 'null', 'o', 'null', '" . $role . "', '', '" . $p ."', '" . $timestamp . "', 0); " ;
      $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc)) ;
      if (mysqli_affected_rows($dbc) == 0)
        $my_ajax_html = '<span style="color:red">User (INSERT) failed!</span>' ;
    }
    else
    {
      $my_ajax_html = '<span style="color:red">A user with same Email Address has already been registered!</span>' ;
    }
  }
  else
  {
    // Prepare the query for not being able to update an ADMIN
      // Verify the role of that User
    $query2 = "SELECT user.id AS user_id, roles 
              FROM user WHERE id='$user_id'";
    
    $result2 = mysqli_query($dbc, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc));
    $row2 = mysqli_fetch_array($result2, MYSQLI_NUM);
    $roles = $row2[1];
    if (strpos($roles,'admin') > 0)
    {
      $my_ajax_html = '<span style="color:red">An Admin can\'t be updated!</span>' ;
    }
    else
    {
      // Update the Settings info for that user
      $query = "UPDATE user 
                 SET name='$name', email= '$e', tel='$phone', family='$family', roles='$role'" . $p_query
                . " WHERE id = " . $user_id ;
      $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc)) ;
      
      if (mysqli_affected_rows($dbc) == 0)
      {
        $my_ajax_html = '<span style="color:red">User (UPDATE) failed!</span>' ;
      }
      
    }
    
  }
  
}
else
{
  // If delete 
  if (isset($_GET['delete'])) 
  {
    // Delete the user if it doesn't have "Manager" or "Admin" roles
    $user_id = $_GET['user_id'] ;
    
      // Verify the role of that User
    $query2 = "SELECT user.id AS user_id, roles 
              FROM user WHERE id='$user_id'";
    
    $result2 = mysqli_query($dbc, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc));
    $row2 = mysqli_fetch_array($result2, MYSQLI_NUM);
    $roles = $row2[1];
    if (strpos($roles,'admin') > 0 || strpos($roles,'manager') > 0)
    {
      // Security for Admins and Managers
      //$my_ajax_html = '<span style="color:red">An Admin or a Manager can\'t be deleted!</span>' ;
      $my_ajax_html = 'An Admin or a Manager can\'t be deleted!' ;
    }
    else
    {
      // Simply delete the user
      $query3 = "DELETE FROM user WHERE id='$user_id' LIMIT 1";
      $result3 = mysqli_query($dbc, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc));
      if (mysqli_affected_rows($dbc) == 0)
        $my_ajax_html = 'This User could not be deleted!' ;
      //$my_ajax_html = '<span style="color:red">This User could not be deleted!</span>' ;
    }
  }
  else
  {
    // If no equipment ID HTML has to be empty
    $my_ajax_html = '<span style="color:red">User update failed!</span>' ;
  }
}


db_close($dbc) ;

echo $my_ajax_html ;

?>
