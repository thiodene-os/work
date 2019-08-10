<?php
 
 $dataPoints = array(
  array("x" => 946665000000, "y" => 3.289),
  array("x" => 978287400000, "y" => 3.830),
  array("x" => 1009823400000, "y" => 2.009),
  array("x" => 1041359400000, "y" => 2.840),
  array("x" => 1072895400000, "y" => 2.396),
  array("x" => 1104517800000, "y" => 1.613),
  array("x" => 1136053800000, "y" => 1.821),
  array("x" => 1167589800000, "y" => 2.000),
  array("x" => 1199125800000, "y" => 1.397),
  array("x" => 1230748200000, "y" => 2.506)
 );
 
?>
<!DOCTYPE HTML>
<html>
<head>
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", { 
  title: {
    text: ""
  },
  axisX: {
    interval: 1
  },
  data: [
    {
      type: "spline",
      dataPoints: [
          { y: 1 },
          { y: 4 },
          { y: 3 },
          { y: 4 }	
      ]
    }
  ]
});
chart.render();	

$("#addNewSeries").click(function () {
   
  var type= "spline";
  var fillOpacity= .4;    
  var dataPoints = [];
  for ( var i = 0; i < 4; i ++ ) {
      dataPoints.push({ y: Math.random() * 10 -5 });
  }
  chart.options.data.push( {type: type, fillOpacity: fillOpacity, dataPoints: dataPoints} );
  chart.render();
    
});

$("#removeSeries").click(function () {
  chart.data[0].remove();
});
 
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<button id="addNewSeries">Add New Series</button> 
<button id="removeSeries">Remove Series</button>
</body>
</html>                              

