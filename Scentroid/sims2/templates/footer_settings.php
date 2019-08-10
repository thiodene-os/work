</div>
</div>

<script src="/includes/javascript/doubletaptogo.js"></script>

<script src="/includes/javascript/open_section.js"></script>

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
            intro: "This is the Settings Page! Use it to set up Scentinals, Sensors, Users, Notifications and AQIs."
          },
          {
            element: document.querySelector('#company_section'),
            intro: "Use that section to change or update the company information."
          },
          { 
            element: document.querySelector('#user_section'),
            intro: "Use this section to add, update or delete users."
          },
          { 
            element: document.querySelector('#notification_section'),
            intro: "Use this section to set up the Notifications per sensors."
          },
          { 
            element: document.querySelector('#aqi_section'),
            intro: "Use this section to set up the AQI (Air Quality Index) per sensors."
          }
        ]
      });
      intro.setOption('doneLabel', 'Restart Tour').start().oncomplete(function() {
          window.location.href = '/index.php?multipage=true';
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