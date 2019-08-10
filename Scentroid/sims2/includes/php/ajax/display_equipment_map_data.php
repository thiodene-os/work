<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
// This renders the HTML part on the map for the equipment
// Everything related to MET data

if (isset($_GET['equipment'])) {
    // Get the selected company ID from GET
    $equipment = $_GET['equipment'] ;

    $chart_number = 1 ;
    list ($sensor_table, $sensor_array, $equipment_name) = buildSensorTable($equipment, $chart_number) ;
    $table_data = getEquipmentDataAverages($equipment);

    $main_title = '
    <div id = "main_title_container_background">
        <div id = "main_title_container">
            ' . $equipment_name . '
            <span id="toggle"><img id="main_title_close_button" src="../../../images/tab_close_white15.png" /></span>
            <script>
                var $box = document.getElementById(\'box\');
                var $toggle = document.getElementById(\'toggle\');
        
                $toggle.addEventListener(\'click\', function() {
                    $box.setAttribute(\'class\', \'slide-in\');
                });
            </script>
        </div>
    </div>';

    $my_ajax_html = $main_title;

    $meteo_raw_data = buildMetDiv($equipment, true);

    $meteo_table = '
    <div id="meteo_container">
        <div id="meteo_body">';

    $meteo_data = array();

    for ($i = 1; $i < sizeof($meteo_raw_data); $i++) {
        $name_image_array = parse_meteo_data($meteo_raw_data[$i][0]);
        array_push($meteo_data, array("../../../images/" . $name_image_array[1], $name_image_array[0], $meteo_raw_data[$i][1] . $meteo_raw_data[$i][2]));
    }

    foreach ($meteo_data as $meteo_item) {
        $meteo_table .= '
            <div id="meteo_body_element">
                <div id="meteo_body_element_icon"><img src="' . $meteo_item[0] . '" /></div>
                <div id="meteo_body_element_parameter">' . $meteo_item[1] . '</div>
                <div id="meteo_body_element_value">' . $meteo_item[2] . '</div>
            </div>';
    }

    $meteo_table .= '
        </div>
    </div>';

    $my_ajax_html .= $meteo_table;

    $table = '        
            <div id="table_container">
                <div id="table_header_container">';

    $content = $table_data;

    for ($x = 0; $x < sizeof($content); $x++) {
        $string_builder = '';
        if ($x == 0) {
            $string_builder = '
                    <div id="table_header">
                        <div>' . $content[$x][0] . '</div>
                        <div>' . $content[$x][1] . '</div>
                        <div>' . $content[$x][2] . '</div>
                        <div>' . $content[$x][3] . '</div>
                        <div>' . $content[$x][4] . '</div>
                        <div>' . $content[$x][5] . '</div>
                    </div>
                </div>
                <div id="table_underline"></div>
                <div id="table_body_container">';
        }
        else {
            $string_builder = '
                    <div class="table_body" id="table_parameter_' . $x . '" graph_id="' . $x .'" isShown="true" onclick="test(' . $x . ')">
                        <div>' . $content[$x][0] . '</div>
                        <div>' . $content[$x][1] . '</div>
                        <div>' . $content[$x][2] . '</div>
                        <div>' . $content[$x][3] . '</div>
                        <div>' . $content[$x][4] . '</div>
                        <div>' . $content[$x][5] . '</div>
                    </div>';
        }
        $table .= $string_builder;
    }

    $table .= '      
                </div>
            </div>';

    $my_ajax_html .= $table;

    list ($chart_container_js, $series_to_plot_js) = getPreSavedSensorDataCanvasJS($equipment, 'current') ;

    $my_ajax_script = '<script type="text/javascript">
        function test(x) {
//            window.alert("Works" + x); 
            let table_row = document.getElementById("table_parameter_" + x);
            if (table_row.getAttribute("isShown") == "true") {
                table_row.setAttribute("isShown", "false");            
                table_row.style.borderLeft = "5px solid white";
            } else {
                table_row.setAttribute("isShown", "true");            
                table_row.style.borderLeft = "0px solid white";
            }
            
//            c[2].textContent = "Hello community";
//            c[2].style.backgroundColor = "white";
        }
        
        $(document).ready(function() {
        
          var chart = [];
          
          ' . $chart_container_js . '
          
          // Remove one data points from the chart
          $(".remove").click(function() {
            // Get the chart number for updated the right one
            chart_number = $(this).attr("chart") ;
            plot_number = parseInt($(this).attr("plot_number")) ;
            
            //alert(plot_number) ;
            chart[chart_number].data[plot_number].remove() ;
            
            // Now recalculate all the plots that were above the removed one!
            // And also give no number to the one that has been removed cause it can\'t be removed anymore...
            $(".remove").each(function()
            {
              length_plot_value = $(this).attr("plot_number").length ;
              if (length_plot_value > 0)
                plot_value = parseInt($(this).attr("plot_number")) ;
              else
                plot_value = false ;
              this_chart_number = $(this).attr("chart") ;
              //alert ("OK") ;
              
              if (this_chart_number == chart_number)
              {
                if (plot_value > plot_number)
                {
                  //alert("case 1") ;
                  $(this).attr("plot_number", plot_value - 1) ;
                }
                else if (plot_value == plot_number)
                {
                  //alert("case 2") ;
                  $(this).attr("plot_number", "") ;
                }
              }
            }) ;
            
            this_parent = $(this).parent() ;
            this_parent.find(".remove").hide() ;
            this_parent.find(".add").css(\'display\', \'inline-block\');
            
          });
          
          
          // Add these new data points to the chart
          $(".add").click(function() {
            var not_plotted = true ;
            var series_to_plot = 0 ;
            
            // Get the Chart number for altering the right one
            chart_number = $(this).attr("chart") ;
          
            series = $(this).attr("series") ;
            sensor_name = $(this).attr("sensor_name") ;
            axisyindex = $(this).attr("axisyindex") ;
            axisytype = $(this).attr("axisytype") ;
            
            // Now check if the data series is already on the chart
            $(".remove").each(function()
            {
              plot_value = $(this).attr("plot_number") ;
              plot_series = $(this).attr("series") ;
              this_chart_number = $(this).attr("chart") ;
              
              if (this_chart_number == chart_number)
              {
                // Verify its not already showing
                if (plot_series == series && plot_value != "")
                {
                  //alert("These datapoints are already on the Chart!") ;
                  not_plotted = false ;
                  return false ;
                }
                else if (plot_series == series && plot_value == "")
                  series_to_plot = plot_series ;
              }
                
            }) ;
            
            // If already plotted stop everything
            if (!not_plotted)
              return false ;
            // Now display the new datapoints after the very curve and update its remove plot_number
            // Check the highest number of actually plotted data points
            var new_plot_number = 0 ;
            var plot_exists = false ;
            $(".remove").each(function()
            {
              this_chart_number = $(this).attr("chart") ;
              if (this_chart_number == chart_number)
              {
                plot_number = $(this).attr("plot_number") ;
                if (plot_number.toString().length > 0)
                {
                  plot_exists = true ;
                  if (plot_number > new_plot_number)
                    new_plot_number = plot_number ;
                }
              }
            }) ;
            
            // increment the plot number for a new curve and add it to the empty remove button
            if (plot_exists)
              new_plot_number++ ;
            //alert("series_to_plot" + series_to_plot) ;
            // add new plot number to the right remove button
            $(".remove").each(function()
            {
              this_chart_number = $(this).attr("chart") ;
              if (this_chart_number == chart_number)
              {
                series = $(this).attr("series") ;
                if (series == series_to_plot)
                  $(this).attr("plot_number", new_plot_number) ;
              }
            }) ;
            
            this_parent = $(this).parent() ;
            this_parent.find(".add").hide() ;
            this_parent.find(".remove").css(\'display\', \'inline-block\') ;
            
            var type= "spline" ;
            var lineThickness = 1;
            var xValueType = "dateTime";
            var fillOpacity= .4;
            var dataPoints = addDataPointsToChart(chart_number, series_to_plot) ;
            
            // Display the result on the correct chart!
            chart[chart_number].options.data.push( {type: type, fillOpacity: fillOpacity, lineThickness: lineThickness, name: sensor_name, showInLegend: true, axisYIndex: axisyindex, axisYType: axisytype, xValueType: xValueType, dataPoints: dataPoints} );
            chart[chart_number].render();
          });
            
          // Adds comma to non-empty string
          function addDataPointsToChart(chart_number, series_to_plot) {
            // This function returns the Data Points to be plotted
            var dataPoints = [];
            
            ' . $series_to_plot_js . '
            
            return dataPoints ;
          } // addDataPointsToChart
        
          $(".close_popup").click(function(){
            $("#box_container").removeClass(\'shown\');
            $(".box").animate({
              width: "toggle"
            });
          });
        });  
  
    </script>';


    // Add the Chart and table with dynamic display buttons
    //$my_ajax_html = $my_ajax_script . $my_ajax_html ;
}

else {
    // If no equipment ID HTML has to be empty
    $my_ajax_html = '' ;
    $my_ajax_script = '' ;
}

$my_ajax_html = $my_ajax_script . $my_ajax_html;

echo $my_ajax_html ;

//    $box_placeholder = '
//    <div id="tab_close">
//        <span class="close_popup"><img src="/images/tab_close_black15.png" /></span>
//    </div>
//    <div id="weather_box">
//      <div class="inner_box">
//        <div class="line_image"><img class="image_trans" src="/images/icon_temp_inv.png"/></div>
//        <div class="line_text">Ext. Temperature</div>
//        <div class="line_text">26 <sup>&#8451;</sup></div>
//      </div>
//      <div class="inner_box">
//        <div class="line_image"><img class="image_trans" src="/images/icon_rain_inv.png"/></div>
//        <div class="line_text">Daily Rain</div>
//        <div class="line_text">0.0 mm</div>
//      </div>
//      <div class="inner_box">
//        <div class="line_image"><img class="image_trans" src="/images/icon_hum_inv.png"/></div>
//        <div class="line_text">Ext. Humidity</div>
//        <div class="line_text">55 &#37;</div>
//      </div>
//      <div class="inner_box">
//        <div class="line_image"><img class="image_trans" src="/images/icon_temp_inv.png"/></div>
//        <div class="line_text">Int. Temperature</div>
//        <div class="line_text">34 <sup>&#8451;</sup></div>
//      </div>
//      <div class="inner_box">
//        <div class="line_image"><img class="image_trans" src="/images/icon_uv_inv.png"/></div>
//        <div class="line_text">UV Radiation</div>
//        <div class="line_text">8.19 UV</div>
//      </div>
//      <div class="inner_box">
//        <div class="line_image"><img class="image_trans" src="/images/icon_hum_inv.png"/></div>
//        <div class="line_text">Int. Humidity</div>
//        <div class="line_text">78 &#37;</div>
//      </div>
//        <div class="line_text">1406 W/m2</div>
//      <div class="inner_box">
//        <div class="line_image"><img class="image_trans" src="/images/icon_sun_inv.png"/></div>
//        <div class="line_text">Solar Radiation</div>
//      </div>
//      <div class="inner_box">
//        <div class="line_image"><img class="image_trans" src="/images/icon_press_inv.png"/></div>
//        <div class="line_text">Pressure</div>
//        <div class="line_text">100.65 KPa</div>
//      </div>
//      <div class="inner_box">
//        <div class="line_image"><img class="image_trans" src="/images/icon_wind_spd_inv.png"/></div>
//        <div class="line_text">Wind Speed</div>
//        <div class="line_text">2.4 m/s</div>
//      </div>
//      <div class="inner_box">
//        <div class="line_image"><img class="image_trans" src="/images/icon_wind_dir_inv.png"/></div>
//        <div class="line_text">Wind Direction</div>
//        <div class="line_text">270 &#176;</div>
//      </div>
//    </div>';

?>