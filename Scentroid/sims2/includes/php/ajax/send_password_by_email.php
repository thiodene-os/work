<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require('/root/vendor/autoload.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');

// This verifies the login credentials of the user after Login attempt
// sends back the script for the login_container

$dbc = db_connect_sims() ;
$dbc_local = db_connect_local() ;

// Admin ID
$admin_id = 10 ;

if (isset($_GET['reset'])) 
{
  
  // Get the selected company ID from GET
  $e = trim(strtolower($_GET['email'])) ;
  
  // Verify that the email/password belong in SIMS
  // Query the database.
  $query = "SELECT id, email, username, name 
            FROM user 
            WHERE (email='$e')" ;
  
  $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
  if (@mysqli_num_rows($result) == 1) 
  { // A match was made.
    
    $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
    $user_id = $row[0] ;
    $username = trim($row[1]) ;
    $name = trim($row[2]) ;
    // Don't reset for Admin because Admin is not an email!
    if ($user_id != $admin_id)
    {
      // Construct a name for email sending
      if (strlen($name) > 0)
        $full_name = $name ;
      else if (strlen($username) > 0)
        $full_name = $username ;
      else
      {
        $full_name_arr = explode('@', $e) ;
        $full_name = $full_name_arr[0] ;
      }
      
      // Update this user's password
      $new_password = substr(hash('sha512',rand()),0,12) ;
      $md5_password = md5($new_password) ;
      $query2 = "UPDATE user SET password='$md5_password' WHERE id = " . $user_id ;
      $result2 = mysqli_query($dbc, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc)) ;
      // Send the new password by email
      $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
      //Server settings
      //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'mail.scentroid.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'alarm@scentroid.com';                 // SMTP username
      $mail->Password = 'salam2030';                           // SMTP password
      $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 465;                                    // TCP port to connect to

      //Recipients
      $mail->setFrom('info@scentroid.com', 'SIMS2.0 Information');
      $mail->addAddress($e,$full_name);     // Add a recipient
      $mail->addReplyTo('info@scentroid.com', 'SIMS2.0 Information');

      //Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'New Sims2.0 Password';
      $mail->Body    = 'Your password for Sims2.0 has been reset, it is now: <b>' . $new_password . '</b>';
      $mail->AltBody = 'Your password for Sims2.0 has been reset, it is now: ' . $new_password ;

      //$mail->send();
      if (!$mail->send())
        $my_ajax_html = '<span>The Password Reset email has not been sent, please try again!</span>' ;
      else
        $my_ajax_html = '' ;
      //echo 'Message has been sent';
    }
    else
    {
      $my_ajax_html = '<span>The Password of the Admin can\'t be reset this way!</span>' ;
    }
  }
  else
  {
    $my_ajax_html = '<span>This Email Address does not exist in our system, try again!</span>' ; 
  }
}
else
{
  // If no equipment ID HTML has to be empty
  $my_ajax_html = '<span>Password Reset attempt failed!</span>' ;
}

db_close($dbc) ;
db_close($dbc_local) ;

echo $my_ajax_html ;

?>