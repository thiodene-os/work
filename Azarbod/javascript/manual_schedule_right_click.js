<script type="text/javascript">
  %equip_arr%

  var transfer_menu_open = false ; // Tells us if the transfer right click menu is open or not

  $(function()
  {
    // Disable default browser context menu
    $(document).on("contextmenu",".transfer_box",function(event)
    {
      return false ;
    }) ;

    $(document).on("mousedown",".transfer_box",function(event)
    {
      // Select/Un-select this box 
      if (event.which == 1) // Left mouse click
      {
        if ($(this).hasClass("box_picked"))
        {  
          if(! transfer_menu_open) // This happens when user clicks on a equipment to transfer to
            $(this).removeClass("box_picked") ;
        }  
        else  
          $(this).addClass("box_picked") ;
      } // left click
      else if (event.which == 3)
      {
        // Make sure to remove previous menu if any
        $("#mnu_transfer").remove() ;

        if ($(this).hasClass("box_picked"))
        {
          // ************* Open Context Menu ****************
          menu = '<div id="mnu_transfer"><ul>' ;

          // Cut menu
          if (! cut_paste.active)
          {
            // Show cut menu, if not already started
            menu += '<li id="btn_wo_cut">'
                      + ' <img src="/~images_folder~/icon_cut.png" /> Cut'
                  + '</li>' ;

            // Also add the option so user can cut it into clipboard to be moved to another
            // location 
            menu += '<li id="btn_wo_cut_to_clipboard">'
                      + ' <img src="/~images_folder~/icon_cut.png" /> Cut to Clipboard'
                  + '</li>' ;
          }

          // Paste menu
          if (cut_paste.active)
          {
            // Allow paste, if already cut
            menu += '<li class="btn_wo_paste_before">'
                      + ' <img src="/~images_folder~/icon_paste_before.png" /> Paste Before'
                  + '</li>' ;
            menu += '<li class="btn_wo_paste_after">'
                      + ' <img src="/~images_folder~/icon_paste_after.png" /> Paste After'
                  + '</li>' ;

            // Add undo cut 
            menu += '<li class="btn_wo_undo_cut">'
                      + ' <img src="/~images_folder~/icon_undo.png" /> Undo Cut'
                  + '</li>' ;
          }        

          // Also add the option to set this work order as completed
          menu += '<li id="btn_wo_set_completed">'
                    + ' <img src="/~images_folder~/icon_set_completed.png" /> Set as Completed'
                + '</li>' ;

          // Schedule Menu
          menu += '<li id="btn_wo_manual_schedule">'
                    + ' <img src="/~images_folder~/icon_synch.png" /> Manual Schedule'
                + '</li>' ;

          menu += '<li id="btn_wo_auto_schedule">'
                    + ' <img src="/~images_folder~/icon_synch.png" /> Auto Schedule'
                + '</li>' ;

          menu += '</ul></div>' ;

          $(this).append(menu) ;    // Show the manu   
          transfer_menu_open = true ;  
        } //  this box has been already selected  
        event.stopPropagation() ;
      } // right click 
    }) ; // mousedown on transfer box

    // ****************** Set as Completed in Context Menu ********************
    $(document).on("click","#btn_wo_set_completed",function()
    {
      // Build the prog to set each selected work order as completed
      prog  = '   $new_rec = array() ;' 
            + '\n $new_rec[\'STEP_STATUS\'] = WO_ITEM_STEP_STATUS_COMPLETED ;'
            + '\n $new_rec[\'LNK_USER_COMPLETE\'] = curUserId() ;'
            + '\n $new_rec[\'ACT_COMPLETE_DT\'] = date("Y-m-d H:i:s") ;'
            + '\n $do_record = new doRecord("WO_ITEM") ;' 
            + '\n $do_record -> new_record = $new_rec ;' ;
      $(".box_picked").each(function()
      {
        wo_item_id = $(this).attr("wo_item_id") ;              
        prog += '\n $do_record -> id_column_val = ' + wo_item_id + ' ;' 
             +  '\n $do_record -> updateRecord() ;' ;

        // Also remove this row from equipment schedule
        $(".actual_body[wo_item_id=" + wo_item_id + "]").remove() ;

        // Remove from woi_arr as well
        wo_arr_idx = findWorkOrderInArray(wo_item_id) ;
        woi_arr.splice(wo_arr_idx,1) ;             

        // ********* Re-arrange rows under original equipment *************
        // At the end make sure to re-arrange the row numbers for the original equipment
        // Note: we re-arrange for every work order that has moved, so in case user picks
        // say some items from one equipment and some from another, still it works
        equip_id = $(this).closest("tr.actual_body").attr("equip_id") ;
        renumberMachineRows(equip_id) ;
      }) ; // each picked box
      prog += '\n unset($new_rec) ;' 
            + '\n unset($do_record) ;' ;

      runBackEndProg(prog) ;         

      createDateTimeBoxesOnFirstRows() ;
    }) ; // Set as completed  

    // ****************** Set as Manually Scheduled ********************
    $(document).on("click","#btn_wo_manual_schedule",function()
    {

      // Build the prog to set each selected work order as manually scheduled
      prog  = '   $new_rec = array() ;' 
            + '\n $new_rec[\'MANUAL_SCHEDULE\'] = YES ;'
            + '\n $do_record = new doRecord("WO_ITEM") ;' 
            + '\n $do_record -> new_record = $new_rec ;' ;
      $(".box_picked").each(function()
      {
        // Get the Wo_item_id from each operation and build the update query
        wo_item_id = $(this).attr("wo_item_id") ;
        prog += '\n $do_record -> id_column_val = ' + wo_item_id + ' ;' 
             +  '\n $do_record -> updateRecord() ;' ;

        // Add the M (Manual) icon to each relevant rows
        parent_wrap = $(this).parent().parent() ;
        manual_sched_status = '<div class="manually_scheduled" title="Manual Schedule">M</div>' ;
        parent_wrap.find(".manual_sch").replaceWith(manual_sched_status) ;
      }) ; // each picked box

      prog += '\n unset($new_rec) ;' 
            + '\n unset($do_record) ;' 

      runBackEndProg(prog) ;

    }) ; // Set as manually scheduled 


    // ****************** Set as Auto Scheduled ********************
    $(document).on("click","#btn_wo_auto_schedule",function()
    {

      // Build the prog to set each selected work order as auto scheduled
      prog  = '   $new_rec = array() ;' 
            + '\n $new_rec[\'MANUAL_SCHEDULE\'] = NO ;'
            + '\n $do_record = new doRecord("WO_ITEM") ;' 
            + '\n $do_record -> new_record = $new_rec ;' ;
      $(".box_picked").each(function()
      {
        // Get the Wo_item_id from each operation and build the update query
        wo_item_id = $(this).attr("wo_item_id") ;
        prog += '\n $do_record -> id_column_val = ' + wo_item_id + ' ;' 
             +  '\n $do_record -> updateRecord() ;' ;

        // Remove the M (Manual) icon to each relevant rows
        parent_wrap = $(this).parent().parent() ;
        auto_sched_status = '<div class="manual_sch"></div>' ;
        parent_wrap.find(".manually_scheduled").replaceWith(auto_sched_status) ;
      }) ; // each picked box

      prog += '\n unset($new_rec) ;' 
            + '\n unset($do_record) ;' 

      runBackEndProg(prog) ;

    }) ; // Set as auto scheduled 


  }) ; // document.ready

  // Make sure to remove the context menu when user clicks outside. Also stop the event when
  // user clicks inside of the menu
  $('#mnu_transfer').click(function(e)
  {
    e.stopPropagation();
  });
  $(document).click(function()
  {
    $("#mnu_transfer").remove() ;
    transfer_menu_open = false ;
  }) ;

  // When we set a row as completed or un-assign a row, this function checks first row
  // on all machines and adds the date time boxes in case they do not exists
  function createDateTimeBoxesOnFirstRows()
  {
    // ******************** Date Boxes on the First Rows ***************************
    // If the first row on a equipment has been set as completed, then the 
    // date and time boxes will disapper and here we re-make them
    $(".actual_body[row_no=1]").each(function()
    {
      // If this row already has date and time boxes, then skip
      if ($(this).find(".new_start_date").length != 0) 
        return ; // Same as continue

      // ************************* Date box ***********************************
      // Build the date box and make it calendar box
      wo_item_id = $(this).attr("wo_item_id") ;
      date_box = '<input class="new_start_date" type="text"' 
                    + ' wo_item_id="' + wo_item_id + '"'
                    + ' value="' + $(this).find(".start_date").attr("start_date") + '"'  
                    + '/>' ;
      $(this).find(".start_date").html(date_box) ;
      $(this).find(".start_date").addClass("editable") ;

      // Add date picker
      $(this).find(".new_start_date").datepicker(
      {
        showOn: 'button'
        , buttonImage: '/~plugin_folder~/jquery_ui/images/calendar.gif'
        , buttonImageOnly: true
        , dateFormat: 'yy-mm-dd'
        , changeMonth: true
        , changeYear: true
      }) ;

      // ********************** Time Boxes (Hour and Minute **********************
      // Convert start time to a text box only if this is the 
      // started work order. Otherwise we do not know the actual start time and this column
      // is auto-estimated by the system
      start_time_wrap = $(this).find(".start_time") ;
      cur_start_time = start_time_wrap.attr("start_time") ;

      cur_start_hour = cur_start_time.substring(0,2) ;
      cur_start_minute = cur_start_time.substring(3) ;

      // For start time, show two elements, one drop-down from 00 to 23 for hour and one
      // box for minutes
      hour_select = '<select wo_item_id="' + wo_item_id + '" class="new_start_hour">' ;
      for(i = 0 ; i < 24 ; i++)
      {
        // Add zero so when building the time string, we do not need to add leading zeros
        if (i < 10)
          hour = '0' + i ;
        else
          hour = i ;

        // Show the hour as am/pm
        if (i <= 9)
          hour_show = '0' + i + ' am' ;
        else if (i == 10 || i == 11)
          hour_show = i + ' am' ;
        else if (i == 12)
          hour_show = '12 pm' ;
        else if (i >= 13 && i <= 21)
          hour_show = '0' + (i - 12) + ' pm' ;
        else  
          hour_show = (i - 12) + ' pm' ;

        hour_select += '<option value="' + hour + '"' ;
        if (hour == cur_start_hour)
          hour_select += ' selected="selected"' ;
        hour_select += '>' + hour_show + '</option>' ;
      }
      hour_select += '</select>' ;

      $(start_time_wrap).html(
            hour_select 
            + ':<input type="text" wo_item_id="' + wo_item_id + '"' 
                  + ' class="new_start_minute" value="' + cur_start_minute + '" />') ;
      // ******************** End of Start Time ************************           
    }) ; // All first rows 
  } // createDateTimeBoxesOnFirstRows

  // Finds the idx of the given work order in woi_arr
  function findWorkOrderInArray(wo_item_id)
  {
    result = null ;
    for(idx = 0 ; idx < woi_arr.length ; idx++)
      if(woi_arr[idx][%idx_wo_item_id%] == wo_item_id)
      {
        result = idx ;
        break ;
      }

    return result ;
  } // findWorkOrderInArray

  // After a row has moved or removed, this functin is called to renumber all the 
  // rows from 1 to ... under that equipment
  function renumberMachineRows(equip_id)
  {
    row_num = 1 ;
    $(".actual_body[equip_id=" + equip_id + "]").each(function()
    {
      $(this).attr("row_no",row_num) ;
      $(this).find(".first_col span:first-child").text(row_num) ;
      row_num++ ;
    }) ;
  } // renumberMachineRows

  $(document).on("click",".btn_move_unassigned",function()
  {
    this_row = $(this).closest("tr") ;
    this_row_no = this_row.attr("row_no") ; // Use it later
    if (this_row.hasClass("wo_started"))
    {
      xmlbWarn("Work order already started and can not not move.") ;
      return false ;
    }
    equip_id = this_row.attr("equip_id") ; // Keep for later

    wo_item_id = $(this).attr("wo_item_id") ;
    wo_arr_idx = findWorkOrderInArray(wo_item_id) ;
    woi_arr[wo_arr_idx][%idx_wo_equip_id%] = 0 ; // First remove it from equipment in woi_arr
    // Set this row in the woi_arr as un-saved so when saving we make sure
    // to un-assign in the database
    woi_arr[wo_arr_idx][%idx_wo_saved%] = false ;

    // Finally remove the row
    this_row.remove()

    renumberMachineRows(equip_id) ;
    updateTabTitles() ;
    recalcSchedule(equip_id) ;

    // Refresh the un-assigned work orders
    showUnassignedWOs() ;

    // If user has moved the first row to un-assigned, make sure to make the first 
    // row editable again.
    if (this_row_no == 1)
    {
      second_row_wo_item_id = 
        $(".actual_body[equip_id=" + equip_id + "][row_no=1]").attr("wo_item_id") ;
      startEditRow(second_row_wo_item_id) ;
    }         
  }) ; // Quick move to un-assigned

</script>  
