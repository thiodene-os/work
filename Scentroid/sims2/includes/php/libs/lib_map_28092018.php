<?php

// Main libraries for Mapping features

// 
function getMainEquipmentID($company)
{
  // Connect to db
  $dbc = db_connect_sims() ;
  
  // Get the "main" equipment from the selected company
  $query = "SELECT equipement.id AS equipement_id
            FROM equipement
            WHERE company = " . $company ;
  $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
  if (mysqli_num_rows($result) > 0)
  {
    $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
    $equipment = $row[0] ;
  }
  else
    $equipment = false ;
  
  // Close db
  db_close($dbc) ;
  
  return $equipment ;
}

// Add Notification info in the Pop Up box shown by clicking on the marker
function addNotificationToMap($equipment)
{
  // Connect to db
  $dbc = db_connect_sims() ;
  $dbc_local = db_connect_local() ;
  
  $notice = '' ;
  $listener = '' ;
  
  // Put random number from one to 10 float for AQI;
  $aqi = rand(1,1000)/100;
  
  // Select the name of the equipment for title
  $query = "SELECT equipement.id AS equipement_id, equipement.name, equipement.sn, equipement.status, equipement.notification
        , company.name, company.city
        FROM equipement
        INNER JOIN company ON equipement.company = company.id
        WHERE equipement.id = " . $equipment ;
  $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
  if (mysqli_num_rows($result) > 0)
  {
    $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
    $name = $row[1] ;
    $sn = $row[2] ;
    $status = $row[3] ;
    $notification = $row[4] ;
    if ($notification != 0)
      $last_notification = date('jS M Y h:i:s A', $notification) ;
    else
      $last_notification = 'None' ;
    
    $company_name = $row[5] ;
    $company_city = $row[6] ;
    
    // Get the last sample measured by that Sensor
    $query2 = "SELECT lastvalue_equipment.id AS lastvalue_id, sampledat
          FROM lastvalue_equipment
          WHERE equipment_id = " . $equipment ;
    $result2 = mysqli_query($dbc_local, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc_local));
    if (mysqli_num_rows($result2) > 0)
    {
      $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
      $last_update = timeElapsedString($row2[1]) ;
    }
    else
      $last_update = 'None' ;
    
    $notice = "  var contentString = '<div id=\"content\">'+
      '<div id=\"siteNotice\">'+
      '</div>'+
      '<h1 id=\"firstHeading\" class=\"firstHeading\">" . $name . "</h1>'+
      '<div id=\"bodyContent\">'+
      '<p>SN: <b>" . $sn. "</b> <br />from the company <b>" . $company_name . "</b><br />' +
      'Status: <b>" . $status . "</b><br />'+
      'Last Update: <b>" . $last_update . "</b><br />'+
      'Last Notification: <b>" . $last_notification . "</b><br />'+
      'AQI: <span style=\"color:red; font-weight: bold;\">" . $aqi . "</span><br />'+
      'Location: <b>" . $company_city . "</b>.</p>'+
      '</div>'+
      '</div>';
  
    var infowindow = new google.maps.InfoWindow({
      content: contentString,
      maxWidth: 300
    });" ;
    
    
    $listener .= '  marker.addListener(\'click\', function() {
    infowindow.open(map, marker);
    });' ;
  
  }
  
  // Close db
  db_close($dbc) ;
  db_close($dbc_local) ;
  
  return array($notice, $listener, $aqi) ;
} // addNotificationToMap

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

// Builds and returns the Geo position of an equipment
function buildMultiGeoPositions($company)
{
  // Connect to db
  $dbc = db_connect_sims() ;
  $dbc_local = db_connect_local() ;
  
  // Select all the equipments belonging to this company
  $query = "SELECT lastvalue_equipment.id AS lastvalue_id, equipment_id, lat, lon, sampledat
        FROM lastvalue_equipment
        WHERE company_id = " . $company ;
  $result = mysqli_query($dbc_local, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc_local));
	if (@mysqli_num_rows($result) != 0) 
  {
    $final_geopositions = 'var locations = [ ' ;
    $first_data = true ;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
    {
      if (!$first_data)
        $final_geopositions .= ',' ;
      $equipment = $row['equipment_id'] ;
      $lat = $row['lat'] ;
      $lon = $row['lon'] ;
      
      $last_update = timeElapsedString($row['sampledat']) ;
      
      // Put random number from one to 10 float for AQI;
      $aqi = rand(1,1000)/100;
      
      // Get the additional info for Popup window
      $query2 = "SELECT equipement.id AS equipement_id, equipement.name, equipement.sn, equipement.status, equipement.notification
            , company.name, company.city
            FROM equipement
            INNER JOIN company ON equipement.company = company.id
            WHERE equipement.id = " . $equipment ;
      $result2 = mysqli_query($dbc, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc));
      $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
      $name = $row2[1] ;
      $sn = $row2[2] ;
      $status = $row2[3] ;
      $notification = $row2[4] ;
      if ($notification != 0)
        $last_notification = date('jS M Y h:i:s A', $notification) ;
      else
        $last_notification = 'None' ;
      
      $company_name = $row2[5] ;
      $location = $row2[6] ;
      
      
      $wind_speed = 'wind speed' ;
      $wind_direction = 'wind direction' ;
      $nsensor = 0 ;
      
      // ------------------------------------------------------Forecast--------------------------------------------------------
      // Get the latest recorded forecast for that city
      $query3 = "SELECT wind.id AS wind_id, direction, speed
                FROM wind
                WHERE location = '" . $location . "'" 
                . " ORDER BY createdat DESC LIMIT 1" ;
      $result3 = mysqli_query($dbc, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc));
      if (@mysqli_num_rows($result3) != 0) 
      {
        $row3 = mysqli_fetch_array($result3, MYSQLI_NUM) ;
        $last_direction_value = $row3[1] ;
        $last_speed_value = round($row3[2]/3.6, 1) ;
        $forecast = true ;
      }
      // -----------------------------------------------------Forecast---------------------------------------------------------
      
      
      // Get the last sample measured by that Equipment
      $query4 = "SELECT lastvalue_equipment.id AS lastvalue_id, value_per_sensor, lat, lon
            FROM lastvalue_equipment
            WHERE equipment_id = " . $equipment ;
      $result4 = mysqli_query($dbc_local, $query4) or trigger_error("Query: $query4\n<br>MySQL Error: " . mysqli_error($dbc_local));
      $row4 = mysqli_fetch_array($result4, MYSQLI_NUM) ;

      $json = $row4[1] ;
      $obj = json_decode($json) ;
      $lat = $row4[2] ;
      $lon = $row4[3] ;
      
      // Verify the record has JSON format
      if ($obj)
      {
        foreach ($obj as $sensor_obj)
        {
          if(strtolower($sensor_obj->name) == $wind_speed)
          {
            $last_speed_value = $sensor_obj->value ;
            $nsensor++ ;
          }
          elseif (strtolower($sensor_obj->name) == $wind_direction)
          {
            $last_direction_value = $sensor_obj->value ;
            $nsensor++ ;
          }
        }
      }
      
      // If both sensors are there build the Polygon (Let's consider the info is always there one way or another)
      list($triangle_coordinates, $arrow_coordinates, $color_code) = calcPolygonCoordinates($lat, $lon, $last_speed_value, $last_direction_value) ;
      
      $final_geopositions .= '[' . $equipment . ',' . $lat .  ',' . $lon . ',"' . $name . '","' . $sn . '","' . $company_name 
       . '","' . $status . '","' . $last_update . '","' . $last_notification . '",' . $aqi . ',"' . $location . '","' . $color_code
      . '","' . $triangle_coordinates[0][1] . ',' . $triangle_coordinates[0][2] 
      . '","' . $triangle_coordinates[1][1] . ',' . $triangle_coordinates[1][2]  
      . '","' . $triangle_coordinates[2][1] . ',' . $triangle_coordinates[2][2]
      . '","' . $arrow_coordinates[1][1] . ',' . $arrow_coordinates[1][2] 
      . '","' . $arrow_coordinates[0][1] . ',' . $arrow_coordinates[0][2] . '"]
' ;
      $first_data = false ;
    }
    $final_geopositions .= ' ];
    ' ;
    $final_markers = "    var marker, i, contentString, infowindow;

    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map
      });
      
      infowindow = new google.maps.InfoWindow({
        content: contentString,
        maxWidth: 300
      }); 
      
      marker = new google.maps.Marker({position: new google.maps.LatLng(locations[i][1], locations[i][2]), map: map,
      label: {
        text: \"AQI: \" + locations[i][9],
        color: \"#FFFFFF\",
        fontWeight: \"bold\",
        fontSize: \"16px\"
      },icon: {
      labelOrigin: new google.maps.Point(11,50),url: \"https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_red.png\"}});
    
      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          
        contentString = '<div id=\"content\">'+
        '<div id=\"siteNotice\">'+
        '</div>'+
        '<h1 id=\"firstHeading\" class=\"firstHeading\">' + locations[i][3] + '</h1>'+
        '<div id=\"bodyContent\">'+
        '<p>SN: <b>' + locations[i][4] + '</b> <br />from the company <b>' + locations[i][5]+ '</b><br />' +
        'Status: <b>' + locations[i][6] + '</b><br />'+
        'Last Update: <b>' + locations[i][7] + '</b><br />'+
        'Last Notification: <b>' + locations[i][8] + '</b><br />'+
        'AQI: <span style=\"color:red; font-weight: bold;\">' + locations[i][9] + '</span><br />'+
        'Location: <b>' + locations[i][10] + '</b>.</p>'+
        '</div>'+
        '</div>';
          
          infowindow.setContent(contentString);
          infowindow.open(map, marker);
        }
      })(marker, i));
    
    };" ;
  }
  else
  {
    $final_geopositions = '' ;
    $final_markers = '' ;
  }
    
  
  // Close db
  db_close($dbc) ;
  db_close($dbc_local) ;
  
  return array($final_geopositions, $final_markers) ;
} // buildMultiGeoPositions

// Builds and returns the Geo position of an equipment
function buildGeoPosition($equipment)
{

  // Connect to db
  $dbc_local = db_connect_local() ;

  // Get the last position of the scentinel
  $query = "SELECT lastvalue_equipment.id AS lastvalue_id, lat, lon
        FROM lastvalue_equipment
        WHERE equipment_id = " . $equipment ;
  $result = mysqli_query($dbc_local, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc_local));
  $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
  $lat = $row[1] ;
  $lon = $row[2] ;
  
  $final_geoposition = '{lat: ' . $lat . ', lng: ' .  $lon . '};' ;
  
  // Close db
  db_close($dbc_local) ;
  
  return $final_geoposition ;
} //buildGeoPosition

// Builds and returns polygons for Wind speed and direction
function buildWindPolygon($equipment)
{

  // Connect to db
  $dbc = db_connect_sims() ;
  $dbc_local = db_connect_local() ;
  
  $wind_speed = 'wind speed' ;
  $wind_direction = 'wind direction' ;
  $nsensor = 0 ;
  
  // ------------------------------------------------------Forecast--------------------------------------------------------
  // First check if there are Forecast values for that city (to get the wind speed and direction)
  $forecast = false ;
  $query2 = "SELECT equipement.id AS equipement_id, company.city
            FROM equipement
            INNER JOIN company ON equipement.company = company.id
            WHERE equipement.id = " . $equipment ;
  $result2 = mysqli_query($dbc, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc));
  $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
  $location = $row2[1] ;
  
  // Get the latest recorded forecast for that city
  $query3 = "SELECT wind.id AS wind_id, direction, speed
            FROM wind
            WHERE location = '" . $location . "'" 
            . " ORDER BY createdat DESC LIMIT 1" ;
  $result3 = mysqli_query($dbc, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc));
	if (@mysqli_num_rows($result3) != 0) 
  {
    $row3 = mysqli_fetch_array($result3, MYSQLI_NUM) ;
    $last_direction_value = $row3[1] ;
    $last_speed_value = round($row3[2]/3.6, 1) ;
    $forecast = true ;
  }
  // -----------------------------------------------------Forecast---------------------------------------------------------
  
  
  // Get the last sample measured by that Equipment
  $query = "SELECT lastvalue_equipment.id AS lastvalue_id, value_per_sensor, lat, lon
        FROM lastvalue_equipment
        WHERE equipment_id = " . $equipment ;
  $result = mysqli_query($dbc_local, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc_local));
  $row = mysqli_fetch_array($result, MYSQLI_NUM) ;

  $json = $row[1] ;
  $obj = json_decode($json) ;
  $lat = $row[2] ;
  $lon = $row[3] ;
  
  // Verify the record has JSON format
  if ($obj)
  {
    foreach ($obj as $sensor_obj)
    {
      if(strtolower($sensor_obj->name) == $wind_speed)
      {
        $last_speed_value = $sensor_obj->value ;
        $nsensor++ ;
      }
      elseif (strtolower($sensor_obj->name) == $wind_direction)
      {
        $last_direction_value = $sensor_obj->value ;
        $nsensor++ ;
      }
    }
  }
  
  // If both sensors are there build the Polygon
  if ($nsensor == 2 || $forecast)
  {
    
    list($triangle_coordinates, $arrow_coordinates, $color_code) = calcPolygonCoordinates($lat, $lon, $last_speed_value, $last_direction_value) ;
    
    // Construct the part to display the arrow on the google map
    $final_arrow = '        // Define a symbol using a predefined path (an arrow)
        // supplied by the Google Maps JavaScript API.
        var lineSymbol = {
          path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
        };

        // Create the polyline and add the symbol via the icons property.
        var line = new google.maps.Polyline({
          path: [{lat: ' . $arrow_coordinates[1][1] . ', lng: ' . $arrow_coordinates[1][2] . '}
          , {lat: ' . $arrow_coordinates[0][1] . ', lng: ' . $arrow_coordinates[0][2] . '}],
          strokeColor: \'' . $color_code . '\',
          strokeWeight: 2,
          icons: [{
            icon: lineSymbol,
            offset: \'100%\'
          }],
          map: map
        });
' ;
    
    // Construct the Javascript part to display the polygon on the google map
    // First Coordinates
    $final_polygon = $final_arrow . '  // Polygon Coordinates
  var triangleCoords = [
    new google.maps.LatLng(' . $triangle_coordinates[0][1] . ', ' . $triangle_coordinates[0][2] . '),'
    . '    new google.maps.LatLng(' . $triangle_coordinates[1][1] . ', ' . $triangle_coordinates[1][2] . '),'
    . '    new google.maps.LatLng(' . $triangle_coordinates[2][1] . ', ' . $triangle_coordinates[2][2] . ')'
    . '    ];' ;
  
    // Styling and Controling
    $final_polygon .= '  // Styling & Controls
  myPolygon = new google.maps.Polygon({
    paths: triangleCoords,
    editable: false,
    strokeColor: \'' . $color_code . '\',
    strokeOpacity: 0.5,
    strokeWeight: 2,
    fillColor: \'' . $color_code . '\',
    fillOpacity: 0.35
  });' ;
  
    // Displaying the Polygon
    $final_polygon .= '  myPolygon.setMap(map);
  ' ;
  
  }
  else
  {
    
    
    $final_polygon = '' ;
    $color_code = '#FFFFFF' ;
  }
  
  // Close db
  db_close($dbc) ;
  db_close($dbc_local) ;
  
  return array($final_polygon, $color_code) ;
} //buildWindPolygon


function calcPolygonCoordinates($lat, $lon, $wind_speed, $wind_direction)
{
  // Initial wind factor is 1 (Max is 5)
  //$wind_speed_factor = 6 ;
  if ($wind_speed <= 1.4) // Calm -> Light Air
  {
    $wind_speed_factor = 2 ;
    $color_code = '#46e246'; // Ademir: Good: rgb(70,226,70)
  }
  elseif ($wind_speed > 1.4 && $wind_speed <= 3) // Light Breeze
  {
    $wind_speed_factor = 3 ;
    $color_code = '#ffff00'; // Ademir: Moderate: rgb(255,255,0)
  }
  elseif ($wind_speed > 3 && $wind_speed <= 5) // Gentle Breeze
  {
    $wind_speed_factor = 4 ;
    $color_code = '#ff9900'; // Ademir: Little unhealthy: rgb(255, 153, 0)
  }
  elseif ($wind_speed > 5 && $wind_speed <= 7.8)  // Moderate Breeze
  {
    $wind_speed_factor = 5 ;
    $color_code = '#ff0000'; // Ademir: Unhealthy: rgb(255,0,0)
  }
  elseif ($wind_speed > 7.8 && $wind_speed <= 10)  // Fresh Breeze
  {
    $wind_speed_factor = 6 ;
    $color_code = '#99004d'; // Ademir: Very Unhealthy: rgb(153,0,77)
  }
  else   // Strong Gale
  {
    $wind_speed_factor = 7;
    $color_code = '#7e0123'; // Ademir: Hazardous: rgb(126,1,35)
  }
  
  //$triangle_coordinates = array() ;
  // Initial coordinates based on the shape of the triangle (Multiplied by wind factor on Y for triangle length!)
  $triangle_tip_x = 0 ;
  $triangle_tip_y = 0 * $wind_speed_factor ;
  $triangle_left_x = -0.001 ;
  $triangle_left_y = -0.001 * $wind_speed_factor ;
  $triangle_right_x = 0.001 ;
  $triangle_right_y = -0.001 * $wind_speed_factor ;
  
  /*
  // Arrow on top of triangle to show direction
  $arrow_tip_x = 0 ;
  $arrow_tip_y = 0.001 * $wind_speed_factor ;
  $arrow_back_x = 0 ;
  $arrow_back_y = 0 * $wind_speed_factor ;
  */
  
  // Arrow on top of triangle to show direction
  $arrow_tip_x = 0 ;
  $arrow_tip_y = 0.0005 * $wind_speed_factor ;
  $arrow_back_x = 0 ;
  $arrow_back_y = 0 * $wind_speed_factor ;
  
  // Now rotate the triangle with respect to the wind direction in degrees
  $triangle_tip_nx = $triangle_tip_x * cos(deg2rad($wind_direction)) - $triangle_tip_y * sin(deg2rad($wind_direction)) ;
  $triangle_tip_ny = $triangle_tip_x * sin(deg2rad($wind_direction)) + $triangle_tip_y * cos(deg2rad($wind_direction)) ;
  $triangle_left_nx = $triangle_left_x * cos(deg2rad($wind_direction)) - $triangle_left_y * sin(deg2rad($wind_direction)) ;
  $triangle_left_ny = $triangle_left_x * sin(deg2rad($wind_direction)) + $triangle_left_y * cos(deg2rad($wind_direction)) ;
  $triangle_right_nx = $triangle_right_x * cos(deg2rad($wind_direction)) - $triangle_right_y * sin(deg2rad($wind_direction)) ;
  $triangle_right_ny = $triangle_right_x * sin(deg2rad($wind_direction)) + $triangle_right_y * cos(deg2rad($wind_direction)) ;
  
  // Arrow
  $arrow_tip_nx = $arrow_tip_x * cos(deg2rad($wind_direction)) - $arrow_tip_y * sin(deg2rad($wind_direction)) ;
  $arrow_tip_ny = $arrow_tip_x * sin(deg2rad($wind_direction)) + $arrow_tip_y * cos(deg2rad($wind_direction)) ;
  $arrow_back_nx = $arrow_back_x * cos(deg2rad($wind_direction)) - $arrow_back_y * sin(deg2rad($wind_direction)) ;
  $arrow_back_ny = $arrow_back_x * sin(deg2rad($wind_direction)) + $arrow_back_y * cos(deg2rad($wind_direction)) ;
  
  // Position the tip of the triangle to the gps position of the equipment (Lat = Y, Lon = X)
  $triangle_tip_lat = $lat + $triangle_tip_ny ;
  $triangle_tip_lon = $lon + $triangle_tip_nx ;
  $triangle_left_lat = $lat + $triangle_left_ny ;
  $triangle_left_lon = $lon + $triangle_left_nx ;
  $triangle_right_lat = $lat + $triangle_right_ny ;
  $triangle_right_lon = $lon + $triangle_right_nx ;
  
  // Arrow
  $arrow_tip_lat = $lat + $arrow_tip_ny ;
  $arrow_tip_lon = $lon + $arrow_tip_nx ;
  $arrow_back_lat = $lat + $arrow_back_ny ;
  $arrow_back_lon = $lon + $arrow_back_nx ;
  
  $triangle_coordinates = array
  (
    array("tip",$triangle_tip_lat,$triangle_tip_lon),
    array("left",$triangle_left_lat,$triangle_left_lon),
    array("right",$triangle_right_lat,$triangle_right_lon)
  ) ;
  
  // Arrow
  $arrow_coordinates = array
  (
    array("tip",$arrow_tip_lat,$arrow_tip_lon),
    array("back",$arrow_back_lat,$arrow_back_lon)
  ) ;
  
  return array($triangle_coordinates, $arrow_coordinates, $color_code) ;
} //calcPolygonCoordinates

?>