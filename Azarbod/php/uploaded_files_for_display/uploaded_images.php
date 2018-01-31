<?php

# How to get images for display from Database to Image Folder

// look up the page range for each section
$sql_str = "SELECT ATTACHED_DOC.* FROM ATTACHED_DOC 
              WHERE RELATED_REC_TYPE = " . ATT_DOC_RELATED_REC_PRODUCT_GEN
                . " AND LNK_RELATED_REC = " . #cur_record#%PRODUCT_ID% ;
$qry = new dbQuery($sql_str,"File: " . __FILE__ . " LINE " . __LINE__) ;
$att_doc_recs = $qry -> getRecords() ;
$rec_count = $qry -> getCount() ;
unset($qry) ;

$cover_img = '' ;

// First find the cover image
foreach($att_doc_recs as $att_doc_rec)
{
  if (isImageFileName($att_doc_rec['DOC_FILE_NAME'])
        && $att_doc_rec['LNK_CAT'] == ATTACHED_DOC_CAT_COVER_IMAGE_ID)
  {
    $cover_img = 
      '<img src="/~attached_docs_folder~/' . $att_doc_rec['DOC_FOLDER'] 
              .  '/' . $att_doc_rec['DOC_FILE_NAME'] . '" />' ;
    break ;          
  }
}

$params['cover_pic'] = $cover_img ;

?>
