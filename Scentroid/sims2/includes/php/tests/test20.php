<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
//require_once(TEMPLATES_PATH . "/template_main.php");

// For admin login always keep that password and change it to test123 if not known
//$sims_token = file_get_contents("http://api2.scentroid.com:8080/user/login") ;

//echo $sims_token ;

$server_output = '{"created_by":"10","created_on":"1450334089","email":"admin","expires_at":1544540157,"first_name":"Admin","id":"None","last_name":"User","password":"None","roles":"[\"admin\",\"user\"]"}';

//$curlc = curl_init();

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_PORT => "8080",
  CURLOPT_URL => "http://api2.scentroid.com:8080/notification/mark?id=64-1517166511859",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "PATCH",
  CURLOPT_POSTFIELDS => "email=pourya%40gmail.com&password=321&firstname=pourya&lastname=jalalipour",
  CURLOPT_HTTPHEADER => array(
    "Postman-Token: 7b08c549-839a-41ab-90ec-8d21e61f4d55",
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

?>