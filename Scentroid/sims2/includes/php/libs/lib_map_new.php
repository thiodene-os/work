<?php

// Main libraries for Mapping features

// Builds and returns the Main map
function buildMainMap()
{

  // Add an image first for debug and change asap
  //$final_map = "
//<img class=\"main_map\" src=\"images/heatmapapi.png\" />" ;

  $final_map = "
<div id=\"map\"></div>" ;

  return $final_map ;
} // buildMainMap

// Builds and returns the Geo position of the equipment
function buildGeoPosition($equipment)
{

  // Try the connection to MySQL  + Select the relevant database
  // Make the SQL connection global as soon as possible
  $dbc = @mysqli_connect(DB_HOST, DB_USER , DB_PASSWORD) ;
  if ($dbc)
  {
    if(!mysqli_select_db($dbc, DB_NAME))
    {
      trigger_error("Could not select the database!<br>MYSQL Error:" . mysqli_error($dbc)) ;
      exit();
    }
  }
  else
  {
    trigger_error("Could not connect to MySQL!<br>MYSQL Error:" . mysqli_error($dbc));
    exit();
  }

  // Get the last position of the scentinel
  $query = "SELECT sample.id AS sample_id, sample.lat, sample.lon
        FROM sample
        WHERE equipement = " . $equipment
        . " ORDER BY sampledat DESC LIMIT 1";
  $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
  $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
  $lat = $row[1] ;
  $lon = $row[2] ;
  
  $final_geoposition = '{lat: ' . $lat . ', lng: ' .  $lon '};';
  
  return array($final_geoposition) ;
} //buildGeoPosition




?>