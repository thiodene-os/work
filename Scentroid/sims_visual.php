<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<script src="amcharts/amcharts.js"></script>
<script src="amcharts/serial.js"></script>
<script>
AmCharts.makeChart( "chartdiv", {
  "type": "serial",
  "dataProvider": chartData,
  "categoryField": "country",
  "graphs": [ {
    "valueField": "visits",
    "type": "column"
  } ]
} );
</script>
<script>
  var chartData = [ {
    "country": "USA",
    "visits": 4252
  }, {
    "country": "China",
    "visits": 1882
  }, {
    "country": "Japan",
    "visits": 1809
  }, {
    "country": "Germany",
    "visits": 1322
  }, {
    "country": "UK",
    "visits": 1122
  }, {
    "country": "France",
    "visits": 1114
  }, {
    "country": "India",
    "visits": 984
  }, {
    "country": "Spain",
    "visits": 711
  }, {
    "country": "Netherlands",
    "visits": 665
  }, {
    "country": "Russia",
    "visits": 580
  }, {
    "country": "South Korea",
    "visits": 443
  }, {
    "country": "Canada",
    "visits": 441
  }, {
    "country": "Brazil",
    "visits": 395
  }, {
    "country": "Italy",
    "visits": 386
  }, {
    "country": "Australia",
    "visits": 384
  }, {
    "country": "Taiwan",
    "visits": 338
  }, {
    "country": "Poland",
    "visits": 328
} ];
</script>
</head>

<body>
<h1>Internal Temperature</h1>
<div id="chartdiv" style="width: 640px; height: 400px;"></div>
</body>
</html>
