<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
//require_once(TEMPLATES_PATH . "/template_main.php");

// For admin login always keep that password and change it to test123 if not known
//$sims_token = file_get_contents("http://api2.scentroid.com:8080/user/login") ;

//echo $sims_token ;

$curlc = curl_init();

curl_setopt($curlc, CURLOPT_URL,"http://api2.scentroid.com:8080/user/login");
curl_setopt($curlc, CURLOPT_POST, 1);
curl_setopt($curlc, CURLOPT_POSTFIELDS,
            "email=admin&password=ides1980");
/* 
//ref: https://tecadmin.net/post-json-data-php-curl/
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);

// Set HTTP Header for POST request 
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($payload))
);
*/

// Receive server response ...
curl_setopt($curlc, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($curlc);

curl_close ($curlc);

// Further processing ...
echo $server_output ;

?>