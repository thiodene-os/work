<?

// This function adds buffer times to each operations of a work order
// based on the next operation and the run order
function addPreviousStartBuffer($wo_id, $run_order)
{
  
  // First find the previous operation that has been assigned to an equipment and 
  // see when it will finish or has already finished so we can schedule this one after it
  $sql_str = "SELECT  STEP_STATUS
                    , EST_COMPLETE_DT
                    , ACT_COMPLETE_DT
                    , RUN_ORDER
                    , OPERATION_TYPE.START_BUFFER
                  FROM WO_ITEM
                    INNER JOIN OPERATION_TYPE ON WO_ITEM.LNK_OPERATION_TYPE = OPERATION_TYPE.UID
                WHERE LNK_WORK_ORDER = " . $wo_id
                . " AND RUN_ORDER < " . $run_order 
                . " ORDER BY RUN_ORDER DESC" ;
  $qry = new dbQuery($sql_str,"File: " . __FILE__ . " LINE " . __LINE__) ;
  $prev_wo_item_recs = $qry -> getRecords() ;
  unset($qry) ;

  $prev_finish = null ;
  $prev_start_buffer = 0 ;
  foreach ($prev_wo_item_recs as $prev_wo_item_rec)
  {
    // If found, then add the required info to the array
    if ($prev_wo_item_rec['STEP_STATUS'] == WO_ITEM_STEP_STATUS_COMPLETED
          && is_null($prev_wo_item_rec['ACT_COMPLETE_DT'])) // In case
    {      
      $prev_finish = $prev_wo_item_rec['ACT_COMPLETE_DT'] ;
    }
    elseif (! is_null($prev_wo_item_rec['EST_COMPLETE_DT']))
    {  
      $prev_finish = $prev_wo_item_rec['EST_COMPLETE_DT'] ;
    }
      
    // Buffer from the previous operation
    $prev_start_buffer += $prev_wo_item_rec['START_BUFFER'] ;
    
    if (! is_null($prev_finish))
      break ;
  }
  // Remove the very last buffer because it corresponds to the very first completed operation
  // This operation is done so no need for its buffer
  $prev_start_buffer -= $prev_wo_item_rec['START_BUFFER'] ;
  return array ($prev_finish, $prev_start_buffer) ;
  
} //addPreviousStartBuffer

?>
