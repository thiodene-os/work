
// Handles the display of the loader once the UPDATE button is pressed
$(function()
{
  // Handle the user information, saves the Itinerary ino a JSON string
  $(".button_update").click(function()
  {
	
	  // At the end there may be other elements who depend on it, so run the show_on ones
	  $(".loader").show() ;
  }) ;
}) ; // document.ready


// Check if both the Date intervals (Begin and End) are selected or none of them
$(function()
{
  // Handle the user information, saves the Itinerary into a JSON string
  $(".button_update").click(function()
  {
	  
    // Look for Begin and End Dates
    this_parent = $(this).parent() ;
    begin_date = this_parent.find("#begin_date").val();
    end_date = this_parent.find("#end_date").val();
    
    // If Begin Date is empty
    if (begin_date.length == 0 && end_date.length != 0)
    {
      alert("Please provide a Begin Date!") ;
      $(".loader").hide() ;
      return false ;
    }
    
    // If End date is empty
    if (begin_date.length != 0 && end_date.length == 0)
    {
      alert("Please provide and End Date!") ;
      $(".loader").hide() ;
      return false ;
    }
    
    // If both dates are provided but the start date is > to the End date -> Error Message
    if (end_date < begin_date)
    {
      alert("The Begin Date must be prior to the End Date!") ;
      $(".loader").hide() ;
      return false ;
    }
    
  }) ;
}) ; // document.ready