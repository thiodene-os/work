<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>

<script src="/includes/javascript/table_scroll.js" type="text/javascript"></script>
<script src="/includes/javascript/common.js" type="text/javascript"></script>
<script>
// Initialize and add the map
function initMap() 
{
  // The location of Equipment
  var equipment = <?php echo $equipment_geoposition_js ; ?>
  
  <?php echo $all_equipments_geoposition_js ; ?>
  // The map, centered at Equipment
  var map = new google.maps.Map(
      document.getElementById('map'), {zoom: 16, center: equipment, mapTypeId: 'satellite',mapTypeControl: true,
          mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,position: google.maps.ControlPosition.TOP_CENTER}});
  // The marker, positioned at Equipment
  
  <?php echo $all_equipments_marker_js ; ?>
  
}
</script>

<style>
@import url(/includes/css/common_ademir.css);
@import url(/includes/css/show_table_overview_ademir.css);
@import url(/includes/css/show_map_overview_ademir.css);                     
</style>

</head>
<body>

<button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>

<?php echo buildUserMenu() ; ?>
