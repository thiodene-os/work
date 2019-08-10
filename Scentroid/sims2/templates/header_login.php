<!DOCTYPE html>
<html>
<head>
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<style type="text/css">
@import url(/includes/css/common.css);
@import url(/includes/css/login.css);   
</style>

<script type="text/javascript">
$(document).ready(function() {
  
  // Remove one data points from the chart
  $(".btn_login").click(function()
  {
    //alert("Login Attempt") ;
    //username = $("#username").val() ;
    var email = $("#email").val() ;
    var password = $("#password").val() ;
    var login = true ;
    // Validate the email address or accept Admin
    if (!validateEmail(email) && email != 'admin')
    {
      alert('Please enter a valid Email address') ;
    //alert(username + ':' + password) ;
      return false;
    }
    if (password.length < 4)
    {
      alert('Please enter a valid Password (at least 4 characters)') ;
    //alert(username + ':' + password) ;
      return false;
    }
    
    // If everything OK query AJAX for login credentials
    $.ajax({
        type: 'GET',
        url: "/includes/php/ajax/verify_login.php",             
        dataType: "html",   //expect html to be returned   
        data: {email: email,password: password, login: login},
        success: function(response)
        {
          // If the response has content it means the Login failed
          if (response.length == 0)
            window.location.href = 'http://sims2.scentroid.com/overview/' ; // Overview page for now
          else if (response.length == 1)
            window.location.href = 'http://sims2.scentroid.com/overview/index.php?tour=true' ; // Overview page first connection
          else
            $("#login_container").html(response);
        }
    });
    
  });
  
  // Trigger the submit button with ENTER pressed
  $('#form_login').keydown(function(e)
  {
    if (e.keyCode === 13) 
    { // If Enter key pressed
      $('.btn_login').click();//Trigger search button click event
    }
  });

});

// In case email Addresses are accepted too!
function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}
</script>
</head>
<body>
