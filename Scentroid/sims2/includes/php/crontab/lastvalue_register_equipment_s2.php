<?php

require_once('/var/www/html/includes/php/crontab/common_cron.php') ;

// Connect to db
$dbc_local = db_connect_local() ;
$sims2_query_url = 'http://sims2.scentroid.com:8080/equipment/map' ;
$result_json = getSims2QueryResult($sims2_query_url) ;

//$features_json = $json_result->features ;

$result_obj = json_decode($result_json) ;

$features_obj = $result_obj->features ;
$equipment_arr = array() ;
$equipment_iter = 0 ;
foreach ($features_obj as $feature)
{
  //echo $feature->geometry . '; ' ;
  //echo '; ' ;
  $geometry_obj = $feature->geometry ;
  $properties_obj = $feature->properties ;
  $type = $feature->type ;
  
  $model = $properties_obj->model ;
  $id = $properties_obj->id ;
  $company = $properties_obj->company ;
  $name = $properties_obj->name ;
  $notification_on = $properties_obj->notification_on ;
  $serial_number = $properties_obj->serial_number ;
  $status = $properties_obj->status ;
  $last_sample = $properties_obj->last_sample ;
  
  //echo $id . '(' . $company . '):' . $name . '; ' . "<br />" ;
  $equipment_arr[$id]["name"] = $name ;
  $equipment_arr[$id]["serial_number"] = $serial_number ;
  $equipment_arr[$id]["company_id"] = $company ;
  $equipment_arr[$id]["lat"] = $geometry_obj->coordinates[1] ;
  $equipment_arr[$id]["lon"] = $geometry_obj->coordinates[0] ;
  $equipment_arr[$id]["sampledat"] = substr($last_sample, 0,-3) ;
  
  $equipment_iter++ ;
}
// Sort by equipment ID
ksort($equipment_arr) ;

foreach ($equipment_arr as $key => $value)
{
  //echo $key . '(' . $value["company_id"] . '):' . $value["name"] . '; ' . "<br />" ;

  $equipment_id = $key ;
  $company_id = $value["company_id"] ;
  $sampledat = $value["sampledat"] ;
  $lat = $value["lat"] ;
  $lon = $value["lon"] ;
  // Check if records of CanvasJS exists in the local DB
  // If not create them
  $query2 = "SELECT lastvalue_equipment_test.id AS lastvalue_id
        FROM lastvalue_equipment_test
        WHERE equipment_id = " . $equipment_id
        . " AND company_id = " . $company_id ;
  $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
  if (mysqli_num_rows($result2) == 0)
  {
    // If no records INSERT new value
    $query3 = "INSERT INTO lastvalue_equipment_test (equipment_id, company_id, lat, lon, sampledat) VALUES ('" 
    . $equipment_id . "', '" . $company_id . "', '" . $lat . "', '" . $lon . "', '" . $sampledat . "'); " ;
    $result3 = mysqli_query($dbc_local, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc_local)) ;
    
  }
  
}

// Close db
db_close($dbc_local) ;

echo "Equipments:($equipment_iter)" ;

//echo "\r\nRegister Sims2 OK; \n" ;

?>