<?php

// Notification libraires for Alarms, Health and all types of Notifications
// SQL + Email or SMS handling and sending

// Builds and returns the Input Select for Equipments
function checkSensorsNotificationValue($user_id)
{
  // Autoload definition for Email and SMS
  require('/root/vendor/autoload.php');
  // Mail
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  // SMS
  use Twilio\Rest\Client;
  $account_sid = 'AC3522ddb3584bc0fd3a918d1dab318b05';
  $auth_token = '36d2d17e06b76c3a9fa834d264991dae';
  
  // Connect to db
  $dbc = db_connect_sims() ;
  $dbc_local = db_connect_local() ;
  
  $query = "SELECT settings.id AS setting_id, settings_json, notification_dt
        FROM settings
        WHERE user_id = " . $user_id ;
  $result = mysqli_query($dbc_local, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc_local));
  $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
  if (@mysqli_num_rows($result) != 0) 
  {
    $settings_json = $row[1] ;
    $notification_dt = strtotime($row[2]) ;
    // If JSON has been saved get the content
    if ($settings_json)
    {
      $settings_obj = json_decode($settings_json) ;
      $alarm_sensor_obj = $settings_obj->alarm_sensor ;
    }
    else
      $alarm_sensor_obj = false ;
  }
  else
    $alarm_sensor_obj = false ;
  
  
  $final_notification = '' ;
  
  // The notification or Alarm has to check values
  // after the date the notification has been set
  
  // Get the Notifications values for each sensor if they have been set
  foreach ($alarm_sensor_obj as $alarm_sensor)
  {
    
    // Enable Check
    if ($alarm_sensor->enabled == 'yes')
    {
      $sensor_id = $alarm_sensor->sensor_id ;
      // From the date of the last check to now (Go through the values and compare to max and min)
      $high_notif_value = $alarm_sensor->low_high_value[0] ;
      $high_notif_value = $alarm_sensor->low_high_value[1] ;
      // Verify that low and high notification values are not empty!
      if (strlen($high_notif_value) > 0 && strlen($low_notif_value) > 0)
      {
        // Get the equipment and company IDs from sensor_id
        $query7 = "SELECT sensor.id AS sensor_id, sensor.equipement, equipment.name, equipement.company
              FROM sensor
              INNER JOIN equipement ON equipement.id = sensor.equipement
              WHERE sensor = " . $sensor_id ;
        $result7 = mysqli_query($dbc, $query7) or trigger_error("Query: $query7\n<br>MySQL Error: " . mysqli_error($dbc));
        $row7 = mysqli_fetch_array($result7, MYSQLI_NUM) ;
        $equipment_id = $row7[1] ;
        $equipment_name = $row7[2] ;
        $company_id = $row7[3] ;
        
        // From settings notification dt to now, check sample values for this sensor
        $update_tmstp = strtotime($notification_dt) ;
        
        // Select sample value where is above threshold
        $query2 = "SELECT sample.id AS sample_id, sample.sampledat
              FROM sample
              WHERE sensor = " . $sensor_id
              . " AND sampledat > " . $notification_dt
              . " AND CAST(value AS UNSIGNED) >= " . $high_notif_value
           // . " AND value >= " . $high_notif_value
              . " ORDER BY sampledat ASC LIMIT 1" ;
        $result2 = mysqli_query($dbc, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc));
        if (@mysqli_num_rows($result2) != 0)
        {
          $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
          $value = $row2[1] ;
          // Write the message under condition
          $high_notif_msg = 'The value of ' . $value . ' (' . $data_unit . ') for sensor ' . $sensor_name 
          . ' in ' . $equipment_name . ' crossed the maximum threshold of ' . $high_notif_value 
          . ' on ' . $value_dt . '.' ;
          
          // INSERT data in alarm_sensor
          $query4 = "INSERT INTO alarm_sensor (message, user_id, sensor_id, equipment_id, company_id, created_dt) VALUES (" 
                    . "'" . $high_notif_msg . "'," . $user_id . ", " . $sensor_id 
                    . ", " . $equipment_id . ", " . $company_id . ",NOW()); " ;
          $result4 = mysqli_query($dbc_local, $query4) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
          
          // Update Notification date
          $query8 = "UPDATE settings SET notification_dt=NOW() WHERE user_id = " . $user_id ;
          $result8 = mysqli_query($dbc_local, $query8) or trigger_error("Query: $query8\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
          
          // Send Email + SMS
          // If alarm email has been sent use it. if not use the user's email
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
          $mail->setFrom('info@scentroid.com', 'SIMS2.0 Notification');
          $mail->addAddress($e,$full_name);     // Add a recipient
          $mail->addReplyTo('info@scentroid.com', 'SIMS2.0 Notification');
          //Content
          $mail->isHTML(true);                                  // Set email format to HTML
          $mail->Subject = 'New Sims2.0 Password';
          $mail->Body    = 'Your password for Sims2.0 has been reset, it is now: <b>' . $new_password . '</b>';
          $mail->AltBody = 'Your password for Sims2.0 has been reset, it is now: ' . $new_password ;
          $mail->send() ;
          
          // Send SMS
          // A Twilio number you own with SMS capabilities
          $twilio_number = "+16477244859";

          $client = new Client($account_sid, $auth_token);
          $client->messages->create(
              // Where to send a text message (your cell phone?)
              '+14167384236',
              array(
                  'from' => $twilio_number,
                  'body' => 'I sent this message in under 10 minutes!'
              )
          );
          
        }
        else
        {
          
        }
        
        // Select sample value where is above threshold
        $query3 = "SELECT sample.id AS sample_id, sample.value, sample.sampledat
              FROM sample
              WHERE sensor = " . $sensor_id
              . " AND sampledat > " . $notification_dt
              . " AND CAST(value AS UNSIGNED) <= " . $low_notif_value
           // . " AND value <= " . $low_notif_value
              . " ORDER BY sampledat ASC LIMIT 1" ;
        $result3 = mysqli_query($dbc, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc));
        if (@mysqli_num_rows($result3) != 0) 
        {
          $row3 = mysqli_fetch_array($result3, MYSQLI_NUM) ;
          $value = $row3[1] ;
          // Write the message under condition
          $high_notif_msg = 'The value of ' . $value. ' (' . $data_unit .  ') for sensor ' . $sensor_name 
          . ' in ' . $equipment_name . ' crossed the maximum threshold of ' . $high_notif_value 
          . ' on ' . $value_dt . '.' ;
          
          // INSERT data in alarm_sensor
          $query4 = "INSERT INTO alarm_sensor (message, user_id, sensor_id, equipment_id, company_id, created_dt) VALUES (" 
                    . "'" . $high_notif_msg . "'," . $user_id . ", " . $sensor_id 
                    . ", " . $equipment_id . ", " . $company_id . ",NOW()); " ;
          $result4 = mysqli_query($dbc_local, $query4) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
          
          // Update Notification date
          $query8 = "UPDATE settings SET notification_dt=NOW() WHERE user_id = " . $user_id ;
          $result8 = mysqli_query($dbc_local, $query8) or trigger_error("Query: $query8\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
          
          // Send Email
          // If alarm email has been sent use it. if not use the user's email
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
          $mail->setFrom('info@scentroid.com', 'SIMS2.0 Notification');
          $mail->addAddress($e,$full_name);     // Add a recipient
          $mail->addReplyTo('info@scentroid.com', 'SIMS2.0 Notification');
          //Content
          $mail->isHTML(true);                                  // Set email format to HTML
          $mail->Subject = 'New Sims2.0 Password';
          $mail->Body    = 'Your password for Sims2.0 has been reset, it is now: <b>' . $new_password . '</b>';
          $mail->AltBody = 'Your password for Sims2.0 has been reset, it is now: ' . $new_password ;
          $mail->send() ;
          
          // Send SMS
          // A Twilio number you own with SMS capabilities
          $twilio_number = "+16477244859";

          $client = new Client($account_sid, $auth_token);
          $client->messages->create(
              // Where to send a text message (your cell phone?)
              '+14167384236',
              array(
                  'from' => $twilio_number,
                  'body' => 'I sent this message in under 10 minutes!'
              )
          );
          
        }
        else
        {
          
        }
        
        /*
        // Write the message under condition
        $high_notif_msg = 'The value of ' . $value . ' for sensor ' . $sensor_name 
        . ' in ' . $equipment_name . ' crossed the maximum threshold of ' . $high_notif_value 
        . ' on ' . $value_dt . '.' ;
        
        $low_notif_msg = 'The value of ' . $value . ' for sensor ' . $sensor_name 
        . ' in ' . $equipment_name . ' crossed the minimum threshold of ' . $low_notif_value 
        . ' on ' . $value_dt . '.' ;
        */
        
        
        
      }
    }
    
  }
  
  // Close db
  db_close($dbc) ;
  db_close($dbc_local) ;
  
  return $final_notification ;
} // checkSensorsNotificationValue

?>