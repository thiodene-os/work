<?php

// After user saves the CNC or master schedule, this function updates and auto-assigns the
// hone operation to hone machines
function autoScheduleHoneOperations()
{
  
  set_time_limit (3600) ;
  
  // **************************************************************************************
  // ************ First reschedule the ones that have been allready assigned **************

  // Note: We do not sort the operations here. The timings have been already assigned and 
  // sorted by SCH_ORDER on screen and no need to sort here
  
  // Select all hone operations on each hone machine and move them based on the previous 
  // operation schedule
  $sql_str = "SELECT  WO_ITEM.UID AS WO_ITEM_ID
                    , LNK_WORK_ORDER AS WO_ID
                    , RUN_ORDER
                    , PROCESS_DESC
                    , PRODUCT_MAIN.DIAMETER_VAL
                    , MANUAL_SCHEDULE
                    , OPERATION_TYPE.START_BUFFER
                    , EST_CYCLE_TIME
                    , PRODUCT_GEN.MEASURE_TYPE
                    , WO_ITEM.EST_START_DT
                    , WO_ITEM.ACT_START_DT
                FROM WO_ITEM 
                  INNER JOIN WORK_ORDER ON WORK_ORDER.UID = WO_ITEM.LNK_WORK_ORDER
                  INNER JOIN PRODUCT_GEN ON PRODUCT_GEN.UID = WORK_ORDER.LNK_PRODUCT
                  INNER JOIN PRODUCT_MAIN ON PRODUCT_GEN.UID = PRODUCT_MAIN.LNK_PRODUCT_GEN
                  INNER JOIN OPERATION_TYPE ON WO_ITEM.LNK_OPERATION_TYPE = OPERATION_TYPE.UID
                  LEFT JOIN EQUIPMENT ON EQUIPMENT.UID = WO_ITEM.LNK_EQUIPMENT
                WHERE
                    WO_STATUS != " . WO_STATUS_COMPLETED
              . "   AND WO_ITEM.STEP_STATUS IN(" . WO_ITEM_STEP_STATUS_WAITING . "," 
                                             . WO_ITEM_STEP_STATUS_ACTIVE . ")"
              . "   AND EQUIPMENT.EQUIP_STATUS = " . EQUIPMENT_STATUS_WORKING
              . "   AND LNK_OPERATION_TYPE = " . OPERATION_TYPE_ID_HONE 
                  // . "     AND WORK_ORDER.UID = 36857" // debug for test
                  // . "     AND WORK_ORDER.UID >= 36857 LIMIT 200" // debug for test
                  . " ORDER BY SCH_ORDER"
              ;
  $qry = new dbQuery($sql_str,"File: " . __FILE__ . " LINE " . __LINE__) ;
  $wo_item_recs = $qry -> getRecords() ;
  $rec_count = $qry -> getCount() ;
  unset($qry) ;

  // **************************************************************************************
  // We will have all the items sorted by schedule time, so we can go through 
  // the operations and assign them one by one to each hone machine  
  
  $all_oprs = array() ;
  foreach($wo_item_recs as $wo_item_rec)
  {
    $cur_opr = new stdClass() ;
    
    $cur_opr -> wo_item_id = $wo_item_rec['WO_ITEM_ID'] ;
    $cur_opr -> manual = $wo_item_rec['MANUAL_SCHEDULE'] ;
    $cur_opr -> process_desc = $wo_item_rec['PROCESS_DESC'] ;
    $cur_opr -> diameter = $wo_item_rec['DIAMETER_VAL'] ;
    $cur_opr -> run_order = $wo_item_rec['RUN_ORDER'] ;
    $cur_opr -> start_buffer = floatval($wo_item_rec['START_BUFFER']) ;
    $cur_opr -> est_cycle_time = $wo_item_rec['EST_CYCLE_TIME'] ;
    $cur_opr -> meas_type = $wo_item_rec['MEASURE_TYPE'] ;
    $cur_opr -> est_start_dt = $wo_item_rec['EST_START_DT'] ;
    $cur_opr -> act_start_dt = $wo_item_rec['ACT_START_DT'] ;

    $all_oprs[] = $cur_opr ;      
    unset($cur_opr) ;
    
    // if (count($all_oprs) > 5)
      // break ;
  } // Each main wo item
  
  // Finally go through all operations and assign them to each machine
  
  // Track the order on each machine
  $hone01_order = 1 ;
  $hone02_order = 1 ;
  $hone03_order = 1 ;
  // For shifting timings based on the first operation (6am current day)
  $first_operation = FALSE ;
  foreach($all_oprs as $cur_opr)
  {
  
    // Now assign to either hone1, hone2 or hone3 depending on the diameter or type of hone
    // Also update the order on that specific machine
    if (empty($cur_opr -> diameter))
    {
      $equip_id = NULL ;
    }
    if (isLike($cur_opr -> process_desc,"polish"))
    {
      $equip_id = HONE03_EQUIPMENT_ID ; // Polish goes here only
      $sch_order = $hone03_order ;
      
      // ---------------------------Begin Time Switching to 6AM----------------------------------
      // Set the EST START DT at 6am of the current day for the very first
      // and then shift the times of the following operations accordingly
      if ($hone03_order == 1)
      {
          $timedifference = getTimeDifferenceInHours($cur_opr -> est_start_dt) ;
          list ($time_difference_hours_hone03, $time_difference_sign_hone03) = $timedifference ;
          $first_operation = TRUE ;
      }
      
      $time_difference_hours = $time_difference_hours_hone03 ; 
      $time_difference_sign = $time_difference_sign_hone03 ;
      // ---------------------------End Time Switching to 6AM Current------------------------------
      
      $hone03_order++ ;
    }
    else
    {
      // Convert the diameter to inch if needed
      if ($cur_opr -> diameter == MEASURE_TYPE_METRIC)
        $diameter_val = $cur_opr -> diameter / MILLIMETERS_PER_INCH ;
      else
        $diameter_val = $cur_opr -> diameter ;
      
      if ($diameter_val < .5)
      {
        $equip_id = HONE01_EQUIPMENT_ID  ;
        $sch_order = $hone01_order ;
        
        // ---------------------------Begin Time Switching to 6AM----------------------------------
        // Set the EST START DT at 6am of the current day for the very first
        // and then shift the times of the following operations accordingly
        if ($hone01_order == 1)
        {
          $timedifference = getTimeDifferenceInHours($cur_opr -> est_start_dt) ;
          list ($time_difference_hours_hone01, $time_difference_sign_hone01) = $timedifference ;
          $first_operation = TRUE ;
        }
        
        $time_difference_hours = $time_difference_hours_hone01 ; 
        $time_difference_sign = $time_difference_sign_hone01 ;
        // ---------------------------End Time Switching to 6AM Current------------------------------
        
        $hone01_order++ ;
      }
      else
      {  
        $equip_id = HONE02_EQUIPMENT_ID  ;
        $sch_order = $hone02_order ;
        
        // ---------------------------Begin Time Switching to 6AM----------------------------------
        // Set the EST START DT at 6am of the current day for the very first
        // and then shift the times of the following operations accordingly
        if ($hone02_order == 1)
        {
          $timedifference = getTimeDifferenceInHours($cur_opr -> est_start_dt) ;
          list ($time_difference_hours_hone02, $time_difference_sign_hone02) = $timedifference ;
          $first_operation = TRUE ;
        }
        
        $time_difference_hours = $time_difference_hours_hone02 ; 
        $time_difference_sign = $time_difference_sign_hone02 ;
        // ---------------------------End Time Switching to 6AM Current------------------------------
        
        $hone02_order++ ;
      }  
    }
  
    $do_record = new doRecord("WO_ITEM") ;
    $new_rec = array() ;
    $new_rec['LNK_EQUIPMENT'] = $equip_id ;
    
    if ($first_operation)
    {
      $new_rec['EST_START_DT'] = date("Y-m-d") . ' ' . '06:00:00' ;
    }
    else
    {
      $new_rec['EST_START_DT'] = date("Y-m-d H:i:s" , strtotime($cur_opr -> est_start_dt . ' ' 
                               . $time_difference_sign . $time_difference_hours . " hours")) ;
    }
    
    $new_rec['SCH_ORDER'] = $sch_order ;
    $new_rec['+WOI_SYSTEM_LOG'] = '<li>' . date("Y-m-d H:i") . ': Auto Scheduled.</li>' ;
    $do_record -> new_record = $new_rec ;
    $do_record -> id_column_val = $cur_opr -> wo_item_id ;
    $do_record -> update() ;
    unset($new_rec) ;
    unset($do_record) ;
    
    // If not first operation add time difference
    $first_operation = FALSE ;
    
  }
} // autoScheduleHoneOperations

?>
