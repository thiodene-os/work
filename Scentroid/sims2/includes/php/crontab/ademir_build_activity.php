<?php
/**
 * Created by PhpStorm.
 * User: gotov.a
 * Date: 2/6/2019
 * Time: 4:54 PM
 */

require_once('/var/www/html/includes/php/crontab/common_cron.php');

// Connect to db
$dbc_local = db_connect_local();

$equipment = false;
$search = false;

$activity_table = build_show_notifications_table($equipment, $search);
$filepath = '/var/www/html/ademir/cron/activity.txt';

// If previous file then remove it and write new
if (file_exists($filepath)) {
    unlink($filepath);
}

// Open and write content to file
$fp = fopen($filepath, "w") or die ("Couldn't open $filepath");
fwrite($fp, $activity_table);
fclose($fp);

// Close db
db_close($dbc_local) ;

echo "Ademir Activity is Build!; \n" ;
