<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');

if (isset($_GET['equipment'])) 
{
  // Get the selected company ID from GET
  $equipment = $_GET['equipment'] ;
}
else
{
  // Use one by default
  $equipment = 31 ;
}

$my_ajax_html = '<div id="box_container" class="box">
<div class="box_inner">
<div class="box_title">Temperature in &#8451;</div>
<div class="box_content_line">
  <div class="box_line">
    <span class="box_span">External
    <img class="box_image_line" src="/images/sun-symbol-trans.png" />
    26 <sup>&#8451;</sup></span>
  </div>
  <div class="box_line">
    <span class="box_span">Internal
    <img class="box_image_line" src="/images/sun-symbol-trans.png" />
    34 <sup>&#8451;</sup></span>
  </div>
</div>
</div>
<div class="box_inner">
<div class="box_title">Temperature in &#8451;</div>
<div class="box_content"><img class="box_image" src="/images/sun-symbol-trans.png" />
<span class="box_span">' . $equipment . ' <sup>&#8451;</sup></span></div>
</div>
</div>' ;

$chart_number = 1 ;
list ($sensor_table, $sensor_array, $equipment_name) = buildSensorTable($equipment, $chart_number) ;
$my_ajax_html .= $sensor_table ;
//$my_ajax_html .= displaySensorChart($chart_number) ;
// Add the Chart and table with dynamic display buttons
list ($chart_container_js, $series_to_plot_js) = getPreSavedSensorDataCanvasJS($equipment, 'current') ;

  $my_ajax_script = '<script type="text/javascript">
$(document).ready(function()
{
  // Remove one data points from the chart
  $(".remove").click(function()
  {
    // Get the chart number for updated the right one
    chart_number = $(this).attr("chart") ;
    plot_number = parseInt($(this).attr("plot_number")) ;
    
    alert("Oops!") ;
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
}) ;
</script>' ;

$my_ajax_html = $my_ajax_script . $my_ajax_html ;
//$my_ajax_html = $my_ajax_html . $my_ajax_script;
//$my_ajax_html = $my_ajax_script ;

echo $my_ajax_html ;

?>