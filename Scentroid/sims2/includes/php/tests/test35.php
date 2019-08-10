<?php
# Test php_put_contents


//file_put_contents('log.txt',date('c')."\n".implode("\n", $error),FILE_APPEND);
$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ \r\n";

//if (!file_put_contents('file_test.txt', rand(1024), FILE_APPEND)) 
if (!file_put_contents('file_test.txt', $characters, FILE_APPEND)) 
{
  exit('file_put_contents() error!');
}
else
{
  echo "Current Disk:OK;" ;
}

?>
