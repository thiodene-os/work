<?php

// For allowing remote connection to API add the following header:
header('Access-Control-Allow-Origin: *') ;
require_once('/var/www/html/api_sync/includes/php/common.php') ;
require_once('/var/www/html/api_sync/includes/php/libs/lib_data.php') ;

$receiving_status = true;

// Switch on the Data receiving for UTORONTO
$result = dataStatusSwitcher($receiving_status) ;
echo $result ;


?>
