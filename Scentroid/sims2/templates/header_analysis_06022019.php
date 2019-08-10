<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="/includes/javascript/date_picker.js" type="text/javascript"></script>
<script src="/includes/javascript/table_scroll.js" type="text/javascript"></script>
<script src="/includes/javascript/update_button.js" type="text/javascript"></script>

<!-- styles -->
<link href="https://introjs.com/example/assets/css/demo.css" rel="stylesheet">

<!-- Add IntroJs styles -->
<link href="https://introjs.com/introjs.css" rel="stylesheet">
<link href="https://introjs.com/example/assets/css/bootstrap-responsive.min.css" rel="stylesheet">

<script>
// Initialize and add the map
function initMap() 
{
  // The location of Equipment
  var equipment = <?php echo $equipment_geoposition_js ; ?>;
  // The map, centered at Equipment
  var map = new google.maps.Map(
      document.getElementById('map'), {zoom: 15, center: equipment, mapTypeId: 'satellite'});
  // The marker, positioned at Equipment
  
  <?php echo $notice ; ?>
  
  var marker = new google.maps.Marker({position: equipment, map: map,
      label: {
        text: "AQI: <?php echo $aqi ; ?>",
        color: "#FFFFFF",
        fontWeight: "bold",
        fontSize: "16px"
      },icon: {
      labelOrigin: new google.maps.Point(11,50),url: "https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_red.png"}});
  
  <?php echo $listener ; ?>
  
  <?php echo $equipment_wind_triangle_js ; ?>
  
}
</script>

<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script type="text/javascript">
window.onload = function () 
{
  var chart = [] ;
  
  <?php echo $chart_container_js ; ?>
  
  // Remove one data points from the chart
  $(".remove").click(function()
  {
    // Get the chart number for updated the right one
    chart_number = $(this).attr("chart") ;
    plot_number = parseInt($(this).attr("plot_number")) ;
    
    //alert(plot_number) ;
    chart[chart_number].data[plot_number].remove() ;
    
    // Now recalculate all the plots that were above the removed one!
    // And also give no number to the one that has been removed cause it can't be removed anymore...
    $(".remove").each(function()
    {
      length_plot_value = $(this).attr("plot_number").length ;
      if (length_plot_value > 0)
        plot_value = parseInt($(this).attr("plot_number")) ;
      else
        plot_value = false ;
      this_chart_number = $(this).attr("chart") ;
      //alert ("OK") ;
      
      if (this_chart_number == chart_number)
      {
        if (plot_value > plot_number)
        {
          //alert("case 1") ;
          $(this).attr("plot_number", plot_value - 1) ;
        }
        else if (plot_value == plot_number)
        {
          //alert("case 2") ;
          $(this).attr("plot_number", "") ;
        }
      }
    }) ;
    
    this_parent = $(this).parent() ;
    this_parent.find(".remove").hide() ;
    this_parent.find(".add").css('display', 'inline-block');
    
  }) ;
  $(this).find(".whatever") ;
  
  // Add these new data points to the chart
  $(".add").click(function()
  {
    var not_plotted = true ;
    var series_to_plot = 0 ;
    
    // Get the Chart number for altering the right one
    chart_number = $(this).attr("chart") ;
  
    series = $(this).attr("series") ;
    sensor_name = $(this).attr("sensor_name") ;
    axisyindex = $(this).attr("axisyindex") ;
    axisytype = $(this).attr("axisytype") ;
    
    // Now check if the data series is already on the chart
    $(".remove").each(function()
    {
      plot_value = $(this).attr("plot_number") ;
      plot_series = $(this).attr("series") ;
      this_chart_number = $(this).attr("chart") ;
      
      if (this_chart_number == chart_number)
      {
        // Verify its not already showing
        if (plot_series == series && plot_value != "")
        {
          //alert("These datapoints are already on the Chart!") ;
          not_plotted = false ;
          return false ;
        }
        else if (plot_series == series && plot_value == "")
          series_to_plot = plot_series ;
      }
        
    }) ;
    
    // If already plotted stop everything
    if (!not_plotted)
      return false ;
    // Now display the new datapoints after the very curve and update its remove plot_number
    // Check the highest number of actually plotted data points
    var new_plot_number = 0 ;
    var plot_exists = false ;
    $(".remove").each(function()
    {
      this_chart_number = $(this).attr("chart") ;
      if (this_chart_number == chart_number)
      {
        plot_number = $(this).attr("plot_number") ;
        if (plot_number.toString().length > 0)
        {
          plot_exists = true ;
          if (plot_number > new_plot_number)
            new_plot_number = plot_number ;
        }
      }
    }) ;
    
    // increment the plot number for a new curve and add it to the empty remove button
    if (plot_exists)
      new_plot_number++ ;
    //alert("series_to_plot" + series_to_plot) ;
    // add new plot number to the right remove button
    $(".remove").each(function()
    {
      this_chart_number = $(this).attr("chart") ;
      if (this_chart_number == chart_number)
      {
        series = $(this).attr("series") ;
        if (series == series_to_plot)
          $(this).attr("plot_number", new_plot_number) ;
      }
    }) ;
    
    this_parent = $(this).parent() ;
    this_parent.find(".add").hide() ;
    this_parent.find(".remove").css('display', 'inline-block') ;
    
    var type= "spline" ;
    var lineThickness = 1;
    var xValueType = "dateTime";
    var fillOpacity= .4;
    var dataPoints = addDataPointsToChart(chart_number, series_to_plot) ;
    
    // Display the result on the correct chart!
    chart[chart_number].options.data.push( {type: type, fillOpacity: fillOpacity, lineThickness: lineThickness, name: sensor_name, showInLegend: true, axisYIndex: axisyindex, axisYType: axisytype, xValueType: xValueType, dataPoints: dataPoints} );
    chart[chart_number].render();
  }) ;
  
  // Adds comma to non-empty string
  function addDataPointsToChart(chart_number, series_to_plot)
  {
    // This function returns the Data Points to be plotted
    var dataPoints = [];
    
    <?php echo $series_to_plot_js ; ?>
    
    return dataPoints ;
  } // addDataPointsToChart
  
}
</script>

<script>
$( function() 
{
  <?php echo $sensor_avg_array_js ; ?>
  
  $("#toggle_avg").change(function()
  {
    //alert("OK") ;
    // Replace the Table values by the ones that correspond to the SELECT
    var average = parseInt($(this).val()) ;
    //alert (average) ;
    var iter = 0 ;
    
    var value_pos = 0 ;
    var aqi_pos = 0 ;
    
    // Choose the position where to get the appropriate data
    switch (average) 
    {
    case 0:
        value_pos = 2; aqi_pos = 6 ;
        break;
    case 1:
        value_pos = 3; aqi_pos = 7 ;
        break;
    case 8:
        value_pos = 4; aqi_pos = 8 ;
        break;
    case 24:
        value_pos = 5; aqi_pos = 9 ;
        break;
    default:
        value_pos = 2; aqi_pos = 6 ;
        break;
    } ;
    
    //alert (average + ":" + value_pos + ":" + aqi_pos) ;
    //return false ;
    
    $("#gas tr").each(function()
    {
      // Now check if this TR has a chemical attributes
      chemical = $(this).attr('chemical') ;
      if(chemical)
      {
        //alert(chemical) ;
        //$(this).find(".avg").val(sensor_data[iter][2]) ;
        $(this).find(".avg").html(sensor_data[iter][value_pos].toFixed(3) + ' ' + sensor_data[iter][1]);
        $(this).find(".aqi").html(sensor_data[iter][aqi_pos]);
        //alert(sensor_data[iter][2]) ;
        //alert(iter) ;
        //alert(sensor_data[iter][aqi_pos]) ;
        iter += 1 ;
      }
      
    }) ;
    
  }) ;
}) ;
</script>

<style>
@import url(/includes/css/common.css);
@import url(/includes/css/show_table_analysis.css);
@import url(/includes/css/show_map_analysis.css);                     
</style>

</head>
<body>

<button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>

<?php echo buildUserMenu() ; ?>

<div id="main_content">
<div style="background-color:#F0F0F0;padding: 0px 10px 900px 70px; margin:0;">