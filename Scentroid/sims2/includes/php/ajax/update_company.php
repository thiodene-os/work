<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
// This saves all the Settings information for the Equipment
// sends back the script for the Saving status

$dbc = db_connect_sims() ;

$my_ajax_html = '' ;
if (isset($_GET['update'])) 
{
  // Get all the Company info from JSON
  $company_json = $_GET['info'] ;
  
  // Decode JSON
  $company_obj = json_decode($company_json) ;
  
  // Get all the info (screened by Javascript!)
  $company_id = $company_obj->company_id ;
  $name = $company_obj->name ;
  $city = $company_obj->city ;
  $timezone = $company_obj->timezone ;
  $alarm_email = $company_obj->alarm_email ;
  $address = $company_obj->address ;
  $tel = $company_obj->tel ;
  $logo = $company_obj->logo ;
  $manager = $company_obj->manager ;
  
  $updatedat = strtotime("now") ;
  $createdat = strtotime("now") ;
  //$creator = 10 ;
  // For now! Don't forget to build equipment Settings page....
  $extra_array = $company_obj->extra ; // '\r\n'
  $category = '' ;
  foreach ($extra_array as $value) 
  {
    if (strlen($category) == 0)
      $category .= $value ;
    else
      $category .= '<br />' . $value ;
  }
  $extra = '{"equipment_category":"' . $category . '", "updatedat":"' . $updatedat . '"}' ;
  
  // First see if there is a user_id or not
  if (strlen($company_id) == 0)
  {
    
    // If no previous INSERT the new Sensor
    $query = "INSERT INTO company (name, city, timezone, alarm_email, address, tel, "
              . "logo, manager, extra, createdat, creator) VALUES ('" 
              . $name . "', '" . $city . "', '" . $timezone . "', '" 
              .  $alarm_email . "', '" . $address . "', '"
              . $tel . "', '" . $logo . "', '" . $manager
              . "', '"  . $extra . "', '"  . $createdat . "', 10); " ;
    $query = str_replace('<br />','\\\r\\\n', $query) ;
    $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc)) ;
    if (mysqli_affected_rows($dbc) == 0)
      $my_ajax_html = '<span style="color:red">Company (INSERT) failed!</span>' ;
  }
  else
  {
    // Update the Settings info for that user
    $query = "UPDATE company 
               SET name='$name', city= '$city', timezone='$timezone', alarm_email='$alarm_email' , address='$address'
               , tel='$tel', logo='$logo', manager='$manager', extra='$extra'
                WHERE id = " . $company_id ;
    $query = str_replace('<br />','\\\r\\\n', $query) ;
    $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc)) ;
    if (mysqli_affected_rows($dbc) == 0)
    {
      $my_ajax_html = '<span style="color:red">Company (UPDATE) failed!</span>' ;
    }
    
  }
  
}
else
{
  // If delete 
  if (isset($_GET['delete'])) 
  {
    // Delete the company
    $company_id = $_GET['company_id'] ;
    
    // Simply delete the Company (Don't delete id-> 1 Million)
    $query3 = "DELETE FROM company WHERE id='$company_id' LIMIT 1";
    //$query3 = "DELETE FROM company WHERE id='1000000' LIMIT 1";
    $result3 = mysqli_query($dbc, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc));
    if (mysqli_affected_rows($dbc) == 0)
      $my_ajax_html = 'This Company could not be deleted!' ;
  }
  else
  {
    // If no successful update print message
    $my_ajax_html = '<span style="color:red">Company (DELETE) failed!</span>' ;
  }
}


db_close($dbc) ;

echo $my_ajax_html ;

?>