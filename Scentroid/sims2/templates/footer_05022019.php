
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
  document.getElementById('startButton').onclick = function() {
    introJs().setOption('doneLabel', 'Next page').start().oncomplete(function() {
      window.location.href = 'second.html?multipage=true';
    });
  };
</script>
<script type="text/javascript">
  function startIntro(){
    var intro = introJs();
      intro.setOptions({
        steps: [
          { 
            intro: "This is the Homepage! Global view of all Equipments on Map."
          },
          {
            element: document.querySelector('#sidebar'),
            intro: "This is the Menu Bar visible throughout the Website!."
          },
          { 
            intro: "Go over this <b>Google Map</b> and find all your <b>Equipments</b>. Click on their icon and check their status and analytics."
          }
        ]
      });
      intro.start();
  }
</script>
</body>
</html>

<?php
ob_end_flush();
?>