
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
</body>
</html>

<?php
ob_end_flush();
?>