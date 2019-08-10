<?php

// Main libraries for Sims2 Connections

// Gets the Query result from sims2.0 in the form of json string file
function getSims2QueryResult($query_url)
{
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
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  //execute the request (the login)
  $store = curl_exec($ch);

  // DISABLE HTTP POST
  curl_setopt($ch, CURLOPT_POST, 0);

  //set the URL to the protected file
  curl_setopt($ch, CURLOPT_URL, $query_url); // OK

  //execute the request
  $result = curl_exec($ch);

  //save the data to disk
  //file_put_contents('result.txt', $content);
  
  return $result ;
} // getSims2QueryResult

?>