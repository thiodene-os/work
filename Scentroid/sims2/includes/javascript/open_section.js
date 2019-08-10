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