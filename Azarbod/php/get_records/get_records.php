<?php

// Look for leaf category and display them below the parent category
$sql_str = "SELECT PRODUCT_CAT.*, PRODUCT_CAT.UID AS PROD_CAT_ID 
            FROM PRODUCT_CAT 
            WHERE PRODUCT_CAT.LNK_TOP_CAT = " . #cur_record#%UID% 
            . " AND IS_ACTIVE = ~YES~"
            . " ORDER BY PRODUCT_CAT.CAT_NAME" ;
$qry = new dbQuery($sql_str,"File: " . __FILE__ . " LINE " . __LINE__) ;
$prod_cat_recs = $qry -> getRecords() ;
$prod_cat_rec_count = $qry -> getCount() ;
unset($qry) ;

if ($prod_cat_rec_count > 0)
{

}

?>
