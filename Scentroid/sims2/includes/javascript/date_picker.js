$(function()
{
  
  var d = new Date();
  $(".input_date").datepicker(
  {
    showOn: 'button'
    ,buttonImage: '/images/date_inpt.jpg'
    ,buttonImageOnly: true
    ,dateFormat: 'yy-mm-dd ' + (d.getHours()<10?'0':'') + d.getHours() + ':' + (d.getMinutes()<10?'0':'') + d.getMinutes() + ':' + (d.getSeconds()<10?'0':'') + d.getSeconds()
    ,changeMonth: true
    ,changeYear: true
  }) ;         

}) ; // document.ready

// This function removes the interval date if a Begin or End Dates are chosen by the user
$( function() 
{
  $(".input_date").change(function()
  {
    // Look for Interval Date
    this_parent = $(this).parent() ;
    this_parent.find(".date_range").val("");
  }) ;

}) ; // document.ready

// This function removes the Begin and End Dates if an Interval Date has been picked
$( function() 
{
  $(".date_range").change(function()
  {
    // Look for Begin and End Dates
    this_parent = $(this).parent() ;
    this_parent.find("#begin_date").val("");
    this_parent.find("#end_date").val("");
  }) ;

}) ; // document.ready