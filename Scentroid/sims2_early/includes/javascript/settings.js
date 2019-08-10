// Click on Table pages and display
$(function()
{
  // Click on a page inactive to display
  $('.pages').click(function() {
    
    if ($(this).hasClass('page_inactive'))
    {
      //alert("OK") ;
      //return false ;
      // Remove all the classes active first
      $('.page_active').each(function () 
      {
        $(this).removeClass('page_active');
        $(this).addClass('page_inactive');
      }) ;
      
      $(this).removeClass('page_inactive');
      $(this).addClass('page_active');
      
      $('#' + $(this).attr('rel')).removeClass('page_inactive') ;
      $('#' + $(this).attr('rel')).addClass('page_active') ;
    
    }
    return false;
  });

}) ; // Print dialog

// Click on Table pages and display Company section: 'c'
$(function()
{
  // Click on a page inactive to display
  $('.cpages').click(function() {
    
    if ($(this).hasClass('cpage_inactive'))
    {
      //alert("OK") ;
      //return false ;
      // Remove all the classes active first
      $('.cpage_active').each(function () 
      {
        $(this).removeClass('cpage_active');
        $(this).addClass('cpage_inactive');
      }) ;
      
      $(this).removeClass('cpage_inactive');
      $(this).addClass('cpage_active');
      
      $('#' + $(this).attr('rel')).removeClass('cpage_inactive') ;
      $('#' + $(this).attr('rel')).addClass('cpage_active') ;
    
    }
    return false;
  });

}) ; // Print dialog

// Click on Table pages and display AQI section: 'a'
$(function()
{
  // Click on a page inactive to display
  $('.apages').click(function() {
    
    if ($(this).hasClass('apage_inactive'))
    {
      //alert("OK") ;
      //return false ;
      // Remove all the classes active first
      $('.apage_active').each(function () 
      {
        $(this).removeClass('apage_active');
        $(this).addClass('apage_inactive');
      }) ;
      
      $(this).removeClass('apage_inactive');
      $(this).addClass('apage_active');
      
      $('#' + $(this).attr('rel')).removeClass('apage_inactive') ;
      $('#' + $(this).attr('rel')).addClass('apage_active') ;
    
    }
    return false;
  });

}) ; // Print dialog

// Click on Table pages and display Notification section: 'n'
$(function()
{
  // Click on a page inactive to display
  $('.npages').click(function() {
    
    if ($(this).hasClass('npage_inactive'))
    {
      //alert("OK") ;
      //return false ;
      // Remove all the classes active first
      $('.npage_active').each(function () 
      {
        $(this).removeClass('npage_active');
        $(this).addClass('npage_inactive');
      }) ;
      
      $(this).removeClass('npage_inactive');
      $(this).addClass('npage_active');
      
      $('#' + $(this).attr('rel')).removeClass('npage_inactive') ;
      $('#' + $(this).attr('rel')).addClass('npage_active') ;
    
    }
    return false;
  });

}) ; // Print dialog

// Popup Dialog page top show sensor list
$(function()
{
  // When user clicks on the print schedule, then show the dialog box
  // where he/she can select different options
  $(".view_sensors").click(function()
  {
    this_parent = $(this).parent().parent() ;
    // get id of the user
    equipment_id = this_parent.attr("equipment_id");
    equipment_name = this_parent.attr("equipment_name");
    
    // Update sensor table through AJAX
    $.ajax({
        type: 'GET',
        url: "/includes/php/ajax/show_sensor_popup.php",             
        dataType: "html",   //expect html to be returned   
        data: {equipment_id: equipment_id, show_sensors:true},
        success: function(response)
        {
          
          // Open the dialog
          prn_dlg = 
              '<div id="dlg_show_sensors">'
              // Link to print current sheet
              + '<h3>Equipment: ' + equipment_name + '</h3>'
              + '<div style="height:400px; overflow:auto;">'
                  + '<table class="scroll settings_table">'
                    + '<thead>'
                    + '<tr><th>Name</th><th>Packet ID</th><th>Type</th><th>Data Unit</th><th>Actions</th></tr>'
                    + '</thead>'
                    + '<tbody>'
                    + response
                    + '</tbody>'
                  + '</table>'
              + '</div>'
            + '</div>' ;
        

        
          // Create the dialog
          $(prn_dlg).dialog(
          {
              modal: true
            , title: 'Sensors List for Equipement ' + equipment_name
            , width: '40em' 
            ,close : function(event , ui)
            {
              // Make sure to remove the div or the calendar may not work
              $(".ui-dialog").remove() ;
              $("#dlg_show_sensors").dialog("destroy").remove() ;
            }
          }) ;
          
        }
    });
    
  }) ;
}) ; // Print dialog

// This functionality is used to Edit Equipments
$( function() 
{
  
  $(".edit_equipment").click(function()
  {
    
    this_parent = $(this).parent().parent() ;
    // get id of the user
    user_id = this_parent.attr("equipment_id");
    // Display the corresponding page that has to be reached on click
    var page_url = window.location.href ;

    // create an array of the current URL considering being located in the settings page
    var url_array = page_url.split ("/settings") ;
    // redirect page for editing user
    var new_page_url = url_array[0] + '/equipment/edit_equipment.php?id=' + user_id ;

    // Redirect to new users page
    window.location.href = new_page_url ;
    
  });
  
  
  $(".delete_equipment").click(function()
  {
    // Confirm Deleting an equipment
    if (! confirm("Do you want to delete this Equipment?"))
      return false ;
    
    this_parent = $(this).parent().parent() ;
    
    // get id of the user
    equipment_id = this_parent.attr("equipment_id");
    
    // Make an AJAX delete
    // ----------------------------------------------------------------------------------------------
    // Update user info through AJAX
    $.ajax({
        type: 'GET',
        url: "/includes/php/ajax/update_equipments.php",             
        dataType: "html",   //expect html to be returned   
        data: {equipment_id: equipment_id, delete:true},
        success: function(response)
        {
          // If the response has content it means the Login failed
          if (response.length == 0)
          {
            // Give an Alert for now before making it fancier looking!
            // Remove the row upon success
            this_parent.remove() ;
            alert_script = '<script>alert("One Equipment has just been deleted!") ;</script>' ;
            
            $("#settings_equipment_container").html(alert_script); // Overview page for now
          }
          else
          {
            // Diagnose the error from Back-End if Settings could not be saved
            alert_script = '<script>alert("' + response + '") ;</script>' ;
            $("#settings_equipment_container").html(alert_script);
          }
        }
    });
    
  });
  
  
  $(".add_equipment").click(function()
  {
    
    this_parent = $(this).parent().parent() ;
    // Display the corresponding page that has to be reached on click
    var page_url = window.location.href ;
    
    // create an array of the current URL considering being located in the settings page
    var url_array = page_url.split ("/settings") ;
    // redirect page for editing equipment
    var new_page_url = url_array[0] + '/equipment/add_equipment.php' ;
    
    // Redirect to new equipment page
    window.location.href = new_page_url ;
    
  });
  
}) ;


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
          // If the response has content it means the query failed
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
    company_id = $("#company_id").val() ;
    info_company += '{"company_id":"' + company_id + '",' ;
    info_company += '"company_name":"' + company_name + '",' ;
    
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
    
    $("#aqi tbody tr").each(function()
    {
      // Now check if this TR has a sensor_id attributes
      sensor_id = $(this).attr('sensor_id') ;
      //sensor_name = $(this).attr('sensor_name') ;
      //alert(sensor_name) ;
      //return false ;
      if(sensor_id)
      {
        info_aqi = addCommaToEndOfNonEmptyString(info_aqi) ;
        info_aqi += '{"sensor_id":"' + sensor_id + '",' ;
        //info_aqi += '"sensor_name":"' + sensor_name + '",' ;
        
        info_aqi += '"parameters":[' ;
        
        //alert(sensor_id) ;
        //get the time average for this sensor_id
        time_avg = $(this).find(".time_average").val() ;
        //alert(time_avg) ;
        
        info_aqi += '"' + time_avg + '"';
        
        //get the data unit for this sensor_id
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
    info += '"aqi":[' + info_aqi + '],' ;
    //alert(info) ;
    //return false ;
    
    // Go through the AQI Data and save it as JSON
    // JSON empty string
    var info_alarm_sensor = '' ;
    
    $("#alarm_sensor tbody tr").each(function()
    {
      
      // Now check if this TR has a sensor_id
      sensor_id = $(this).attr('sensor_id') ;
      //sensor_name = $(this).attr('sensor_name') ;
      //alert(sensor_name) ;
      //return false ;
      if(sensor_id)
      {
        info_alarm_sensor = addCommaToEndOfNonEmptyString(info_alarm_sensor) ;
        info_alarm_sensor += '{"sensor_id":"' + sensor_id + '",' ;
        //info_alarm_sensor += '"sensor_name":"' + sensor_name + '",' ;
        
        info_alarm_sensor += '"low_high_value":[' ;
        
        low_point = $(this).find(".low_point").val() ;
        high_point = $(this).find(".high_point").val() ;
        //alert(time_avg) ;
        
        info_alarm_sensor += '"' + low_point + '",';
        info_alarm_sensor += '"' + high_point + '"';
        
        info_alarm_sensor += '],' ;
        
        data_unit = $(this).find(".data_unit").val() ;
        info_alarm_sensor += '"data_unit":' ;
        info_alarm_sensor += '"' + data_unit + '",';
        
        frequency = $(this).find(".frequency").val() ;
        info_alarm_sensor += '"frequency":' ;
        info_alarm_sensor += '"' + frequency + '",';
        
        time_range = $(this).find(".time_range").val() ;
        info_alarm_sensor += '"time_range":' ;
        info_alarm_sensor += '"' + time_range + '",';
        
        //if ($(this).find(".enabled").checked != true)
          //enabled = '' ;
        //else
          //enabled = 'yes' ;
        
        is_enabled = $(this).find(".enabled").is(":checked");
        if (is_enabled)
          enabled = 'yes' ;
        else 
          enabled = '' ;
        
        info_alarm_sensor += '"enabled":' ;
        info_alarm_sensor += '"' + enabled + '"';
        
        info_alarm_sensor += '}' ;
      }
    }) ;
    
    info += '"alarm_sensor":[' + info_alarm_sensor + ']}' ;
    
    //alert (info) ;
    //return false ;
    
    //var info_test = '{"aqi":"test"}' ;
    
  
    // ----------------------------------------------------------------------------------------------
    // Save All Settings through AJAX
    $.ajax({
        type: 'POST',
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