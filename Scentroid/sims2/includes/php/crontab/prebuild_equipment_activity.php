<?php

require_once('/var/www/html/includes/php/crontab/common_cron.php') ;

// Connect to db
$dbc_local = db_connect_local() ;

//$equipment = false ;
//$search = false ;
//$activity_table = buildShowNotificationsTable($equipment,$search) ;

$activity_table = buildShowNotificationsTableAll() ;

// Chart
$file_notification = 'show_equipment_activity_.txt' ;
$filepath = "/var/www/html/includes/php/crontab/files/" . $file_notification;

// If previous file then remove it and write new
if (file_exists($filepath)) 
{
  unlink($filepath);
}

// Open and write content to file
$fp = fopen($filepath, "w") or die ("Couldn't open $filepath");
fwrite($fp, $activity_table);
fclose($fp);

// Close db
db_close($dbc_local) ;

echo " Build Notifications:OK; \n" ;

?>