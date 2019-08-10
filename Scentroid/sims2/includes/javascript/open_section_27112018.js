// This functionality is used to Edit or Delete a user
$( function() 
{
  
  $(".edit_user").click(function()
  {

    this_parent = $(this).parent().parent() ;
    // get id of the user
    user_id = this_parent.attr("user_id");
    // Display the corresponding page that has to be reached on click
    var page_url = window.location.href ;

    // create an array of the current URL considering being located in the settings page
    var url_array = page_url.split ("/settings") ;
    // redirect page for editing user
    var new_page_url = url_array[0] + '/user/edit_user.php?id=' + user_id ;

    // Redirect to new users page
    window.location.href = new_page_url ;
    
  });
  
  $(".delete_user").click(function()
  {
    // Confirm Deleting a user
    if (! confirm("Do you want to delete this User?"))
      return false ;
    
    this_parent = $(this).parent().parent() ;
    
    // get id of the user
    user_id = this_parent.attr("user_id");
    
    // Make an AJAX delete
    // ----------------------------------------------------------------------------------------------
    // Update user info through AJAX
    $.ajax({
        type: 'GET',
        url: "/includes/php/ajax/update_users.php",             
        dataType: "html",   //expect html to be returned   
        data: {user_id: user_id, delete:true},
        success: function(response)
        {
          // If the response has content it means the Login failed
          if (response.length == 0)
          {
            // Give an Alert for now before making it fancier looking!
            // Remove the row upon success
            this_parent.remove() ;
            alert_script = '<script>alert("One user has just been deleted!") ;</script>' ;
            
            $("#settings_user_container").html(alert_script); // Overview page for now
          }
          else
          {
            // Diagnose the error from Back-End if Settings could not be saved
            alert_script = '<script>alert("' + response + '") ;</script>' ;
            $("#settings_user_container").html(alert_script);
          }
        }
    });
    
  });
  
  $(".add_user").click(function()
  {
    
    this_parent = $(this).parent().parent() ;
    // Display the corresponding page that has to be reached on click
    var page_url = window.location.href ;
    
    // create an array of the current URL considering being located in the settings page
    var url_array = page_url.split ("/settings") ;
    // redirect page for editing user
    var new_page_url = url_array[0] + '/user/add_user.php' ;
    
    // Redirect to new users page
    window.location.href = new_page_url ;
    
  });
  
}) ;


// this functionality is used to open and close Settings sections
var acc = document.getElementsByClassName("section");
var i;

for (i = 0; i < acc.length; i++) 
{
  acc[i].addEventListener("click", function() 
  {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } 
    else 
    {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}

// This function removes the interval date if a Begin or End Dates are chosen by the user
$( function() 
{
  $("input").keydown(function(){
      $(this).css("background-color", "yellow");
  });
  
  //$("input").change(function()
  $("input").keyup(function()
  {
    // Look for Interval Date
    this_parent = $(this).parent().parent() ;
    $(this).css("background-color", "pink");
    value = $(this).val() ;
    attrib = $(this).attr("cat") ;
    
    //alert(attrib) ;
    
    this_parent.find('td').each (function() 
    {
      // do your cool stuff
      if($(this).hasClass(attrib))
      {
        $(this).css("background-color", "pink");
        $(this).html("> " + value);
      }
    }); 
    
    //alert(value) ;
    //this_parent.find(".date_range").val("");
  }) ;

}) ; // document.ready

// This function saves the entire page of settings
// Sends all of the info as JSON through AJAX
$( function() 
{
  
  $(".btn_save_settings").click(function()
  {
    // Confirm Saving Settings
    //if (! confirm("Do you want to save your Settings?"))
      //return false ;
    
    //For all settings use the Info variable
    var info = '' ;
    
    // Get the company information and save it as JSON
    var info_company = '' ;
    
    // Company name
    company_name = $("#company_name").val() ;
    info_company += '{"company_name":"' + company_name + '",' ;
    
    city = $("#city").val() ;
    info_company += '"city":"' + city + '",' ;
    
    timezone = $("#timezone").val() ;
    //timezoneid = $("#timezone").attr('timezoneid') ;
    timezoneid = $("#timezone").find(':selected').attr('timezoneid') ;
    info_company += '"timezones":[' ;
    info_company += '"' + timezone + '"' ;
    info_company += ',"' + timezoneid + '"' ;
    info_company += '],' ; 
    
    alarm_email = $("#alarm_email").val() ;
    info_company += '"alarm_email":"' + alarm_email + '",' ;
    address = $("#address").val() ;
    info_company += '"address":"' + address + '"' ;
    //alert(timezoneid) ;
    //return false ;
    info_company += '}' ;
    
    //info += '{"company":[' + info_company + ']}' ;
    info += '{"company":' + info_company + ',' ;
    //alert(info) ;
    //return false ;
    
    // Go through the AQI Data and save it as JSON
    // JSON empty string
    //var info = '{"aqi":[' ;
    var info_aqi = '' ;
    
    $("#aqi tr").each(function()
    {
      // Now check if this TR has a chemical attributes
      chemical = $(this).attr('chemical') ;
      if(chemical)
      {
        info_aqi = addCommaToEndOfNonEmptyString(info_aqi) ;
        info_aqi += '{"chemical":"' + chemical + '",' ;
        
        info_aqi += '"parameters":[' ;
        
        //alert(chemical) ;
        //get the time average for this chemical
        time_avg = $(this).find(".time_average").val() ;
        //alert(time_avg) ;
        
        info_aqi += '"' + time_avg + '"';
        
        //get the data unit for this chemical
        data_unit = $(this).find(".data_unit").val() ;
        //alert(data_unit) ;
        
        info_aqi += ',"' + data_unit + '"';
        
        $(this).find('input[type="number"]').each(function () 
        {
          concentration = $(this).val() ;
          category = $(this).attr('cat') ;
          //alert(category + ': ' + concentration) ;
          info_aqi += ',"' + concentration + '"' ;
          
        });
        
        info_aqi += ']}' ;
      }
      
    }) ;
    
    //info += '{"aqi":[' + info_aqi + ']}' ;
    info += '"aqi":[' + info_aqi + ']}' ;
    
    //alert (info) ;
    //return false ;
  
    // ----------------------------------------------------------------------------------------------
    // Save All Settings through AJAX
    $.ajax({
        type: 'GET',
        url: "/includes/php/ajax/save_settings.php",             
        dataType: "html",   //expect html to be returned   
        data: {info: info, save:true},
        success: function(response)
        {
          // If the response has content it means the Login failed
          if (response.length == 0)
          {
            // Give an Alert for now before making it fancier looking!
            alert_script = '<script>alert("Settings have just been updated!") ;</script>' ;
            $("#settings_container").html(alert_script); // Overview page for now
          }
          else
          {
            // Diagnose the error from Back-End if Settings could not be saved
            $("#settings_container").html(response);
          }
        }
    });
  
  }) ;
  
}) ; // document.ready


// Adds comma to non-empty string
function addCommaToEndOfNonEmptyString(info)
{
    // For a string to pass to JSON add the comma before adding any new parameter
    if (info.length > 0)
      info = info + ',' ;
    return info ;
} // addCommaToEndOfNonEmptyString