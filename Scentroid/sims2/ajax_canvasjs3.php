<!DOCTYPE HTML>
<html>
<head>  
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){

  var dataPointsA = []
  var dataPointsB = []

  $.ajax({
    type: 'GET',
    url: 'https://api.myjson.com/bins/1igag',
    dataType: 'json',
    success: function(field) {
      for (var i = 0; i < field.length; i++) {
        dataPointsA.push({
          x: field[i].time,
          y: field[i].xxx
        });
        dataPointsB.push({
          x: field[i].time,
          y: field[i].yyy
        });
      }


      var chart = new CanvasJS.Chart("chartContainer", {
        title: {
          text: "JSON from External File"
        },

        data: [{
          type: "line",
          name: "line1",
          dataPoints: dataPointsA
        }, {
          type: "line",
          name: "line2",
          dataPoints: dataPointsB
        }, ]
      });

      chart.render();

    }




});

    });
</script>

</head>
<body>
    <div id="chartContainer" style="height: 300px; width: 100%;">
    </div>
</body>
</html>