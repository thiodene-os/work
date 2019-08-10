</div>
</div>

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
            intro: "This is the Notifications Page! It renders the daily the activity of Sensors and Scentinals."
          },
          {
            element: document.querySelector('.active'),
            intro: "The type of Notifications can be selected from the tabs menu."
          },
          { 
            element: document.querySelector('#update_input'),
            intro: "Select the Scentinal or Enter keywords in the Search field and Update the Notifications."
          },
          { 
            element: document.querySelector('#activity'),
            intro: "The activity per Scentinals or Sensors is shown in this Table."
          }
        ]
      });
      intro.setOption('doneLabel', 'Next page').start().oncomplete(function() {
          window.location.href = '/settings/index.php?multipage=true';
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