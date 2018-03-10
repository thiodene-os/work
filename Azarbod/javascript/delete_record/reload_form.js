    <script type="text/javascript">
      $(function()
      {
        $(".btn_att_doc_delete").click(function()
        {
          if (confirm("Delete this document?"))
          {
            prog = "\n $do_record = new doRecord(\"ATTACHED_DOC\") ;"
                 + "\n $do_record -> id_column_val = " + $(this).attr("att_doc_id") + " ;"
                 + "\n $do_record -> deleteRecords() ;"
                 + "unset($do_record) ;" ;
            callback = "refreshForm('#cur_form_name#','#cur_page_id#','') ;" ;     
            runBackEndProg(prog,null,callback) ;  
          }  
          return false ;  
        }) ;
      }) ; // document.ready
    </script>
