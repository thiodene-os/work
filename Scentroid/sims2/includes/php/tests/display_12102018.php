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
$my_ajax_html .= displaySensorChart($chart_number) ;
// Add the Chart and table with dynamic display buttons

echo $my_ajax_html ;

?>