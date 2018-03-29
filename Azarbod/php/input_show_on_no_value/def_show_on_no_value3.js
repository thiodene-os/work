<script type="text/javascript">
  $(function()
  {

    $(".btn_add_special_product_to_po").click(function()
    {
      if (! confirm("Add this product to the selected PO?"))
        return false ;

      // Get the ID of the element before removing the row!
      parent_wrap = $(this).parent().parent() ;

      // Liquor Product ID
      if (parent_wrap.attr("gn_prod_id").length == 0)
      {
        xmlbWarn("Product not related to Liquor Product.") ; 
        return false ;
      }
      else
      {
        gn_prod_id = parent_wrap.attr("gn_prod_id") ;
      }

      // Quantity of product
      if (parent_wrap.find(".special_item_qty").val().length == 0)
      {
        xmlbWarn("Please enter a valid qty for this product.") ; 
        return false ;
      }
      else
      {
        qty = parseFloat(parent_wrap.find(".special_item_qty").val()) ;
        if (qty <= 0)
        {
          xmlbWarn("Please enter a valid qty for this product.") ; 
          return false ;
        }
      }

      po_id = $.trim(parent_wrap.find(".po_id").val()) ;
      if (po_id == "~def_show_on_no_value~")
      {
        alert('Please Select A PO in preparation!') ;
        return false ;
      }
      prog = 'addSpecialProductToPO(' + po_id + ', ' + gn_prod_id + ', ' + qty + ') ;' ;
      runBackEndProg(prog,null,'location.reload() ;') ;
      return false ;

    }); // Save rows

  }) ; // document.ready
</script>  
