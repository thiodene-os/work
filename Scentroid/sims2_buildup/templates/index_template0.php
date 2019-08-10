<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/sims2/includes/php/common.php');
require_once(TEMPLATES_PATH . "/template_main.php");

/*
    Now you can handle all your php logic outside of the template
    file which makes for very clean code!
*/

/*----------------------------------------------------------Update input Field top bar------------------------------------------------------------------- */

$update_input_field = buildTopInputFields() ;

/*----------------------------------------------------------Left Side Chart Data------------------------------------------------------------------------- */

$left_side_data = buildSensorTable() ;

$left_side_data .= buildSensorChart() ;

/*----------------------------------------------------------Right Side Chart Data------------------------------------------------------------------------ */

$right_side_data = buildMainMap() ;

$right_side_data .= buildMetTable() ;

$right_side_data .= buildMetChart() ;

/*-----------------------------------Collect Variables and Send all------------------------------------ */

// Must pass in variables (as an array) to use in template
$variables = array(
    'update_input_field' => $update_input_field,
    'left_side_data' => $left_side_data,
    'right_side_data' => $right_side_data
);

renderLayoutWithContentFile("template_home.php", $variables);

?>