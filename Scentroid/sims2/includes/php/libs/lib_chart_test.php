<?php

// Main libraries for Chart features

// Builds and returns the Top Input Fields (Dates, Sensors Update buttons etc...)
function buildTopInputFields()
{
  // Build the top input bar
  $final_top_input = "" ;
  
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
  
  // Get all the Scentinals and the respective company name and build the top input select field
	$query = "SELECT equipement.id AS equip_id, equipement.name AS equip_name
            , equipement.company AS company_id
            , company.name AS company_name
            FROM equipement 
            INNER JOIN company ON equipement.company = company.id 
            ORDER BY equipement.id DESC";
	$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
	if (@mysqli_num_rows($result) != 0) 
  {
    $final_top_input .= "
                  <label>Scentinal:</label>
                  <select class=\"input_select\">" ;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
    {
      $equipement_id = $row['equip_id'] ;
      $equipement_name = $row['equip_name'] ;
      $company_id = $row['company_id'] ;
      $company_name = $row['company_name'] ;
      
      $final_top_input .= "<option value=\"" . $equipement_id . "\">" . $equipement_name . " -:- " . $company_name . "</option>";

		}
    $final_top_input .= "</select>" ;
	}
  
  $final_top_input .= "
                  <label>Begin Date:</label><input type=\"text\" id=\"begin_date\" class=\"input_date\" name=\"begin_date\" value=\"\" />  
                  <label>End Date:</label><input type=\"text\" id=\"end_date\" class=\"input_date\" name=\"end_date\" value=\"\" />
                  <button class=\"button_update\">Update</button>" ;
                  
                  
  
  return $final_top_input ;
} // buildTopInputFields 

// Builds and returns Charts for Meteorological Data
function displayMetChart($chart_number)
{

  // Add the image to be replaced asap
  $final_met_chart = "  <div id=\"chartContainer" . $chart_number . "\" style=\"height: 300px; width: 100%; float:right;\">
  </div>" ;

  return $final_met_chart ;
} // buildMetChart

// Builds and returns Charts for Meteorological Table
function buildMetTable($chart_number)
{

  // Start by selecting the parameters that are not PPM in Dataunit
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
  
  // Add the frozen table to be replaced asap
  $final_met_table = "
  <table class=\"scroll met_data\">
  <thead>
  <tr>
  <th>Parameter</th>
  <th>Value</th>
  <th>Updated</th>
  <th>&nbsp;</th>
  </tr>
  </thead>" ;

  // Get all the Met sensor (Not PPM for now) for the equipement 86
  $plot_number = 0 ;
	$query = "SELECT sensor.id AS sensor_id, sensor.name AS sensor_name
            , sensor.dataunit AS sensor_dataunit
            FROM sensor
            WHERE equipement = " . 86 
            . " AND dataunit != " . "'PPM'"
            . " ORDER BY sensor.id ASC" ;            ;
	$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
	if (@mysqli_num_rows($result) != 0) 
  {
    $final_met_table .= "<tbody>" ;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
    {
      $sensor_id = $row['sensor_id'] ;
      $sensor_name = $row['sensor_name'] ;
      $sensor_dataunit = $row['sensor_dataunit'] ;
      
      // Get the last sample measured by that Sensor
	    $query2 = "SELECT sample.id AS sample_id, sample.value AS sample_value, sampledat
            FROM sample
            WHERE equipement = " . 86 
            . " AND sensor = " . $sensor_id 
            . " ORDER BY sampledat DESC LIMIT 1";
      $result2 = mysqli_query($dbc, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc));
      $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
      $last_sample_value = $row2[1] ;
      $last_sample_time = timeElapsedString($row2[2]) ;
      
      $final_met_table .= "<tr><td>" . $sensor_name . "</td><td>" . $last_sample_value . "</td><td>" . $last_sample_time 
                       . "<td><span class=\"option selectable remove\" chart=\"" . $chart_number . "\" plot_number=\"" . $plot_number 
                       . "\" series=\"" . $plot_number . "\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>"
                       . "<span class=\"option selectable add\" chart=\"" . $chart_number . "\" plot_number=\"" . $plot_number 
                       . "\" series=\"" . $plot_number . "\" sensor_name=\"" . $sensor_name . "\">PLOT</span></td>"
                       . "</tr>";
      $plot_number++ ;
		}
    $final_met_table .= "</tbody>" ;
	}


  $final_met_table .= "

</table>" ;

  return $final_met_table ;
} // buildMetTable

// Builds and returns Charts for Sensor Data
function displaySensorChart($chart_number)
{

  // Add the image to be replaced asap
  //$final_sensor_chart = "<img class=\"main_graph\" src=\"images/sensor_chart.jpg\" />
//" ;
  
  $final_sensor_chart = "  <div id=\"chartContainer" . $chart_number . "\" style=\"height: 400px; width: 100%;\">
  </div>" ;
  
  return $final_sensor_chart ;
} // buildSensorChart

// Builds and returns Table for Sensor Data
function buildSensorTable($chart_number)
{

  // Start by selecting the parameters that are not PPM in Dataunit
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

  // Add the frozen table to be replaced asap
  $final_sensor_table = "
  <table class=\"scroll sensor_data\">
  <thead>
  <tr>
  <th>Sensors</th>
  <th>Concentration</th>
  <th>Updated</th>
  <th>&nbsp;</th>
  </tr>
  </thead>" ;

  // Get all the Met sensor (Not PPM for now) for the equipement 86
  $plot_number = 0 ;
	$query = "SELECT sensor.id AS sensor_id, sensor.name AS sensor_name
            , sensor.dataunit AS sensor_dataunit
            FROM sensor
            WHERE equipement = " . 86 
            . " AND dataunit = " . "'PPM'"
            . " ORDER BY sensor.id ASC" ;
	$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
	if (@mysqli_num_rows($result) != 0) 
  {
    $final_sensor_table .= "<tbody>" ;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
    {
      $sensor_id = $row['sensor_id'] ;
      $sensor_name = $row['sensor_name'] ;
      $sensor_dataunit = $row['sensor_dataunit'] ;
      
      // Get the last sample measured by that Sensor
	    $query2 = "SELECT sample.id AS sample_id, sample.value AS sample_value, sampledat
            FROM sample
            WHERE equipement = " . 86 
            . " AND sensor = " . $sensor_id
            . " ORDER BY sampledat DESC LIMIT 1";
      $result2 = mysqli_query($dbc, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc));
      $row2 = mysqli_fetch_array($result2, MYSQLI_NUM) ;
      $last_sample_value = $row2[1] ;
      $last_sample_time = timeElapsedString($row2[2]) ;
      
      $final_sensor_table .= "<tr><td>" . $sensor_name . "</td><td>" . $last_sample_value . "</td><td>" . $last_sample_time 
                       . "<td><span class=\"option selectable remove\" chart=\"" . $chart_number . "\" plot_number=\"" . $plot_number 
                       . "\" series=\"" . $plot_number . "\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>"
                       . "<span class=\"option selectable add\" chart=\"" . $chart_number . "\" plot_number=\"" . $plot_number 
                       . "\" series=\"" . $plot_number . "\" sensor_name=\"" . $sensor_name . "\">PLOT</span></td>"
                       . "</tr>";
      $plot_number++ ;
		}
    $final_sensor_table .= "</tbody>" ;
	}

  $final_sensor_table .= "

</table>" ;


  return $final_sensor_table ;
} // buildSensorTable


// Builds and returns JS data for each sensors of the equipement
function buildSensorDataCanvasJS($chart_number, $met = false)
{
  // Connect to the db
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
  
  // Add the condition for Met data
  if ($met)
  {
    $sign = '!' ;
    $naxis = 0 ;
    $nsensor = 0 ;
    $axisy = '' ;
    $dataunit_arr = array() ;
    $dataunit_str = ' ' ;
    // Go through each different dataunit and build a scale
    // First get all the different MET sensors from this equipement ordered!
    $query3 = "SELECT sensor.id AS sensor_id, sensor.name AS sensor_name
              , sensor.dataunit AS sensor_dataunit
              FROM sensor
              WHERE equipement = " . 86 
              . " AND dataunit " . $sign . "= " . "'PPM'"
              . " ORDER BY sensor.id ASC" ;
    $result3 = mysqli_query($dbc, $query3) or trigger_error("Query: $query3\n<br>MySQL Error: " . mysqli_error($dbc));
    while ($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC))
    {
      // register the dataunit
      if (substr_count($dataunit_str, $row3['sensor_dataunit']) == 0)
      {
        $dataunit_arr[$naxis] = $row3['sensor_dataunit'] ;
        $sensor_arr[$nsensor] = $row3['sensor_dataunit'] ;
        $dataunit_str .= $row3['sensor_dataunit'] ;
        
        // If more than 4 build scales on the other side Y2
        if ($naxis <=3)
        {
          if ($naxis == 0)
            $axisy .= '		axisY:[';
          $axisy .= '{
            title: "' . $row3['sensor_dataunit'] . '",
            lineColor: "#000000",
            titleFontColor: "#000000",
            labelFontColor: "#000000"
          }' ;
          if ($naxis == 3)
            $axisy .= '],' ;
          else
            $axisy .= ',' ;
            
        }
        else
        {
          if ($naxis == 4)
            $axisy .= '
          axisY2:[';
          $axisy .= '{
            title: "' . $row3['sensor_dataunit'] . '",
            lineColor: "#000000",
            titleFontColor: "#000000",
            labelFontColor: "#000000"
          }' ;
          if ($naxis == 7)
            $axisy .= '],' ;
          else
            $axisy .= ',' ;
        }
        $naxis++;
      }
      else
        $sensor_arr[$nsensor] = $row3['sensor_dataunit'] ;
      $nsensor++;
    }
  }
  else
  {
    $sign = '' ;
    
    $axisy = '		axisY:[{
			title: "PPM",
			lineColor: "#000000",
			titleFontColor: "#000000",
			labelFontColor: "#000000"
		}],' ;
  }
  
  // First get all the different sensors from this equipement ordered!
	$query = "SELECT sensor.id AS sensor_id, sensor.name AS sensor_name
            , sensor.dataunit AS sensor_dataunit
            FROM sensor
            WHERE equipement = " . 86 
            . " AND dataunit " . $sign . "= " . "'PPM'"
            . " ORDER BY sensor.id ASC" ;
	$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
	if (@mysqli_num_rows($result) != 0) 
  {
    // Construct the chart container for each sensors
    $final_sensor_chart_js = "  
    chart[$chart_number] = new CanvasJS.Chart(\"chartContainer$chart_number\",
    {
      title:{
        text: \"SL041803\"
      },
      $axisy
      data: [
      " ;
    
    // Construct the plot series for the Chart to be displayed
    $final_sensor_series_js = " if (chart_number == $chart_number)
      {
    " ;
    $plot_series = 0;
    //$nmet = 0 ;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
    {
      // Date Interval (2 week ago) - Always put the timestamp in milliseconds
      $sql_date_interval = strtotime("-1 week") ;
      
      $sensor_id = $row['sensor_id'] ;
      $sensor_name = $row['sensor_name'] ;
      // Now get all the sample value for the sensors and build the CanvasJS data
      // Get the values and timestamp to build the CanvasJS data
      $query2 = "SELECT sample.id AS sample_id, sample.value, sample.sampledat, sensor.name
                FROM sample
                INNER JOIN sensor ON sample.sensor = sensor.id
                WHERE equipement = " . 86 
                . " AND sample.sensor = " . $sensor_id
                . " AND sampledat >= " . $sql_date_interval
                . " ORDER BY sampledat ASC" ;
      $result2 = mysqli_query($dbc, $query2) or trigger_error("Query: $query2\n<br>MySQL Error: " . mysqli_error($dbc));
      if (@mysqli_num_rows($result2) != 0) 
      {
        if ($met)
        {
          $nyindex = $sensor_arr[] ;
          if ($nmet < 4)
            $nyindex = $nmet ;
          else
            $nyindex = $nmet - 4 ;
          
          $final_sensor_chart_js .= "     {        
          type: \"spline\",
          name: \"$sensor_name\",
          showInLegend: true,
          axisYIndex: " . $nyindex . ",
          lineThickness: 1,
          xValueType: \"dateTime\",
          dataPoints: [" ;
          $nmet++ ;
        }
        else
        {
          $final_sensor_chart_js .= "     {        
          type: \"spline\",
          name: \"$sensor_name\",
          showInLegend: true,
          lineThickness: 1,
          xValueType: \"dateTime\",
          dataPoints: [" ;
        }
        
        // Construct the plot series for the Chart to be displayed
        if ($plot_series > 0)
          $final_sensor_series_js .= " else" ;
        $final_sensor_series_js .= " if (series_to_plot == $plot_series)
        {" ;
        $plot_series++ ;
        while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC))
        {
          $milli_timestamp = $row2['sampledat'] * 1000 ;
          // Add each data samples to the CanvasJS files
          $final_sensor_chart_js .= '{ x: ' . $milli_timestamp . ', y: ' . $row2['value'] . ' },';
          $final_sensor_series_js .= 'dataPoints.push({ x: ' . $milli_timestamp . ', y: ' . $row2['value'] . ' });' ;
          
        }
        
        $final_sensor_chart_js .= " 
        ]
      }," ;
        
        $final_sensor_series_js .= " 
        }" ;
        
      }
      
      
    }
    
    $final_sensor_chart_js .= "
    ]
  });
  
  chart[$chart_number].render();" ;
    
    $final_sensor_series_js .= " 
    }" ;
    
  }

  return array($final_sensor_chart_js, $final_sensor_series_js) ;
  
} // buildSensorDataCanvasJS

?>