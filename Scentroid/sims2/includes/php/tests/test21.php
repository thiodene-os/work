<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
//require_once(TEMPLATES_PATH . "/template_main.php");

// For admin login always keep that password and change it to test123 if not known
//$sims_token = file_get_contents("http://api2.scentroid.com:8080/user/login") ;

//echo $sims_token ;

$curlc = curl_init();

curl_setopt($curlc, CURLOPT_URL,"http://api2.scentroid.com:8080/user/login");
curl_setopt($curlc, CURLOPT_POST, 1);
// Enable headers
curl_setopt($curlc, CURLOPT_HEADER, 1);
//get only headers
//curl_setopt($curlc, CURLOPT_NOBODY, 1);
curl_setopt($curlc, CURLOPT_POSTFIELDS,
            "email=admin&password=ides1980");

// In real life you should use something like:
// curl_setopt($curlc, CURLOPT_POSTFIELDS, 
//          http_build_query(array('postvar1' => 'value1')));

// Receive server response ...
curl_setopt($curlc, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($curlc);

curl_close ($curlc);

// Further processing ...
echo $server_output ;

?>