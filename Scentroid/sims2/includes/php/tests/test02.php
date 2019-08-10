<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
require_once(TEMPLATES_PATH . "/template_main.php");


// Connect to db
$ftpc = ftp_connect_local() ;

$filepath = "/var/www/html/includes/php/crontab/files/test_equipment.txt";

if (file_exists($filepath)) {
  unlink($filepath);
}

$url = 'http://crypster.cc' ;

$contents = file_get_contents("$url"); 
if ($contents) {

  // echo $myspace;
  $filepath1 = "/var/www/html/includes/php/crontab/files/test_equipment.txt";
  
  //$filetemp = tmpfile();
  //ftp_fput($ftpc, "../crontab/files/test_equipment.txt", $filetemp, FTP_ASCII);
  //ftp_chmod($ftpc, 0777, "../crontab/files/test_equipment.txt");

  $fp = fopen($filepath1, "w") or die ("Couldn't open $filepath1");
  fwrite($fp, $contents);
  fclose($fp);

}

// Close db
ftp_close_local($ftpc) ;

echo "OK" ;

?>