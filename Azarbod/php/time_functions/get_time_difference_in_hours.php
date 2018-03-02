<?php

// For Auto-Schedulers:
// In order to set the EST START DT at 6am of the current day for the very first operation
// We calculate the number of hours between the actually Estimated Date
// to help shifts the times of the current and following operations accordingly
function getTimeDifferenceInHours($est_start_dt)
{
  
  // Set the EST START DT at 6am of the current day for the very first
  // and then shift the times of the following operations accordingly
  
  // Get today's date and time
  $today_at_six_am = strtotime(date("Y-m-d") . ' ' . '06:00:00') ;
  // Current operation start date and time
  $est_start_date = strtotime($est_start_dt) ;
  
  // Calculate the time difference in hours
  $time_difference = $today_at_six_am - $est_start_date ;
  if ($time_difference >= 0)
  {
    $time_difference_hours = intval($time_difference / ( 60 * 60 ));
    $time_difference_sign = '+' ;
  }
  else
  {
    $time_difference_hours = intval($time_difference / ( 60 * 60 ) * -1);
    $time_difference_sign = '-' ;
  }
  
  return array ($time_difference_hours, $time_difference_sign) ;
  
} // getTimeDifferenceInHours

?>
