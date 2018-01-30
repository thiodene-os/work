// params_prog type of isset check for a GET session

if (isset(#_GET#['prod_cat_id']))
{
  $prod_cat_id = #_GET#['prod_cat_id'] ;
  $prod_cat_rec = lookupRecordbyId("PRODUCT_CAT","UID",$prod_cat_id
                                      ,"CAT_NAME,LNK_TOP_CAT,IS_ACTIVE") ;

  $params['prod_cat_name'] = $prod_cat_rec['CAT_NAME'] ;


}
