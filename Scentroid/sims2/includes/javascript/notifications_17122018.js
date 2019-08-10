// Click on Notifications Tabs to have their content shown
// Should redirect to a phyical page for each of the tabs
$(function()
{
  // Click on a page inactive to display
  $('.tabs').click(function() {
    
    if (!$(this).hasClass('active'))
    {
      // Remove all the classes active first
      $('.active').each(function () 
      {
        $(this).removeClass('active');
      }) ;
      
      $(this).addClass('active');
      topic = $(this).attr("href") ;
      //alert(topic) ;
      
      // Display the table corresponding to the topic
    
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