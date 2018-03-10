      <script type="text/javascript">
        $(function()
        {
          //Handle the user information, saves it if it is new and continue to summary page
          $(".btn_send_customer_message").click(function()
          {
            // Confirm sending this message
            if (! confirm("Do you want to send this Message?"))
              return false ;
              
            // Customer Information
            customer_name = $("#customer_name").val() ;  
            if (customer_name == "")
            {
              alert("Please enter a valid Full Name!") ;
              return false ;
            }
            
            customer_email = $("#customer_email").val() ; 
            if (customer_email == "")
            {
              alert("Please enter a Email!") ;
              return false ;
            }
            
            company_name = $("#company_name").val() ; 
            if (company_name == "")
            {
              alert("Please enter a valid Company Name!") ;
              return false ;
            }
            
            customer_phone = $("#customer_phone").val() ; 
            if (customer_phone == "")
            {
              alert("Please enter a valid Phone Number!") ;
              return false ;
            }
            
            customer_message = $("#customer_message").val() ; 
            if (customer_message == "")
            {
              alert("Please enter a Message!") ;
              return false ;
            }
            
            staff_email = 'ayissi_serge@hotmail.com' ;
            
            // If all the customer information is OK send the message
            prog = ' $email_params = array() ;'
                     + '\n $email_params[\'to_email\'] = "' + staff_email + '" ;'
                     + '\n $email_params[\'from_name\'] = "' + customer_name + ' from ' + company_name + '" ;'
                     + '\n $email_params[\'from_email\'] = "' + customer_email + '" ;'
                     + '\n $template_params = array() ;'
                     + '\n $template_params[\'name\'] = "' + customer_name + '" ;'
                     + '\n $template_params[\'phone\'] = "' + customer_phone + '" ;'
                     + '\n $template_params[\'email\'] = "' + customer_email + '" ;'
                     + '\n $inquiry = htmlspecialchars("' + customer_message + '") ;'
                     + '\n $inquiry = trim(str_replace("\n","<br />",$inquiry)) ;'
                     + '\n $inquiry = removeExtraSpaces($inquiry) ;'
                     + '\n $inquiry = fixEncoding($inquiry) ;'
                     + '\n $template_params[\'inquiry\'] = $inquiry ;'
                     + '\n $mail = new JBurstMailer($email_params) ;'
                     + '\n $mail -> template_id = \'Contact Form\' ;'
                     + '\n $mail -> template_params = $template_params ;'
                     + '\n $mail -> how_generated = EMAIL_HOW_GEN_CONTACT_PAGE ;'
                     + '\n $mail -> priority = EQ_PRIORITY_URGENT ;'
                     + '\n $mail -> Send() ;'
                     + "\n" + '   $prog_result = "doAfterSendCustomerMessage()" ; ' ;
            
            console.log("prog: " + prog) ; // debug alert
            // runBackEndProg(prog) ;
            
            
            
          });
          
        }) ;
        
        function doAfterSendCustomerMessage() 
        {
          alert('The message has been sent!') ;
        }
      </script>
