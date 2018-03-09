      <script type="text/javascript">
        
        // This function handles the Liquor Checkout for an event happening shortly
        
        $(function()
        {
          $(".btn_start_checkout_process").click(function()
          {
            // Confirm the checkout for event
            if (! confirm("Checkout Items for this event?"))
              return false ;
            
            parent_wrap = $(this).parent() ;
            
            // Check for A checkout destination facility
            facility_id = parent_wrap.find('.facility').val() ;
            
            if (facility_id == "~def_show_on_no_value~")
            {
              xmlbWarn("Please select a checkout destination Facility!!") ; 
              return false ; // break out of each loop
            }
            
            // Check for A Bartender
            bartender_id = parent_wrap.find('.bartender').val() ;
            
            if (bartender_id == "~def_show_on_no_value~")
            {
              xmlbWarn("Please select a Bartender!!") ; 
              return false ; // break out of each loop
            }
            
            // Handle possible Chekout Notes
            checkout_notes = $("#checkout_notes").val() ;
            checkout_notes = xmlbEncodeForAJAX(checkout_notes) ;
            
            prog =  '$do_record = new doRecord("LIQUOR_CHECKOUT") ;'
                  + "\n" + '$new_rec = array() ;'
                  + "\n" + '$new_rec[\'LNK_EVENT\'] = ' + %event_id% + ' ;'
                  + "\n" + '$new_rec[\'LNK_BARTENDER\'] = ' + bartender_id + ' ;'
                  + "\n" + '$new_rec[\'LNK_FACILITY\'] = ' + facility_id + ' ;'
                  + "\n" + '$new_rec[\'CHECKOUT_NOTES\'] = \'' + checkout_notes + '\' ;'
                  + "\n" + '$new_rec[\'CHECKOUT_STATUS\'] = LIQUOR_CHECKOUT_STATUS_PREPARATION ;'
                  + "\n" + '$do_record -> new_record = $new_rec ;'
                  + "\n" + 'if (! $do_record -> insert()) '
                  + "\n" + '{ '
                  + "\n" + '  debug(getGlobalMsg(),"getGlobalMsg","File: " . __FILE__ . " Line: " . __LINE__) ;'
                  + "\n" + '  return Null ; '
                  + "\n" + '} '
                  + "\n" + 'else '
                  + "\n" + '  $lq_checkout_id = $do_record -> id_column_val ; '
                  + "\n" + 'unset($new_rec) ;'
                  + "\n" + 'unset($do_record) ;'
                  + "\n" + '$prog_result = "doAfterCheckoutSessionSaved(\" . $lq_checkout_id . \")" ; ' ;
            
            runBackEndProg(prog) ; 
            
          }); // Validate Checkout Start and redirect to liquor_checkout_view
          
        }) ; // document.ready
        
        function doAfterCheckoutSessionSaved(liq_checkout_id) 
        {
          window.location.assign( '%checkout_redirect_href%?liquor_checkout_id=' + liq_checkout_id );
        }
        
      </script> 
