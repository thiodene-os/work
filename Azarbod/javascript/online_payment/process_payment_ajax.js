<script type="text/javascript">
  var processing_pay = false ; // Shows if we are in the middle of processing

  $(function()
  {
    // When user clicks on proceed to payment, then process the payment
    $("#btn_proceed_to_pay").click(function()
    {
      if (processing_pay)
      {
        alert("Please wait as we are processing.") ;
        return ;
      }

      // Now start processing
      paymentProcessingStarted() ;
      result = true ;

      // First validate the form and then process the payment
      var name_on_card = $("#name_on_cc").val() ;
      var cc_type = $("#cc_type").val() ;
      var cc_num = $.trim($("#cc_num").val()) ;
      var cc_expiry_month = $("#cc_expiry_month").val() ;
      var cc_expiry_year = $("#cc_expiry_year").val() ;
      var cc_expiry = '20'+ cc_expiry_year + '-' + cc_expiry_month;
      var cvd = $.trim($("#cvd").val()) ;
      var transac_type = "~PAYMENT_TRANSACTION_TYPE_PURCHASE~" ;

      if (result && name_on_card.length == "")
      {
        xmlbAlert("Please enter a valid name.") ;
        result = false ;
      }
      if (result && cc_type == "~def_show_on_no_value~")
      {
        xmlbAlert("Please select the type of credit card.") ;
        result = false ;
      }
      if (result && cc_num.length < 16)
      {
        xmlbAlert("Please provide the credit card number or it is wrong.") ;
        result = false ;
      }
      if (result && cc_expiry_month == "~def_show_on_no_value~")
      {
        xmlbAlert("Please select expiry month on your credit card.") ;
        result = false ;
      }
      if (result && cc_expiry_year == "~def_show_on_no_value~")
      {
        xmlbAlert("Please select expiry year on your credit card.") ;
        result = false ;
      }

      if (result && cvd.length < 3)
      {
        xmlbAlert("Please enter the 3-4 Digits CVD Number.") ;
        result = false ;
      }

      if (result)
      {
        // If all good create the program and process the payment via AJAX

        // Pass the info via JSON
        info = '{'
                  + '"customer_id" : "' + %customer_id% + '"'
                  + ',"cc_type" : "' + cc_type + '"'
                  + ',"cc_no" : "' + cc_num + '"'
                  + ',"cc_expiry" : "' + cc_expiry + '"'
                  + ',"cvd" : "' + cvd + '"'
                  + ',"name_on_cc" : "' + name_on_card + '"'
             + '}' ;
        // Make sure to encode in client side and decode on server side     
        prog = "   $info = '" + xmlbEncodeForAJAX(info,false) + "' ;"               
               + "\n $result = processOnlinePaymentAuthorizeNet(%grand_total_js%," 
               + "xmlbDecodeFromAJAX($info)) ;"
               + '\n if (! $result)'
               + "\n" + '   $prog_result = "paymentProcessingEnded() ;" ; '
               + "\n" + ' else'
               + "\n" + '   $prog_result = "doAfterSuccessfulPaymentProcessing()" ; ' ;                     

        runBackEndProg(prog) ;
        //alert (prog) ;
      } // all fine, so process it online
      else
        paymentProcessingEnded() ;
    }) ; // click
  }) ; // document.ready

  function paymentProcessingEnded()
  {
    // Once the payment processing ended redirect to the 
    processing_pay = false ;
    $("#scart_cover").css("display","none") ;
    alert("The payment applied to your Credit Card was unsuccessful! Please try again!") ;
  } // paymentProcessingEnded  

  function paymentProcessingStarted()
  {
    processing_pay = true ;
    $("#scart_cover").css("display","block") ;
  } // paymentProcessingStarted   

  function doAfterSuccessfulPaymentProcessing()
  {
    window.location.assign( '%order_confirmation_href%?customer_id=' + %customer_id% );
  }

</script> 
