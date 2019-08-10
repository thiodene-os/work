<html>
<head>
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<script type="text/javascript">

  window.onload = function () {

    $(document).on('click','#display',function() {               
      
      //alert("OK") ;
      equipment = $(this).attr("equipment") ;
      //alert(equipment) ;
      $.ajax({    //create an ajax request to display.php
        type: "GET",
        url: "/includes/php/tests/display.php?equipment=" + equipment,
        dataType: "html",   //expect html to be returned
        success: function(response)
        {
          // Evaluates the HTML and possible CSS and script inside the DOM (If script not executed use Eval!)
          $("#responsecontainer").html(response);
          //$("#responsecontainer").find("script").each(function(){
            //eval($(this).text());
            //alert("OK") ;
          //});
          //alert(response);
        }

      });

      
    });
    
  }

</script>

<style type="text/css">
@import url(/includes/css/show_table.css);
.box                                
{
  float:left; 
  overflow: hidden; 
  background-color: rgba(255, 0, 0, 0.4);
  position:absolute ; 
  left: 800px; 
  top: 155px;  
  z-index: 99;
  width: 525px; 
  margin: 0;
  padding: 0;
}
.box_inner
{
  width: 230px;
  height: 120px;
  padding: 10px;
  background: #267fff;
  border: 1px solid #a29415;
  margin: 5px;
  float: left;
}
.box_title
{
  color:white;
  border-bottom: 1px solid white;
  width: 100%;
  height: 30px;
  font-family: tahoma;
  font-size:18px;
}
.box_content
{
  display: table;
  color:white;
  width: 100%;
  height: 100px;
  font-family: tahoma;
  font-size:48px;
}
.box_image
{
  max-height: 100px;
  max-width: 100px;
  vertical-align: middle;
  display: table-cell;
}
.box_span
{
  vertical-align: middle;
  display: table-cell;
}
.box_line
{
  width: 100%;
  height: 50px;
}
.box_content_line
{
  display: table;
  color:white;
  width: 100%;
  height: 100px;
  font-family: tahoma;
  font-size:18px;
}
.box_image_line
{
  text-align:left;
  max-height: 44px;
  max-width: 44px;
  vertical-align: middle;
  display: table-cell;
  margin: 0 35px 0 15px;
}
</style>
</head>
<body>
<h3 align="center">Manage Student Details</h3>
<table border="1" align="center">
   <tr>
       <td> <input type="button" id="display" equipment="71" value="Display 71 Data" /> </td>
       <td> <input type="button" id="display" equipment="42" value="Display 42 Data" /> </td>
   </tr>
</table>
<div id="responsecontainer" align="center">
</div>
<div id="chartContainer" style="height: 360px; width: 100%;"></div>
</body>
</html>