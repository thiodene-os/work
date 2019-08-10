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
<script>
// Initialize and add the map
function initMap() 
{
  // The location of Equipment
  var equipment = <?php echo $equipment_geoposition_js ; ?>
  
  <?php echo $all_equipments_geoposition_js ; ?>
  // The map, centered at Equipment
  var map = new google.maps.Map(
      document.getElementById('map'), {zoom: 12, center: equipment, mapTypeId: 'satellite'});
  // The marker, positioned at Equipment
  
  <?php echo $all_equipments_marker_js ; ?>
  
  <?php echo $notice ; ?>
  
  var marker = new google.maps.Marker({position: equipment, map: map,
      label: {
        text: "AQI: <?php echo $aqi ; ?>",
        color: "<?php echo $color_code ; ?>",
        fontWeight: "bold",
        fontSize: "16px"
      },icon: {
      labelOrigin: new google.maps.Point(11,50),url: "https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_red.png"}});
  
  <?php echo $listener ; ?>
  
  <?php echo $equipment_wind_triangle_js ; ?>
  
}
</script>

<style>
@import url(/includes/css/common.css);
@import url(/includes/css/show_table.css);
@import url(/includes/css/show_map.css);                     
</style>

</head>
<body>

<button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>

<?php echo buildUserMenu() ; ?>

<div id="main_content">
<div style="background-color:white;color:black;padding:10px 70px 0px 70px; margin:0;"><a id="logo" href="http://sims2.scentroid.com" title="SIMS 2.0"><img src="/images/logo.jpg" /></a>
<div class="user_nav"><img style="vertical-align: middle" src="/images/mnu_icon_user_def.png" /><span class="user_name">Admin User(IDES2)</span>
<a class="user_logout" href="sims2.scentroid.com/logout"> <i class="fa fa-sign-in fa-2x"></i>Sign Out</a></div>
</div>
<div style="background-color:#F0F0F0;padding:10px 70px 2500px; margin:0;">