<?php

require_once('/var/www/html/includes/php/common.php') ;
require_once('/var/www/html/includes/php/libs/lib_data.php') ;

if (isset($_GET['submitted'])) 
{
    // Get the selected company ID from GET
    $pt = $_GET['pt'] ;
    $data = $_GET['data'] ;
    //$timestamp = $_GET['timestamp'] ;
    
    $result = recordPolluTrackerData($pollu, $data) ;
    echo $result ;
}
else
{
    // If no position store NULL (NULL by default)
    //$company = get_session_company_id_from_user($_SESSION['id']) ;
    echo '{"submitted": false}' ;
}


?>
