<?php

require_once('/var/www/html/includes/php/crontab/common_cron.php') ;

// Connect to db
$dbc = db_connect_sims() ;
$dbc_local = db_connect_local() ;

// First Get a list of all the equipments
$query = "SELECT equipement.id AS equipment_id, company
          FROM equipement"
          . " ORDER BY equipment_id ASC" ; // LIMIT 1 for testing
$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc)) ;
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
{
  $equipment_id = $row['equipment_id'] ;
  $company_id = $row['company'] ;
  // Check if records of CanvasJS exists in the local DB
  // If not create them
  $query2 = "SELECT lastvalue_equipment.id AS lastvalue_id
        FROM lastvalue_equipment
        WHERE equipment_id = " . $equipment_id ;
  $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
  if (mysqli_num_rows($result2) == 0)
  {
    // If no records INSERT new value
    $query3 = "INSERT INTO lastvalue_equipment (equipment_id, company_id) VALUES (" . $equipment_id . ", " . $company_id . "); " ;
    $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
    
  }
  else
  {
    $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
    $lastvalue_id = $row2[0] ;
    // Update the company in case it has been changed for some reason
    $query3 = "UPDATE lastvalue_equipment 
               SET company_id=" . $company_id
              . " WHERE id = " . $lastvalue_id ;
    $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
    
  }
  
}

// Close db
db_close($dbc) ;
db_close($dbc_local) ;

echo "Register OK; \n" ;

?>