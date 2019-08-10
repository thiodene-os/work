<?php

//require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
require_once('/var/www/html/includes/php/crontab/common_cron.php') ;

$met_ext = '_met' ;
$equipment_id = 86 ;
$daterange_id = 2 ;

// Connect to db
$ftpc = ftp_connect_local() ;

$file = "series_plot". $met_ext . "_id" . $equipment_id . "_dr" . $daterange_id . ".txt" ;
//$file = "chart_container". $met_ext . "_id" . $equipment_id . "_dr" . $daterange_id . ".txt" ;
$filepath = "/var/www/html/includes/php/crontab/files/" . $file;

$url = 'http://crypster.cc' ;

$contents = file_get_contents("$url"); 
if ($contents)
{
  // If previous file then remove it and write new
  if (file_exists($filepath)) 
  {
    unlink($filepath);
  }
  
  $filepath1 = "/var/www/html/includes/php/crontab/files/" . $file;
  
  // Open and write content to file
  $fp = fopen($filepath1, "w") or die ("Couldn't open $filepath1");
  fwrite($fp, $contents);
  fclose($fp);
}

// Close db
ftp_close_local($ftpc) ;

echo "OK" ;

?>