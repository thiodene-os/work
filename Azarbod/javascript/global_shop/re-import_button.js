    <script type="text/javascript">
      $(function()
      {
        // This function recalculates timing for each work order operation
        // And then reload the page
        $(document).on("click","#btn_re_import_product",function()
        {
          if (! confirm("Re-import this product from GS?"))
            return false ;
          
          prog = 'importSynchOneProductGS(\'%prod_sku%\',\'%cat_name%\',' + %amt_price% + ') ;' ;
          
          runBackEndProg(prog,null,'location.reload() ;') ;
          return false ;            
        }) ;
        
      }) ;
    </script>
    
    # HTML part
    <button id="btn_re_import_product" class="button">Re-import</button>
