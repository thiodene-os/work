// Handle possible Receive Notes
adjust_notes = $("#item_adjust_notes").val() ;
adjust_notes = xmlbEncodeForAJAX(adjust_notes) ;

// Handle possible date
adjust_date = $.trim($("#item_adjust_date").val()) ;

// Sends the manual reception details to Function receiveManuallyLiquorProduct 
prog = " $result = adjustLiquorInventoryQty(%gn_prod_id%" 
                                                + ",'" + num_packs + "'" 
                                                + ",'" + num_singles + "'" 
                                                + ",'" + inv_level_id + "'" 
                                                + ",'" + adjust_date + "'"                                                             
                                                + ",'" + adjust_notes + "') ;"
         + "\n if (! $result)"
         + "\n   $prog_result = \"alert('\" . getGlobalMsg() . \"')\" ;"
         + "\n else"
         + "\n   $prog_result = 'location.reload() ;' ;" ;

//alert(prog) ;
runBackEndProg(prog) ;
