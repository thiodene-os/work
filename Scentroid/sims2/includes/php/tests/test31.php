<?php

//require 'PHPMailerAutoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
//require 'vendor/autoload.php';
//require(__DIR__ . '/vendor/autoload.php');
require('/root/vendor/autoload.php');

$mail = new PHPMailer;
$mail->setFrom('from@scentroid.com', 'Tester Scentroid');
$mail->addAddress('serge.a@scentroid.com', 'Serge Ayissi');
$mail->Subject  = 'First PHPMailer Message';
$mail->Body     = 'Hi! This is my first e-mail sent through PHPMailer.';
if(!$mail->send()) {
  echo 'Message was not sent.';
  echo 'Mailer error: ' . $mail->ErrorInfo;
} else {
  echo 'Message has been sent.';
}

?>
