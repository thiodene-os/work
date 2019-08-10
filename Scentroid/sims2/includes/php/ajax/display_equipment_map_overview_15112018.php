<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
// This renders the HTML part on the map for the equipment 
// Everything related to MET data
if (isset($_GET['equipment'])) 
{
  // Get the selected company ID from GET
  $equipment = $_GET['equipment'] ;

  $my_ajax_html = '<div id="tab_close">
  <span class="close_popup"><img src="/images/tab_close_white15.png" /></span>
  </div>' ;
  
  // Build the Met Data box for popup window
  $my_ajax_html .= buildMetDiv($equipment) ;
  
  $chart_number = 1 ;
  list ($sensor_table, $sensor_array, $equipment_name) = buildSensorTable($equipment, $chart_number) ;
  $my_ajax_html .= $sensor_table ;
  //$my_ajax_html .= displaySensorChart($chart_number) ;
  list ($chart_container_js, $series_to_plot_js) = getPreSavedSensorDataCanvasJS($equipment, 'current') ;
  
  $my_ajax_script = '<script type="text/javascript">
$(document).ready(function() {

  var chart = [] ;
  
  ' . $chart_container_js . '
  
  // Remove one data points from the chart
  $(".remove").click(function()
  {
    // Get the chart number for updated the right one
    chart_number = $(this).attr("chart") ;
    plot_number = parseInt($(this).attr("plot_number")) ;
    
    //alert(plot_number) ;
    chart[chart_number].data[plot_number].remove() ;
    
    // Now recalculate all the plots that were above the removed one!
    // And also give no number to the one that has been removed cause it can\'t be removed anymore...
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
    this_parent.find(".add").css(\'display\', \'inline-block\');
    
  }) ;
  
  
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
    this_parent.find(".remove").css(\'display\', \'inline-block\') ;
    
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
    
    ' . $series_to_plot_js . '
    
    return dataPoints ;
  } // addDataPointsToChart

  $(".close_popup").click(function()
  {
    $("#box_container").removeClass(\'shown\');
    $(".box").animate({
      width: "toggle"
    });
  });

});
</script>' ;
  
  
  // Add the Chart and table with dynamic display buttons
  //$my_ajax_html = $my_ajax_script . $my_ajax_html ;
}
else
{
  // If no equipment ID HTML has to be empty
  $my_ajax_html = '' ;
  $my_ajax_script = '' ;
}

$my_ajax_html = $my_ajax_script . $my_ajax_html ;

echo $my_ajax_html ;

?>