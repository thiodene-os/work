// Initialize and add the map
function initMap() 
{
  // The location of Equipment
  var equipment = {lat: -25.344, lng: 131.036};
  // The map, centered at Equipment
  var map = new google.maps.Map(
      document.getElementById('map'), {zoom: 14, center: equipment, mapTypeId: 'satellite'});
  // The marker, positioned at Equipment
  var marker = new google.maps.Marker({position: equipment, map: map});
}