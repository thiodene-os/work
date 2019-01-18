$(function()
{
  // **************** Option Select ******************
  // When user clicks on an option, turn it green and also add a check mark besides it
  // and remove the checkbox from all other options
  $(document).on("click",".option.selectable",function()
  {
    // If it is actually selected then de-select it on click
    if ($(this).hasClass("selected"))
    {
      $(this).removeClass("selected") ;
      $(this).find(".check_plot").hide() ; // Remove checkboxes if any
    }
    else
    {
      // Find the parent and remove selected class from all its child options
      this_parent = $(this).parent() ;
      this_parent.find(".option").removeClass("selected") ;
      this_parent.find(".check_plot").show() ;
      $(this).addClass("selected") ;
    }
  }) ;
}) ; // document.ready