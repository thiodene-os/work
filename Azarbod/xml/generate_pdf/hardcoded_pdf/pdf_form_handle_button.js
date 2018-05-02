<script type="text/javascript">

  $(function()
  {
    // Changes the selection of Printing Options
    $(".btn_print_pdf").click(function()
    {
      // Confirm the printout of this itinerary
      if (! confirm("Do you want to print the current stage of this Itinerary?"))
        return false ;

        href = "%print_page_path%?action=itinerary&itin_id=%itin_id%";
        location.href = href ; // Download the file
        return false ;
    }) ;

  }) ; // document.ready

</script>
