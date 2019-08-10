$(function()
{
  // Handle the user information, saves the Itinerary ino a JSON string
  $(".button_update").click(function()
  {
	
	  // At the end there may be other elements who depend on it, so run the show_on ones
	  $(".loader").show() ;
  }) ;
}) ; // document.ready