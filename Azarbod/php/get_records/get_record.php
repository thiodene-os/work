<?php

// If that matrix has an image show it
$sql_str = "SELECT ATTACHED_DOC.* FROM ATTACHED_DOC 
              WHERE RELATED_REC_TYPE = " . ATT_DOC_RELATED_REC_PROD_MATRIX
              . " AND FILE_TYPE = " . ATTACHED_DOC_FILE_TYPE_IMAGE
                . " AND LNK_RELATED_REC = " . $prod_matrix_rec['MATRIX_ID'] ;
$qry = new dbQuery($sql_str,"File: " . __FILE__ . " LINE " . __LINE__) ;
$att_doc_rec = $qry -> getRecord() ;
$att_doc_rec_count = $qry -> getCount() ;
unset($qry) ;

if ($att_doc_rec_count > 0)
{

}


?>
