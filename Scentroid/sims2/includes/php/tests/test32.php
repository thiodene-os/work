<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
//require_once(TEMPLATES_PATH . "/template_main.php");

$to_email_address = 'serge.a@scentroid.com' ;
//$to_email_address = 'ayissi.serge@gmail.com' ;
//$to_email_address = 'ayissi_serge@hotmail.com' ;
$subject = 'Test the PHP mail' ;
$message = 'This program is free software; 
you can redistribute it and/or modify it under the terms of the PHP License 
as published by the PHP Group and included in the distribution in the file: LICENSE

This program is distributed in the hope that it will be useful, 
but WITHOUT ANY WARRANTY; 
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

If you did not receive a copy of the PHP license, 
or have any questions about PHP licensing, please contact license@php.net.' ;

$headers = "From: webmaster@scentroid.com" ;
// . "\r\n"
// . "CC: somebodyelse@example.com";

// Test the simple email function from the current server (sims2.scentroid.com)
//mail($to_email_address,$subject,$message,[$headers],[$parameters]);
//mail($to_email_address,$subject,$message,$headers);
if (mail($to_email_address,$subject,$message,$headers))
{
  echo "Message accepted" ;
}
else
{
  echo "Error: Message not accepted" ;
}

?>