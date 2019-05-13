// This function converts the MV values to PPB
// In the right order CO, NO2, O3
function convertGasMVtoPPB($pt,$co_mv,$no2_mv,$o3_mv)
{
  // Connect to db
  $dbc = db_connect_ut() ;
  
  // SELECT the very last record and UPDATE it
  $query = "SELECT id
            , co_zero_offset, co_sensitivity, co_min_detection, co_max_detection
            , no2_zero_offset, no2_sensitivity, no2_min_detection, no2_max_detection
            , o3_zero_offset, o3_sensitivity, o3_min_detection, o3_max_detection
            FROM config_mv_ppb
            WHERE id = " . $pt ;
  $result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
  $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
  $config_id = $row[0] ;
  // Data values
  $co_zero_offset = $row[1] ;
  $co_sensitivity = $row[2] ;
  $co_min_detection = $row[3] ;
  $co_max_detection = $row[4] ;
  
  $no2_zero_offset = $row[5] ;
  $no2_sensitivity = $row[6] ;
  $no2_min_detection = $row[7] ;
  $no2_max_detection = $row[8] ;
  
  $o3_zero_offset = $row[9] ;
  $o3_sensitivity = $row[10] ;
  $o3_min_detection = $row[11] ;
  $o3_max_detection = $row[12] ;
  // CO
  $co_ppb = ($co_mv - $co_zero_offset) / $co_sensitivity ;
  if ($co_ppb > $co_max_detection)
    $co_ppb = $co_max_detection ;
  else if ($co_ppb < $co_min_detection)
    $co_ppb = $co_min_detection ;
  
  // NO2
  $no2_ppb = ($no2_mv - $no2_zero_offset) / $no2_sensitivity ;
  if ($no2_ppb > $no2_max_detection)
    $no2_ppb = $no2_max_detection ;
  else if ($no2_ppb < $no2_min_detection)
    $no2_ppb = $no2_min_detection ;
  
  // O3
  $o3_ppb = ($o3_mv - $o3_zero_offset) / $o3_sensitivity ;
  if ($o3_ppb > $o3_max_detection)
    $o3_ppb = $o3_max_detection ;
  else if ($o3_ppb < $o3_min_detection)
    $o3_ppb = $o3_min_detection ;
  
  // Close db
  db_close($dbc) ;
  
  return array($co_ppb,$no2_ppb,$o3_ppb) ;
} // convertGasMVtoPPB
