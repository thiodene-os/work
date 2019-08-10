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

// Click on Notifications Tabs to have their content shown
// Should redirect to a phyical page for each of the tabs
$(function()
{
  // Click on a page inactive to display
  $('.tabs').click(function() {
    
    if (!$(this).hasClass('active'))
    {
      topic = $(this).attr("href") ;
      
      // Display the corresponding page that has to be reached on click
      var page_url = window.location.href ;
      
      var after_index_pos = page_url.indexOf("?");
      var after_index_url = '' ;
      if (after_index_pos > 0)
      {
        var after_index_array = page_url.split ("?") ;
        after_index_url = after_index_array[1] ;
        if (after_index_url.length > 0)
          after_index_url = '?' + after_index_url ;
      }
      
      // create an array of the current URL considering being located in the settings page
      var url_array = page_url.split ("/notifications") ;
      var new_page_url = '' ;
      // redirect page for editing user
      if (topic == '#activity')
        new_page_url = url_array[0] + '/notifications' + '/index.php' + after_index_url ;
      else if (topic == '#health')
        new_page_url = url_array[0] + '/notifications/health' + '/index.php' + after_index_url ;
      else if (topic == '#alarm')
        new_page_url = url_array[0] + '/notifications/alarms' + '/index.php' + after_index_url ;
      else if (topic == '#log')
        new_page_url = url_array[0] + '/notifications/logs' + '/index.php' + after_index_url ;
      else
        new_page_url = url_array[0] + '/notifications' + '/index.php' + after_index_url ;

      // Redirect to new users page
      window.location.href = new_page_url ;
    
    }
    return false;
  });

}) ; // Print dialog


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