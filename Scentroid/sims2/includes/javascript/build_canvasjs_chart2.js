window.onload = function () 
{
  var chart = new CanvasJS.Chart("chartContainer",
  {
    title:{
      text: "Removing and adding dataSeries Dynamically"
    },
    data: [
    <?php echo $ ; ?>
    ]
  });

  chart.render();
  
  // Remove one data points from the chart
  $(".remove").click(function()
  {
    // Confirm removing
    //if (! confirm("Do you really want to remove these data points?"))
    //  return false ;
    plot_number = $(this).attr("plot_number") ;
    if (plot_number == "")
      return false ;
    //alert(plot_number) ;
    chart.data[plot_number].remove() ;
    
    // Now recalculate all the plots that were above the removed one!
    // And also give no number to the one that has been removed cause it can't be removed anymore...
    $(".remove").each(function()
    {
      plot_value = $(this).attr("plot_number") ;
      //alert ("OK") ;
      
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
    }) ;
    
    this_parent = $(this).parent() ;
    this_parent.find(".remove").hide() ;
    this_parent.find(".add").css('display', 'inline-block');
  
  }) ;
  
  
  // Add these new data points to the chart
  $(".add").click(function()
  {
    var not_plotted = true ;
    var series_to_plot = 0 ;
    // Confirm adding the data points
    //if (! confirm("Do you really want to add these data points?"))
    //  return false ;
    series = $(this).attr("series") ;
    
    // Now check if the data series is already on the chart
    $(".remove").each(function()
    {
      plot_value = $(this).attr("plot_number") ;
      plot_series = $(this).attr("series") ;
      
      // Verify its not already showing
      if (plot_series == series && plot_value != "")
      {
        //alert("These datapoints are already on the Chart!") ;
        not_plotted = false ;
        return false ;
      }
      else if (plot_series == series && plot_value == "")
        series_to_plot = plot_series ;
        
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
      plot_number = $(this).attr("plot_number") ;
      if (plot_number.toString().length > 0)
      {
        plot_exists = true ;
        if (plot_number > new_plot_number)
          new_plot_number = plot_number ;
      }
    }) ;
    
    // increment the plot number for a new curve and add it to the empty remove button
    if (plot_exists)
      new_plot_number++ ;
    //alert("series_to_plot" + series_to_plot) ;
    // add new plot number to the right remove button
    $(".remove").each(function()
    {
      series = $(this).attr("series") ;
      if (series == series_to_plot)
        $(this).attr("plot_number", new_plot_number) ;
    }) ;
    
    this_parent = $(this).parent() ;
    this_parent.find(".add").hide() ;
    this_parent.find(".remove").css('display', 'inline-block') ;
    
    var type= "spline" ;
    var fillOpacity= .4;
    var dataPoints = addDataPointsToChart(series_to_plot) ;
    chart.options.data.push( {type: type, fillOpacity: fillOpacity, dataPoints: dataPoints} );
    chart.render();
  }) ;
    
  // Adds comma to non-empty string
  function addDataPointsToChart(series_to_plot)
  {
    // This function returns the Data Points to be plotted
    var dataPoints = [];
    <?php echo $ ; ?>
    return dataPoints ;
  } // addDataPointsToChart
  
}