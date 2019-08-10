<?php
$username = 'admin';
$password = 'ides1980';
$loginUrl = 'http://api2.scentroid.com:8080/user/login';

//init curl
$ch = curl_init();

//Set the URL to work with
curl_setopt($ch, CURLOPT_URL, $loginUrl);
curl_setopt($ch, CURLOPT_PORT, 8080);

// ENABLE HTTP POST
curl_setopt($ch, CURLOPT_POST, 1);

//Set the post parameters
curl_setopt($ch, CURLOPT_POSTFIELDS, 'email='.$username.'&password='.$password);

//Handle cookies for the login
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');

//Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
//not to print out the results of its query.
//Instead, it will return the results as a string return value
//from curl_exec() instead of the usual true/false.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//execute the request (the login)
$store = curl_exec($ch);

//the login is now done and you can continue to get the
//protected content.

// DISABLE HTTP POST
curl_setopt($ch, CURLOPT_POST, 0);

//set the URL to the protected file
//curl_setopt($ch, CURLOPT_URL, 'http://api2.scentroid.com:8080/equipment/map'); // OK
//curl_setopt($ch, CURLOPT_URL, 'http://api2.scentroid.com:8080/equipment/samples?equipment=71'); // OK
//curl_setopt($ch, CURLOPT_URL, 'http://api2.scentroid.com:8080/equipment');
//curl_setopt($ch, CURLOPT_URL, 'http://api2.scentroid.com:8080/equipment/aqi?equipment=71'); // OK
//curl_setopt($ch, CURLOPT_URL, 'http://api2.scentroid.com:8080/equipment/type/_search?pretty=true -d');
//curl_setopt($ch, CURLOPT_URL, 'http://api2.scentroid.com:8080/company/map');
//curl_setopt($ch, CURLOPT_URL, 'http://api2.scentroid.com:8080/equipment/locations');
//curl_setopt($ch, CURLOPT_URL, 'http://api2.scentroid.com:8080/sensor');
//curl_setopt($ch, CURLOPT_URL, 'http://api2.scentroid.com:8080/equipment/layer?category=Chemical'); // OK
//curl_setopt($ch, CURLOPT_URL, 'http://api2.scentroid.com:8080/equipment/aqi?equipment=45&hours=1'); // OK
//curl_setopt($ch, CURLOPT_URL, 'http://api2.scentroid.com:8080/equipment/aqi?equipment=45&hours=8'); // OK
curl_setopt($ch, CURLOPT_URL, 'http://api2.scentroid.com:8080/equipment/aqi?equipment=45&hours=24'); // OK

//execute the request
$content = curl_exec($ch);

//save the data to disk
file_put_contents('result.txt', $content);
