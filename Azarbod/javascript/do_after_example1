<script type="text/javascript">
  $(function()
  {
    //Handle the user information, saves it if it is new and continue to summary page
    $(".btn_add_contact_and_continue").click(function()
    {
      // Confirm the qty adjustment of Liquor
      if (! confirm("User this user information and place the order?"))
        return false ;

      //-----------------------------------Begin Script------------------------------

      //-----------------------------------End Script------------------------------

      // If everything OK enter the new Customer and Customer Contact
      // Send the Customer info  
      customer = '{' + customer + '}' ;
      customer = xmlbEncodeForAJAX(customer) ;
      prog = ' $result = saveCustomerInfoForOrderCheckout("' + customer + '") ;'
               + '\n if (! $result)'
               + '\n   $prog_result = "alert(\'" . getGlobalMsg() . "\')" ;'
               + '\n  else'
               + "\n" + '   $prog_result = "doAfterSaveCustomerContact(\" . $result . \")" ; ' ;

      runBackEndProg(prog) ;
      //console.log("prog: " + prog) ; // debug alert

    });

  }) ;

  function doAfterSaveCustomerContact(customer_id) 
  {
    window.location.assign( '%checkout_summay_href%?customer_id=' + customer_id );
  }

</script>
