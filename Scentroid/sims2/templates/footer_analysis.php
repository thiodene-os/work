</div>
</div>

<script async defer
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBT1JHzCuB3na6CoYxlw8kNwwCclzkAEBc&callback=initMap">
</script>

<script src="/includes/javascript/doubletaptogo.js"></script>

<script>
	$( function()
	{
		$( '#sidebar li:has(ul)' ).doubleTapToGo();
	});
</script>
<script src="/includes/javascript/back_to_top.js"></script>
<script type="text/javascript" src="https://introjs.com/intro.js"></script>
<script type="text/javascript">
  function startIntro(){
    var intro = introJs();
      intro.setOptions({
        steps: [
          { 
            intro: "This is the Analysis Page! Check the sensors measurements per Scentinal."
          },
          {
            element: document.querySelector('#update_input'),
            intro: "Choose the Scentinal the Range or the Dates to visualize the data on the Chart."
          },
          {
            element: document.querySelector('#chartContainer1'),
            intro: "This Chart contains all the sensor analytics! For Meteorological data and Gas sensors"
          },
          {
            element: document.querySelector('#map'),
            intro: "The geographical position of the Scentinals can be seen on the <b>Google Map</b>!"
          },
          {
            element: document.querySelector('#gas'),
            intro: "Click on the <b>PLOT</b> buttons to view the sensor data on the Chart."
          }
        ]
      });
      intro.setOption('doneLabel', 'Next page').start().oncomplete(function() {
          window.location.href = '/notifications/index.php?multipage=true';
      });
  }
  if (RegExp('multipage', 'gi').test(window.location.search)) 
  {
    startIntro();
  }
</script>
</body>
</html>

<?php
ob_end_flush();
?>