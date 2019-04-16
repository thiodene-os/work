<?php
$path_cam = "/home/scentroid/cam_snapshot/camera" ;
$path_lidar = "/home/scentroid/cam_snapshot/lidar" ;
$path_image = "/var/www/html/images" ;

//$files = scandir($path) ;
//echo var_dump($files) ;

$files_lidar = array_diff(scandir($path_lidar), array('.', '..')) ;
$files_cam = array_diff(scandir($path_cam), array('.', '..')) ;
//echo var_dump($files) ;


// -------------------------------------------------------------LIDAR
$file_lidar_list = array() ;
$latest_lidar_tmstp = 0 ;
foreach ($files_lidar as $file)
{

  $file_lidar_tmstp = filemtime($path_lidar . '/' . $file) ;
  //echo $file . ": " . $file_lidar_tmstp ;
  $file_lidar_list[$file_lidar_tmstp] = $file ;

  if($latest_lidar_tmstp <= $file_lidar_tmstp)
    $latest_lidar_tmstp = $file_lidar_tmstp ;

}

echo $file_lidar_list[$latest_lidar_tmstp] ;
$latest_lidar_file = $file_lidar_list[$latest_lidar_tmstp] ;


// -------------------------------------------------------------CAM
$file_cam_list = array() ;
$latest_cam_tmstp = 0 ;
foreach ($files_cam as $file)
{

  $file_cam_tmstp = filemtime($path_cam . '/' . $file) ;
  //echo $file . ": " . $file_cam_tmstp ;
  $file_cam_list[$file_cam_tmstp] = $file ;

  if($latest_cam_tmstp <= $file_cam_tmstp)
    $latest_cam_tmstp = $file_cam_tmstp ;

}

echo $file_cam_list[$latest_cam_tmstp] ;
$latest_cam_file = $file_cam_list[$latest_cam_tmstp] ;



// Now copy the file over to /mages
// LIDAR
if (!copy($path_lidar . '/' . $latest_lidar_file, $path_image . '/' . $latest_lidar_file))
{
  echo "Copy failed!" ;

}
// CAM
if (!copy($path_cam . '/' . $latest_cam_file, $path_image . '/' . $latest_cam_file))
{
  echo "Copy failed!" ;

}
