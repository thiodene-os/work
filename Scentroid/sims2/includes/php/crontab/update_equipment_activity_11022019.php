<?php

require_once('/var/www/html/includes/php/crontab/common_cron.php') ;

// Connect to db
$dbc = db_connect_sims() ;
$dbc_local = db_connect_local() ;

// Better use createdat instead of sampledat

// First Get a list of all the equipments
$query = "SELECT equipement.id AS equipment_id, equipement.name AS equipment_name, company
          FROM equipement
          INNER JOIN company ON company.id = equipement.company"
          . " ORDER BY equipment_id ASC" ; // LIMIT 1 for testing
$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc)) ;
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
{
  $equipment_id = $row['equipment_id'] ;
  $equipment_name = $row['equipment_name'] ;
  $company_id = $row['company'] ;
  // Check if records of Activity
  // If not create them
  $query2 = "SELECT activity_equipment.id AS activity_id, status_id, category_id
        FROM activity_equipment
        WHERE equipment_id = " . $equipment_id
       . " ORDER BY activity_equipment.id DESC LIMIT 1" ;
  $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
  if (mysqli_num_rows($result2) == 0)
  {
    // First select the last sample value for that equipment (if any!) and 
    // Define the intial Activity value
    $query4 = "SELECT sample.id AS sample_id, createdat
              FROM sample
              WHERE equipement = " . $equipment_id
              . " ORDER BY createdat ASC LIMIT 1" ;
    $result4 = mysqli_query($dbc, $query4) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysqli_error($dbc)) ;
    if (mysqli_num_rows($result4) == 0)
    {
      // If no prior samples INSERT Activity
      $message = $equipment_name . ' has not been sending data to Cloud yet!' ;
      // If no records INSERT new value
      $query3 = "INSERT INTO activity_equipment (message, equipment_id, company_id, status_id, category_id, created_dt) VALUES ('" 
                . $message . "', " . $equipment_id . ", " . $company_id . ", " . EQUIPMENT_NO_DATA . ", " . EQUIPMENT_CONNECTION_TO_CLOUD . ",NOW()); " ;
      $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
      
    }
    else
    {
      // If no recorded date use today
      $row4 = mysqli_fetch_array($result4, MYSQLI_NUM) ;
      $last_data_timestamp = $row4[1] ;
      $last_data_datetime = date("Y-m-d H:i:s", $last_data_timestamp) ;
      $last_data_date = date("l jS \of F Y", $last_data_timestamp) ;
      
      // If prior activity write message
      $message = $equipment_name . ' is sending data to Cloud as of ' . $last_data_date ;
      
      // If no records INSERT new value
      $query3 = "INSERT INTO activity_equipment (message, equipment_id, company_id, status_id, category_id, created_dt) VALUES ('" 
                . $message . "', " . $equipment_id . ", " . $company_id . ", " . EQUIPMENT_SENDING . ", " . EQUIPMENT_CONNECTION_TO_CLOUD . ",NOW()); " ;
      $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
    }
    
  }
  else
  {
    // If already a Notification record check its status and add new notification if needed
    // If the status ID is 0 do nothing (still no sample data)
    $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
    $status_id = $row2[1] ;
    $category_id = $row2[1] ;
    // Based on the Status check if any new samples have been received
    if ($status_id == EQUIPMENT_NO_DATA)
    {
      // If sending calculate the date of the last Sample to see if it's not old over 7 days
      // Define the intial Activity value
      $query5 = "SELECT sample.id AS sample_id, createdat
                FROM sample
                WHERE equipement = " . $equipment_id
                . " ORDER BY createdat DESC LIMIT 1" ;
      $result5 = mysqli_query($dbc, $query5) or trigger_error("Query: $query5\n<br>MySQL Error: " . mysqli_error($dbc)) ;
      if (mysqli_num_rows($result5) != 0)
      {
        // If no recorded date use today
        $row5 = mysqli_fetch_array($result5, MYSQLI_NUM) ;
        $last_data_timestamp = $row5[1] ;
        $last_data_datetime = date("Y-m-d H:i:s", $last_data_timestamp) ;
        $last_data_date = date("l jS \of F Y", $last_data_timestamp) ;
        
        // If prior activity write message
        $message = $equipment_name . ' is sending data to Cloud as of ' . $last_data_date ;
        
        // If no records INSERT new value
        $query3 = "INSERT INTO activity_equipment (message, equipment_id, company_id, status_id, category_id, created_dt) VALUES ('" 
                  . $message . "', " . $equipment_id . ", " . $company_id . ", " . EQUIPMENT_SENDING . ", " . EQUIPMENT_CONNECTION_TO_CLOUD . ",NOW()); " ;
        $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
      }
      
    }
    elseif ($status_id == EQUIPMENT_SENDING)
    {
      // Check how old is the last sample from that Equipment
      // if older than 7 days change status to NOT SENDING
      $query5 = "SELECT sample.id AS sample_id, createdat
                FROM sample
                WHERE equipement = " . $equipment_id
                . " ORDER BY createdat DESC LIMIT 1" ;
      $result5 = mysqli_query($dbc, $query5) or trigger_error("Query: $query5\n<br>MySQL Error: " . mysqli_error($dbc)) ;
      if (mysqli_num_rows($result5) != 0)
      {
        $row5 = mysqli_fetch_array($result5, MYSQLI_NUM) ;
        $last_data_timestamp = $row5[1] ;
        $last_data_date = date("l jS \of F Y", $last_data_timestamp) ;
        // Based on today get the 7 days ago date
        $today_timestamp = strtotime("now") ;
        $seven_days_ago_timestamp = strtotime('-7 days', $today_timestamp) ;
        
        // If sample date older than 7 days ago Notify
        if ($last_data_timestamp <= $seven_days_ago_timestamp)
        {
          //$message = $equipment_name . ' has stopped sending data to Cloud! 7day_tmstp:' . $seven_days_ago_timestamp . ', last_sample_tmstp:' . $last_data_timestamp ;
          $message = $equipment_name . ' has stopped sending data to Cloud on ' . $last_data_date ;
          $query6 = "INSERT INTO activity_equipment (message, equipment_id, company_id, status_id, category_id, created_dt) VALUES ('" 
                    . $message . "', " . $equipment_id . ", " . $company_id . ", " . EQUIPMENT_NOT_SENDING . ", " . EQUIPMENT_CONNECTION_TO_CLOUD . ",NOW()); " ;
          $result6 = mysqli_query($dbc_local, $query6) or trigger_error("Query: $query6\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
        }
        
      }
      else
      {
        // If data disappeared say it has been removed and status 0
        $message = 'All Data has been removed from equipment: ' . $equipment_name ;
        $query6 = "INSERT INTO activity_equipment (message, equipment_id, company_id, status_id, category_id, created_dt) VALUES ('" 
                  . $message . "', " . $equipment_id . ", " . $company_id . ", " . EQUIPMENT_NO_DATA . ", " . EQUIPMENT_CONNECTION_TO_CLOUD . ",NOW()); " ;
        $result6 = mysqli_query($dbc_local, $query6) or trigger_error("Query: $query6\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
      }
      
      
    }
    elseif ($status_id == EQUIPMENT_NOT_SENDING)
    {
      // If that status is Not sending (2) check how recent is the last sample received
      $query5 = "SELECT sample.id AS sample_id, createdat
                FROM sample
                WHERE equipement = " . $equipment_id
                . " ORDER BY createdat DESC LIMIT 1" ;
      $result5 = mysqli_query($dbc, $query5) or trigger_error("Query: $query5\n<br>MySQL Error: " . mysqli_error($dbc)) ;
      if (mysqli_num_rows($result5) != 0)
      {
        $row5 = mysqli_fetch_array($result5, MYSQLI_NUM) ;
        $last_data_timestamp = $row5[1] ;
        $last_data_date = date("l jS \of F Y", $last_data_timestamp) ;
        // Based on today get the 7 days ago date
        $today_timestamp = strtotime("now") ;
        $seven_days_ago_timestamp = strtotime('-7 days', $today_timestamp) ;
        // Check for 2 months ago
        $two_month_ago_timestamp = strtotime('-2 months', $today_timestamp) ;
        
        // If sample date newer than 7 days ago Notify
        if ($last_data_timestamp > $seven_days_ago_timestamp)
        {
          //$message = $equipment_name . ' has resumed sending data to Cloud! 7day_tmstp:' . $seven_days_ago_timestamp . ', last_sample_tmstp:' . $last_data_timestamp ;
          $message = $equipment_name . ' has resumed sending data to Cloud on ' . $last_data_date ;
          $query6 = "INSERT INTO activity_equipment (message, equipment_id, company_id, status_id, category_id, created_dt) VALUES ('" 
                    . $message . "', " . $equipment_id . ", " . $company_id . ", " . EQUIPMENT_SENDING . ", " . EQUIPMENT_CONNECTION_TO_CLOUD . ",NOW()); " ;
          $result6 = mysqli_query($dbc_local, $query6) or trigger_error("Query: $query6\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
        }
        
        // If older than 2 months ago with the Not Sending Status
        if ($last_data_timestamp < $two_month_ago_timestamp)
        {
          $message = $equipment_name . ' has become inactive since ' . $last_data_date ;
          $query6 = "INSERT INTO activity_equipment (message, equipment_id, company_id, status_id, category_id, created_dt) VALUES ('" 
                    . $message . "', " . $equipment_id . ", " . $company_id . ", " . EQUIPMENT_INACTIVE . ", " . EQUIPMENT_CONNECTION_TO_CLOUD . ",NOW()); " ;
          $result6 = mysqli_query($dbc_local, $query6) or trigger_error("Query: $query6\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
        }
      }
      else
      {
        // If data disappeared say it has been removed and status 0
        $message = 'All Data has been removed from equipment: ' . $equipment_name ;
        $query6 = "INSERT INTO activity_equipment (message, equipment_id, company_id, status_id, category_id, created_dt) VALUES ('" 
                  . $message . "', " . $equipment_id . ", " . $company_id . ", " . EQUIPMENT_NO_DATA . ", " . EQUIPMENT_CONNECTION_TO_CLOUD . ",NOW()); " ;
        $result6 = mysqli_query($dbc_local, $query6) or trigger_error("Query: $query6\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
      }
      
    }
    elseif ($status_id == EQUIPMENT_INACTIVE)
    {
      
      // If that status is Inactive (3) check how recent is the last sample received
      $query5 = "SELECT sample.id AS sample_id, createdat
                FROM sample
                WHERE equipement = " . $equipment_id
                . " ORDER BY createdat DESC LIMIT 1" ;
      $result5 = mysqli_query($dbc, $query5) or trigger_error("Query: $query5\n<br>MySQL Error: " . mysqli_error($dbc)) ;
      if (mysqli_num_rows($result5) != 0)
      {
        $row5 = mysqli_fetch_array($result5, MYSQLI_NUM) ;
        $last_data_timestamp = $row5[1] ;
        $last_data_date = date("l jS \of F Y", $last_data_timestamp) ;
        // Based on today get the 7 days ago date
        $today_timestamp = strtotime("now") ;
        // Check for 2 months ago
        $two_month_ago_timestamp = strtotime('-2 months', $today_timestamp) ;
        
        // If sample date newer than 7 days ago Notify
        if ($last_data_timestamp > $two_month_ago_timestamp)
        {
          $message = $equipment_name . ' has resumed sending data to Cloud on ' . $last_data_date ;
          $query6 = "INSERT INTO activity_equipment (message, equipment_id, company_id, status_id, category_id, created_dt) VALUES ('" 
                    . $message . "', " . $equipment_id . ", " . $company_id . ", " . EQUIPMENT_SENDING . ", " . EQUIPMENT_CONNECTION_TO_CLOUD . ",NOW()); " ;
          $result6 = mysqli_query($dbc_local, $query6) or trigger_error("Query: $query6\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
        }
        
      }
      else
      {
        // If data disappeared say it has been removed and status 0
        $message = 'All Data has been removed from equipment: ' . $equipment_name ;
        $query6 = "INSERT INTO activity_equipment (message, equipment_id, company_id, status_id, category_id, created_dt) VALUES ('" 
                  . $message . "', " . $equipment_id . ", " . $company_id . ", " . EQUIPMENT_NO_DATA . ", " . EQUIPMENT_CONNECTION_TO_CLOUD . ",NOW()); " ;
        $result6 = mysqli_query($dbc_local, $query6) or trigger_error("Query: $query6\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
      }
      
    }
    
    
  }
  
}

// Close db
db_close($dbc) ;
db_close($dbc_local) ;

echo "Notifications:OK; \n" ;

?>