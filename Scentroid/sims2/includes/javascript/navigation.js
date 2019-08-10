// This functionality is used to Edit a COMPANY
$( function() 
{
  // For Editing or Adding a new equipment
  $(".btn_save_company").click(function()
  {
    //For all settings use the Info variable
    var info = '' ;
    
    // Get the company information and save it as JSON
    var info_company = '' ;
    
    // Equipment where the sensor goes to
    company_id = $(this).attr("company_id") ;
    if (company_id)
      info_company += '{"company_id":"' + company_id + '",' ;
    else
      info_company += '{"company_id":"",' ;
    
    //info_company += '{"company_id":"' + company_id + '",' ;
    
    // Get the name
    name = $("#name").val() ;
    if (name == "")
    {
      alert("Please enter a valid Company Name!") ;
      return false ;
    }
    info_company += '"name":"' + name + '",' ;
    
    // Get the city
    city = $("#city").val() ;
    info_company += '"city":"' + city + '",' ;
    
    // Get the timezone
    timezone = $("#timezone").val() ;
    info_company += '"timezone":"' + timezone + '",' ;
    
    // Get the alarm_email
    alarm_email = $("#alarm_email").val() ;
    if (!validateEmail(alarm_email))
      alarm_email = '' ;
    info_company += '"alarm_email":"' + alarm_email + '",' ;
    
    // Get the Manager (None for the moment so -1)
    var manager = -1 ;
    info_company += '"manager":"' + manager + '",' ;
    
    // Get the address
    address = $("#address").val() ;
    info_company += '"address":"' + address + '",' ;
    
    // Get the Phone
    tel = $("#tel").val() ;
    info_company += '"tel":"' + tel + '",' ;
    // Get the logo
    logo = $("#logo_url").val() ;
    info_company += '"logo":"' + logo + '",' ;
    
    // Get the extra for categories
    extra = $("#extra").val() ;
    extra_array = false ;
    if (extra.length > 1)
    {
      info_company += '"extra":[' ;
      info_extra = '' ;
      extra_array = extra.split(/\r*\n\r*/);
      for(var i=0;i<extra_array.length;i++)
      {
        if (info_extra.length > 0)
          info_extra += ',"' + extra_array[i] + '"' ;
        else
          info_extra += '"' + extra_array[i] + '"' ;
      }
      info_company += info_extra + ']' ;
    }
    else
      info_company += '"extra":[""]' ;
    
    //info_company += '"extra":"' + extra + '"' ;
    
    info_company += '}' ;
    
    info = info_company ;
    
    //alert(info) ;
    //alert("OK") ;
    //return false ;
    
    
    // ----------------------------------------------------------------------------------------------
    // Update Equipment info through AJAX
    $.ajax({
        type: 'GET',
        url: "/includes/php/ajax/update_company.php",             
        dataType: "html",   //expect html to be returned   
        data: {info: info, update:true},
        success: function(response)
        {
          // If the response has content it means the query failed
          if (response.length == 0)
          {
            // Give an Alert for now before making it fancier looking!
            if (company_id)
              alert_script = '<script>alert("Company info has just been updated!") ;</script>' ;
            else
              alert_script = '<script>alert("A new Company has been added successfully!") ;</script>' ;
            
            $("#company_container").html(alert_script); // Overview page for now
          }
          else
          {
            // Diagnose the error from Back-End if Settings could not be saved
            $("#company_container").html(response);
          }
        }
    });
    
    
  });
  
}) ;


// This functionality is used to Edit a SENSOR
$( function() 
{
  // For Editing or Adding a new equipment
  $(".btn_save_sensor").click(function()
  {
    //For all settings use the Info variable
    var info = '' ;
    
    // Get the company information and save it as JSON
    var info_sensor = '' ;
    
    // Equipment where the sensor goes to
    equipment_id = $(this).attr("equipment_id") ;
    info_sensor += '{"equipment_id":"' + equipment_id + '",' ;
    // Get the sensor ID if any (if not that's an INSERT)
    sensor_id = $(this).attr("sensor_id") ;
    if (sensor_id)
    {
      // Save the sensor's new information
      //alert("Save: " + sensor_id) ;
      info_sensor += '"sensor_id":"' + sensor_id + '",' ;
    }
    else
    {
      // Add the new sensor
      //alert("Add: OK") ;
      info_sensor += '"sensor_id":"",' ;
    }
    
    // Get the name
    name = $("#name").val() ;
    if (name == "")
    {
      alert("Please enter a valid Name!") ;
      return false ;
    }
    info_sensor += '"name":"' + name + '",' ;
    
    // Get the Packet ID
    packet_id = $("#packet_id").val() ;
    if (packet_id.length < 3)
    {
      alert("Please enter a valid Packet ID! At least 3 Characters") ;
      return false ;
    }
    info_sensor += '"packet_id":"' + packet_id + '",' ;
    
    // Get the Data Unit
    dataunit = $(".data_unit").val() ;
    info_sensor += '"dataunit":"' + dataunit + '",' ;
    
    // Get the Type
    type = $("#type").val() ;
    info_sensor += '"type":"' + type + '",' ;
    
    // Get the Alarm values
    alarm_max_value = $("#alarm_max_value").val() ;
    info_sensor += '"alarm_max_value":"' + alarm_max_value + '",' ;
    
    alarm_value_total = $("#alarm_value_total").val() ;
    info_sensor += '"alarm_value_total":"' + alarm_value_total + '",' ;
    
    alarm_value_num = $("#alarm_value_num").val() ;
    info_sensor += '"alarm_value_num":"' + alarm_value_num + '",' ;
    
    // Calibration values
    zero_offset = $("#zero_offset").val() ;
    info_sensor += '"zero_offset":"' + zero_offset + '",' ;
    
    sensitivity = $("#sensitivity").val() ;
    info_sensor += '"sensitivity":"' + sensitivity + '",' ;
    
    max_sensitivity = $("#max_sensitivity").val() ;
    info_sensor += '"max_sensitivity":"' + max_sensitivity + '",' ;
    
    min_sensitivity = $("#min_sensitivity").val() ;
    info_sensor += '"min_sensitivity":"' + min_sensitivity + '",' ;
    
    // Relay Trigger values
    relay_trigger_limit = $("#relay_trigger_limit").val() ;
    info_sensor += '"relay_trigger_limit":"' + relay_trigger_limit + '",' ;
    
    relay_trigger_comparison = $("#relay_trigger_comparison").val() ;
    info_sensor += '"relay_trigger_comparison":"' + relay_trigger_comparison + '",' ;
    
    relay_number = $("#relay_number").val() ;
    info_sensor += '"relay_number":"' + relay_number + '"' ;
    
    info_sensor += '}' ;
    
    info = info_sensor ;
    
    //alert(info) ;
    //alert("OK") ;
    //return false ;
    
    
    // ----------------------------------------------------------------------------------------------
    // Update Equipment info through AJAX
    $.ajax({
        type: 'GET',
        url: "/includes/php/ajax/update_sensors.php",             
        dataType: "html",   //expect html to be returned   
        data: {info: info, update:true},
        success: function(response)
        {
          // If the response has content it means the query failed
          if (response.length == 0)
          {
            // Give an Alert for now before making it fancier looking!
            if (sensor_id)
              alert_script = '<script>alert("Sensor info has just been updated!") ;</script>' ;
            else
              alert_script = '<script>alert("A new Sensor has been added successfully!") ;</script>' ;
            
            $("#sensor_container").html(alert_script); // Overview page for now
          }
          else
          {
            // Diagnose the error from Back-End if Settings could not be saved
            $("#sensor_container").html(response);
          }
        }
    });
    
    
  });
  
}) ;

// This functionality is used to Edit an EQUIPMENT
$( function() 
{
  // For Editing or Adding a new equipment
  $(".btn_save_equipment").click(function()
  {
    //For all settings use the Info variable
    var info = '' ;
    
    // Get the company information and save it as JSON
    var info_equipment = '' ;
    
    // Get the equipment ID if any (if not that's an INSERT)
    equipment_id = $(this).attr("equipment_id") ;
    if (equipment_id)
    {
      // Save the equipment's new information
      //alert("Save: " + equipment_id) ;
      info_equipment += '{"equipment_id":"' + equipment_id + '",' ;
    }
    else
    {
      // Add the new equipment
      //alert("Add: OK") ;
      info_equipment += '{"equipment_id":"",' ;
    }
    
    // Get the name
    name = $("#name").val() ;
    if (name == "")
    {
      alert("Please enter a valid Name!") ;
      return false ;
    }
    info_equipment += '"name":"' + name + '",' ;
    
    // Get the Serial Number
    sn = $("#sn").val() ;
    if (sn.length < 6)
    {
      alert('Please enter a valid Serial Number (at least 6 characters)') ;
      return false;
    }
    info_equipment += '"sn":"' + sn + '",' ;
    
    // Get the status
    status = $("input[name='status']:checked").val();
    info_equipment += '"status":"' + status + '",' ;
    
    // Get the calibrate_date
    calibrate_date = $("#calibrate_date").val() ;
    info_equipment += '"calibrate_date":"' + calibrate_date + '",' ;
    
    // Get the company
    company_id = $("#company").val() ;
    info_equipment += '"company_id":"' + company_id + '"' ;
    
    info_equipment += '}' ;
    
    info = info_equipment ;
    
    //alert(info) ;
    //return false ;
    
    // ----------------------------------------------------------------------------------------------
    // Update Equipment info through AJAX
    $.ajax({
        type: 'GET',
        url: "/includes/php/ajax/update_equipments.php",             
        dataType: "html",   //expect html to be returned   
        data: {info: info, update:true},
        success: function(response)
        {
          // If the response has content it means the Query failed
          if (response.length == 0)
          {
            // Give an Alert for now before making it fancier looking!
            if (equipment_id)
              alert_script = '<script>alert("Equipment info has just been updated!") ;</script>' ;
            else
              alert_script = '<script>alert("A new Equipment has been added successfully!") ;</script>' ;
            
            $("#equipment_container").html(alert_script); // Overview page for now
          }
          else
          {
            // Diagnose the error from Back-End if Settings could not be saved
            $("#equipment_container").html(response);
          }
        }
    });
    
  });
  
}) ;

// This functionality is used to Edit or Delete a USER
$( function() 
{
  // For Editing or Adding a new User
  $(".btn_save_user").click(function()
  {
    
    //For all settings use the Info variable
    var info = '' ;
    
    // Get the company information and save it as JSON
    var info_user = '' ;
    
    // Get the user ID if any (if not that's an INSERT)
    user_id = $(this).attr("user_id") ;
    if (user_id)
    {
      // Save the user's new information
      //alert("Save: " + user_id) ;
      info_user += '{"user_id":"' + user_id + '",' ;
    }
    else
    {
      // Add the new user
      //alert("Add: OK") ;
      info_user += '{"user_id":"",' ;
    }
    
    // Get the name
    name = $("#name").val() ;
    if (name == "")
    {
      alert("Please enter a valid Name!") ;
      return false ;
    }
    info_user += '"name":"' + name + '",' ;
    
    // Get the family
    family = $("#family").val() ;
    family_list = $('#family_list').val() ;
    if (family_list)
      info_user += '"family":"' + family_list + '",' ;
    else
      info_user += '"family":"' + family + '",' ;
    
    // Get the gender (useful?)
    //gender = $("input[name='gender']:checked").val();
    //info_user += '"gender":"' + gender + '",' ;
    
    // Get the telephone
    phone = $("#phone").val() ;
    info_user += '"phone":"' + phone + '",' ;
    
    // Get the email
    email = $("#email").val() ;
    if (!validateEmail(email))
    {
      alert('Please enter a valid Email address') ;
      return false;
    }
    info_user += '"email":"' + email + '",' ;
    
    // Get the password
    pwd = $("#password").val() ;
    if (pwd.length < 8 && !user_id)
    {
      alert('Please enter a valid Password (at least 8 characters)') ;
      return false;
    }
    info_user += '"password":"' + pwd + '",' ;
    
    // Get the company
    company_id = $("#company").val() ;
    info_user += '"company_id":"' + company_id + '",' ;
    
    // Get the role
    role = $("#role").val() ;
    info_user += '"role":"' + role + '"' ;
    
    
    info_user += '}' ;
    
    info = info_user ;
    
    //alert(info_user) ;
    //return false ;
    
    // ----------------------------------------------------------------------------------------------
    // Update user info through AJAX
    $.ajax({
        type: 'GET',
        url: "/includes/php/ajax/update_users.php",             
        dataType: "html",   //expect html to be returned   
        data: {info: info, update:true},
        success: function(response)
        {
          // If the response has content it means the Query failed
          if (response.length == 0)
          {
            // Give an Alert for now before making it fancier looking!
            if (user_id)
              alert_script = '<script>alert("User info has just been updated!") ;</script>' ;
            else
              alert_script = '<script>alert("A new User has been added successfully!") ;</script>' ;
            
            $("#user_container").html(alert_script); // Overview page for now
          }
          else
          {
            // Diagnose the error from Back-End if Settings could not be saved
            $("#user_container").html(response);
          }
        }
    });
    
  });
  
}) ;


// Adds comma to non-empty string
function addCommaToEndOfNonEmptyString(info)
{
    // For a string to pass to JSON add the comma before adding any new parameter
    if (info.length > 0)
      info = info + ',' ;
    return info ;
} // addCommaToEndOfNonEmptyString


// In case email Addresses are accepted too!
function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}