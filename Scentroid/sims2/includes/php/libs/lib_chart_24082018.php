<?php

// Main libraries for Chart features

// Builds and returns the Top Input Fields (Dates, Sensors Update buttons etc...)
function buildTopInputFields()
{
  
  /*
  $final_top_input = "
                  <label>Scentinal:</label>
                  <select class=\"input_select\">
                  <option value=\"87\">SL041806 -:- Scentroid</option>
                  <option value=\"86\">SL041803 -:- SI Analytics</option>
                  <option value=\"85\">SL041802 -:- SI Analytics</option>
                  <option value=\"84\">Scentinal-SL031804 -:- Vietan Environment Technology</option>
                  <option value=\"83\">Midstream SL021803 -:- C&M Consulting Engineer</option>
                  <option value=\"82\">Midstream SL021802 -:- C&M Consulting Engineer</option>
                  <option value=\"81\">Scentinal-SL041801 -:- LA Sanitation</option>
                  <option value=\"80\">Scentinal-SL031803 -:- Beijing Leshi Alliance Technology</option>
                  <option value=\"79\">Scentinal-SL031802 -:- Beijing Leshi Alliance Technology</option>
                  <option value=\"78\">Scentinal-SL031801 -:- Beijing Leshi Alliance Technology</option>
                  </select> 
                  <label>Begin Date:</label><input type=\"text\" id=\"begin_date\" class=\"input_date\" name=\"begin_date\" value=\"\" />  
                  <label>End Date:</label><input type=\"text\" id=\"end_date\" class=\"input_date\" name=\"end_date\" value=\"\" />
                  <button class=\"button_update\">Update</button>" ;
  */
  
  $final_top_input = "" ;
  
  // Try the connection to MySQL  + Select the relevant database
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
  else
  {
    $final_top_input .= "Nothing!" ;
  
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
  $final_met_chart = "  <div id=\"chartContainer" . $chart_number . "\" style=\"height: 300px; width: 45%; float:right;\">
  </div>" ;

  return $final_met_chart ;
} // buildMetChart

// Builds and returns Charts for Meteorological Table
function buildMetTable()
{

  // Add the frozen table to be replaced asap
  $final_met_table = "
<table class=\"scroll met_data\">
<thead>
<tr>
<th>Parameter</th>
<th>Value</th>
<th>Updated</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<tr>
<td>Internal Temperature</td>
<td>31.57 C</td>
<td>1 Hour ago</td>
<td><span class=\"option selectable remove\" chart=\"2\" plot_number=\"0\" series=\"0\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>
    <span class=\"option selectable add\" chart=\"2\" plot_number=\"0\" series=\"0\">PLOT</span></td>
</tr>

<tr>
<td>Internal Humidity</td>
<td>33.42%</td>
<td>4 minutes ago</td>
<td><span class=\"option selectable remove\" chart=\"2\" plot_number=\"1\" series=\"1\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>
    <span class=\"option selectable add\" chart=\"2\" plot_number=\"1\" series=\"1\">PLOT</span></td>
</tr>

<tr>
<td>External Temperature</td>
<td>23.50 C</td>
<td>4 minutes ago</td>
<td><span class=\"option selectable remove\" chart=\"2\" plot_number=\"2\" series=\"2\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>
    <span class=\"option selectable add\" chart=\"2\" plot_number=\"2\" series=\"2\">PLOT</span></td>
</tr>

<tr>
<td>Wind Speed</td>
<td>0.047 m/s</td>
<td>4 minutes ago</td>
<td><span class=\"option selectable remove\" chart=\"2\" plot_number=\"3\" series=\"3\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>
    <span class=\"option selectable add\" chart=\"2\" plot_number=\"3\" series=\"3\">PLOT</span></td>
</tr>


</tbody>

</table>" ;

/*

<tr>
<td>Wind Direction</td>
<td>87.000 Degrees</td>
<td>1 Hour ago</td>
<td><span class=\"option selectable remove\" chart=\"2\" plot_number=\"4\" series=\"4\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>
    <span class=\"option selectable add\" chart=\"2\" plot_number=\"4\" series=\"4\">PLOT</span></td>
</tr>

<tr>
<td>Daily Rain</td>
<td>1897.200 mm</td>
<td>4 minutes ago</td>
<td><span class=\"option selectable remove\" chart=\"2\" plot_number=\"5\" series=\"5\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>
    <span class=\"option selectable add\" chart=\"2\" plot_number=\"5\" series=\"5\">PLOT</span></td>
</tr>

<tr>
<td>Solar Radiation</td>
<td>0.000 W/m2</td>
<td>1 Hour ago</td>
<td><span class=\"option selectable remove\" chart=\"2\" plot_number=\"6\" series=\"6\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>
    <span class=\"option selectable add\" chart=\"2\" plot_number=\"6\" series=\"6\">PLOT</span></td>
</tr>

<tr>
<td>UV Sensor</td>
<td>0.000 UV Index</td>
<td>1 Hour ago</td>
<td><span class=\"option selectable remove\" chart=\"2\" plot_number=\"7\" series=\"7\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>
    <span class=\"option selectable add\" chart=\"2\" plot_number=\"7\" series=\"7\">PLOT</span></td>
</tr>

<tr>
<td>Barometric Pressure</td>
<td>98.340 kPA</td>
<td>4 minutes ago</td>
<td><span class=\"option selectable remove\" chart=\"2\" plot_number=\"8\" series=\"8\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>
    <span class=\"option selectable add\" chart=\"2\" plot_number=\"8\" series=\"8\">PLOT</span></td>
</tr>

<tr>
<td>PM2.5</td>
<td>16.750 ug/m3</td>
<td>4 minutes ago</td>
<td><span class=\"option selectable remove\" chart=\"2\" plot_number=\"9\" series=\"9\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>
    <span class=\"option selectable add\" chart=\"2\" plot_number=\"9\" series=\"9\">PLOT</span></td>
</tr>

<tr>
<td>PM10</td>
<td>19.750 ug/m3</td>
<td>4 minutes ago</td>
<td><span class=\"option selectable remove\" chart=\"2\" plot_number=\"10\" series=\"10\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>
    <span class=\"option selectable add\" chart=\"2\" plot_number=\"10\" series=\"10\">PLOT</span></td>
</tr>

</tbody>

</table>" ;

*/

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
function buildSensorTable()
{

  // Add the frozen table to be replaced asap
  $final_sensor_table = "
<table class=\"scroll sensor_data\">
<thead>
<tr>
<th>Sensors</th>
<th>Concentration</th>
<th>Updated</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<tr>
<td>Ozone - LC</td>
<td>658.228 ppm</td>
<td>1 Hour ago</td>
<td><span class=\"option selectable remove\" chart=\"1\" plot_number=\"0\" series=\"0\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>
    <span class=\"option selectable add\" chart=\"1\" plot_number=\"0\" series=\"0\">PLOT</span></td>
</tr>

<tr>
<td>Nitrogen Dioxide - LC</td>
<td>609.082 ppm</td>
<td>4 minutes ago</td>
<td><span class=\"option selectable remove\" chart=\"1\" plot_number=\"1\" series=\"1\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>
    <span class=\"option selectable add\" chart=\"1\" plot_number=\"1\" series=\"1\">PLOT</span></td>
</tr>

<tr>
<td>Carbon Monoxide - LC</td>
<td>650.171 ppm</td>
<td>4 minutes ago</td>
<td><span class=\"option selectable remove\" chart=\"1\" plot_number=\"2\" series=\"2\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>
    <span class=\"option selectable add\" chart=\"1\" plot_number=\"2\" series=\"2\">PLOT</span></td>
</tr>

<tr>
<td>Sulfur Dioxide - LC</td>
<td>617.944 ppm</td>
<td>4 minutes ago</td>
<td><span class=\"option selectable remove\" chart=\"1\" plot_number=\"3\" series=\"3\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>
    <span class=\"option selectable add\" chart=\"1\" plot_number=\"3\" series=\"3\">PLOT</span></td>
</tr>

</tbody>

</table>" ;

/*

<tr>
<td>PID - LC</td>
<td>68.481 ppm</td>
<td>1 Hour ago</td>
<td><span class=\"option selectable remove\" chart=\"1\" plot_number=\"4\" series=\"4\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>
    <span class=\"option selectable add\" chart=\"1\" plot_number=\"4\" series=\"4\">PLOT</span></td>
</tr>

<tr>
<td>NMHC</td>
<td>0.000 ppm</td>
<td>4 minutes ago</td>
<td><span class=\"option selectable remove\" chart=\"1\" plot_number=\"5\" series=\"5\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>
    <span class=\"option selectable add\" chart=\"1\" plot_number=\"5\" series=\"5\">PLOT</span></td>
</tr>

<tr>
<td>Carbon Dioxide - LC</td>
<td>973.242 ppm</td>
<td>1 Hour ago</td>
<td><span class=\"option selectable remove\" chart=\"1\" plot_number=\"6\" series=\"6\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>
    <span class=\"option selectable add\" chart=\"1\" plot_number=\"6\" series=\"6\">PLOT</span></td>
</tr>

<tr>
<td>Methane</td>
<td>0.000 ppm</td>
<td>1 Hour ago</td>
<td><span class=\"option selectable remove\" chart=\"1\" plot_number=\"7\" series=\"7\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>
    <span class=\"option selectable add\" chart=\"1\" plot_number=\"7\" series=\"7\">PLOT</span></td>
</tr>

<tr>
<td>Hydrogen Sulfide</td>
<td>616.333 ppm</td>
<td>4 minutes ago</td>
<td><span class=\"option selectable remove\" chart=\"1\" plot_number=\"8\" series=\"8\">PLOT<span class=\"check_plot\"><i class=\"fa fa-check fa-2x\"></i></span></span>
    <span class=\"option selectable add\" chart=\"1\" plot_number=\"8\" series=\"8\">PLOT</span></td>
</tr>

</tbody>

</table>" ;

*/

  return $final_sensor_table ;
} // buildSensorTable


// Builds and returns JS data for 
function buildSensorChartContainerJS($chart_number)
{

  // Add the frozen table to be replaced asap
  $final_sensor_chart_js = "  
  chart[$chart_number] = new CanvasJS.Chart(\"chartContainer$chart_number\",
  {
    title:{
      text: \"Removing and adding dataSeries Dynamically\"
    },
    data: [
    {        
      type: \"spline\",
      lineThickness: 1,
      dataPoints: [
        { x: 10, y: 71 },
        { x: 20, y: 55 },
        { x: 30, y: 50 },
        { x: 40, y: 65 },
        { x: 50, y: 95 },
        { x: 60, y: 68 },
        { x: 70, y: 28 },
        { x: 80, y: 34 },
        { x: 90, y: 14 }
      ]
    },
    {        
      type: \"spline\",
      lineThickness: 1,
      dataPoints: [
        { x: 10, y: 7 },
        { x: 20, y: 5 },
        { x: 30, y: 5 },
        { x: 40, y: 16 },
        { x: 50, y: 9 },
        { x: 60, y: 24 },
        { x: 70, y: 18 },
        { x: 80, y: 14 },
        { x: 90, y: 24 }
      ]
    },
    {        
      type: \"spline\", 
      lineThickness: 1,
      dataPoints: [
        { x: 10, y: 44 },
        { x: 20, y: 25 },
        { x: 30, y: 41 },
        { x: 40, y: 6 },
        { x: 50, y: 29 },
        { x: 60, y: 54 },
        { x: 70, y: 12 },
        { x: 80, y: 74 },
        { x: 90, y: 29 }
      ]
    },
    {        
      type: \"spline\",
      lineThickness: 1,
      dataPoints: [
        { x: 10, y: 27 },
        { x: 20, y: 90 },
        { x: 30, y: 74 },
        { x: 40, y: 85 },
        { x: 50, y: 63 },
        { x: 60, y: 87 },
        { x: 70, y: 52 },
        { x: 80, y: 44 },
        { x: 90, y: 76 }
      ]
    }
    ]
  });
  
  chart[$chart_number].render();" ;

  return $final_sensor_chart_js ;
} // buildSensorChartContainerJS


// Builds and returns JS data for 
function buildSensorSeriesToPlotJS($chart_number)
{

  if ($chart_number == 1)
    $final_sensor_series_js = "" ;
  else
    $final_sensor_series_js = "else" ;

  // Construct the plot series for the Chart to be displayed
  $final_sensor_series_js .= " if (chart_number == $chart_number)
    {
      if (series_to_plot == 0)
      {
        dataPoints.push({ x: 10, y: 71 });
        dataPoints.push({ x: 20, y: 55 });
        dataPoints.push({ x: 30, y: 50 });
        dataPoints.push({ x: 40, y: 65 });
        dataPoints.push({ x: 50, y: 95 });
        dataPoints.push({ x: 60, y: 68 });
        dataPoints.push({ x: 70, y: 28 });
        dataPoints.push({ x: 80, y: 34 });
        dataPoints.push({ x: 90, y: 14 });
      }
      else if (series_to_plot == 1)
      {
        dataPoints.push({ x: 10, y: 7 });
        dataPoints.push({ x: 20, y: 5 });
        dataPoints.push({ x: 30, y: 5 });
        dataPoints.push({ x: 40, y: 16 });
        dataPoints.push({ x: 50, y: 9 });
        dataPoints.push({ x: 60, y: 24 });
        dataPoints.push({ x: 70, y: 18 });
        dataPoints.push({ x: 80, y: 14 });
        dataPoints.push({ x: 90, y: 24 });
      }
      else if (series_to_plot == 2)
      {
        dataPoints.push({ x: 10, y: 44 });
        dataPoints.push({ x: 20, y: 25 });
        dataPoints.push({ x: 30, y: 41 });
        dataPoints.push({ x: 40, y: 6 });
        dataPoints.push({ x: 50, y: 29 });
        dataPoints.push({ x: 60, y: 54 });
        dataPoints.push({ x: 70, y: 12 });
        dataPoints.push({ x: 80, y: 74 });
        dataPoints.push({ x: 90, y: 29 });
      }
      else if (series_to_plot == 3)
      {
        dataPoints.push({ x: 10, y: 27 });
        dataPoints.push({ x: 20, y: 90 });
        dataPoints.push({ x: 30, y: 74 });
        dataPoints.push({ x: 40, y: 85 });
        dataPoints.push({ x: 50, y: 63 });
        dataPoints.push({ x: 60, y: 87 });
        dataPoints.push({ x: 70, y: 52 });
        dataPoints.push({ x: 80, y: 44 });
        dataPoints.push({ x: 90, y: 76 });
      }
    }" ;

  return $final_sensor_series_js ;
} // buildSensorSeriesToPlotJS

// Builds and returns JS data for 
function buildMetChartContainerJS($chart_number)
{

  // Add the frozen table to be replaced asap
  $final_met_chart_js = "        
  chart[$chart_number] = new CanvasJS.Chart(\"chartContainer$chart_number\",
  {
    title:{
      text: \"Removing and adding dataSeries Dynamically\"
    },
    data: [
    {        
      type: \"spline\",
      lineThickness: 1,
      dataPoints: [
        { x: 10, y: 90 },
        { x: 20, y: 80 },
        { x: 30, y: 70 },
        { x: 40, y: 60 },
        { x: 50, y: 50 },
        { x: 60, y: 40 },
        { x: 70, y: 30 },
        { x: 80, y: 20 },
        { x: 90, y: 10 }
      ]
    },
    {        
      type: \"spline\",
      lineThickness: 1,
      dataPoints: [
        { x: 10, y: 10 },
        { x: 20, y: 20 },
        { x: 30, y: 30 },
        { x: 40, y: 40 },
        { x: 50, y: 50 },
        { x: 60, y: 60 },
        { x: 70, y: 70 },
        { x: 80, y: 80 },
        { x: 90, y: 90 }
      ]
    },
    {        
      type: \"spline\",
      lineThickness: 1,
      dataPoints: [
        { x: 10, y: 25 },
        { x: 20, y: 30 },
        { x: 30, y: 35 },
        { x: 40, y: 40 },
        { x: 50, y: 45 },
        { x: 60, y: 40 },
        { x: 70, y: 35 },
        { x: 80, y: 30 },
        { x: 90, y: 25 }
      ]
    },
    {        
      type: \"spline\",
      lineThickness: 1,
      dataPoints: [
        { x: 10, y: 60 },
        { x: 20, y: 60 },
        { x: 30, y: 60 },
        { x: 40, y: 60 },
        { x: 50, y: 60 },
        { x: 60, y: 60 },
        { x: 70, y: 60 },
        { x: 80, y: 60 },
        { x: 90, y: 60 }
      ]
    }
    ]
  });

  chart[$chart_number].render();" ;

  return $final_met_chart_js ;
} // buildMetChartContainerJS


// Builds and returns JS data for 
function buildMetSeriesToPlotJS($chart_number)
{
  if ($chart_number == 1)
    $final_met_series_js = "";
  else
    $final_met_series_js = "else";
    
  // Construct the plot series for the Chart to be displayed
  $final_met_series_js .= " if (chart_number == $chart_number)
    {
      if (series_to_plot == 0)
      {
        dataPoints.push({ x: 10, y: 90 });
        dataPoints.push({ x: 20, y: 80 });
        dataPoints.push({ x: 30, y: 70 });
        dataPoints.push({ x: 40, y: 60 });
        dataPoints.push({ x: 50, y: 50 });
        dataPoints.push({ x: 60, y: 40 });
        dataPoints.push({ x: 70, y: 30 });
        dataPoints.push({ x: 80, y: 20 });
        dataPoints.push({ x: 90, y: 10 });
      }
      else if (series_to_plot == 1)
      {
        dataPoints.push({ x: 10, y: 10 });
        dataPoints.push({ x: 20, y: 20 });
        dataPoints.push({ x: 30, y: 30 });
        dataPoints.push({ x: 40, y: 40 });
        dataPoints.push({ x: 50, y: 50 });
        dataPoints.push({ x: 60, y: 60 });
        dataPoints.push({ x: 70, y: 70 });
        dataPoints.push({ x: 80, y: 80 });
        dataPoints.push({ x: 90, y: 90 });
      }
      else if (series_to_plot == 2)
      {
        dataPoints.push({ x: 10, y: 25 });
        dataPoints.push({ x: 20, y: 30 });
        dataPoints.push({ x: 30, y: 35 });
        dataPoints.push({ x: 40, y: 40 });
        dataPoints.push({ x: 50, y: 45 });
        dataPoints.push({ x: 60, y: 40 });
        dataPoints.push({ x: 70, y: 35 });
        dataPoints.push({ x: 80, y: 30 });
        dataPoints.push({ x: 90, y: 25 });
      }
      else if (series_to_plot == 3)
      {
        dataPoints.push({ x: 10, y: 60 });
        dataPoints.push({ x: 20, y: 60 });
        dataPoints.push({ x: 30, y: 60 });
        dataPoints.push({ x: 40, y: 60 });
        dataPoints.push({ x: 50, y: 60 });
        dataPoints.push({ x: 60, y: 60 });
        dataPoints.push({ x: 70, y: 60 });
        dataPoints.push({ x: 80, y: 60 });
        dataPoints.push({ x: 90, y: 60 });
      }
    }" ;

  return $final_met_series_js ;
} // buildMetSeriesToPlotJS

?>