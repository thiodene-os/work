$(function()
{
  // Handle the user information, saves the Itinerary ino a JSON string
  $(".btn-success").click(function()
  {
    var zero_offset = "ZERO_OFFSET_VOLTAGE_KEY_NAME" ;
    var sensor_sens = "SENSOR_SENSITIVITY_KEY_NAME" ;
    var min_sens = "MINIMUM_SENSITIVITY_RANGE_KEY_NAME" ;
    var max_sens = "MAXIMUM_SENSITIVITY_RANGE_KEY_NAME" ;
    var val_status = true ;
    
    // On-click of the Save button verify the form control fields
    $(".form-control").each(function()
    {
      name = $(this).attr("name") ;
      value = Number($(this).val()) ;
      //alert (value) ;
      //alert(value.length) ;
      
      if (name.indexOf(zero_offset) >= 0)
      {
        if (isNaN(value))
        {
          alert("The Zero Offset Voltage should be a number! ") ;
          val_status = false ;
          return false;
        }
      }
      else if (name.indexOf(sensor_sens) >= 0)
      {
        if (isNaN(value))
        {
          alert("The Sensor Sensitivity should be a number! ") ;
          val_status = false ;
          return false;
        }
      }
      else if (name.indexOf(min_sens) >= 0)
      {
        if (isNaN(value))
        {
          alert("The Minimum Sensitivity Range should be a number! ") ;
          val_status = false ;
          return false;
        }
      }
      else if (name.indexOf(max_sens) >= 0)
      {
        if (isNaN(value))
        {
          alert("The Maximum Sensitivity Range should be a number! ") ;
          val_status = false ;
          return false;
        }
      }
    }) ;

    if (!val_status)
      return false ;

  });

}) ;