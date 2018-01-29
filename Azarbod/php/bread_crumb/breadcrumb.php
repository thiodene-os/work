<?php
# Front-End tool in PHP: Builds a bread_crumb for products pages

// Initial value for top_category ID
$top_cat_id = $prod_cat_rec['LNK_TOP_CAT'] ;

// Build Bread Crumb
// Get all the parent categories from this one
$bread_crumb = '' ;
while ($top_cat_id)
{
  // Get the top category name and uid for forming the bread crumb
  $prod_cat_bc_rec = lookupRecordbyId("PRODUCT_CAT","UID",$top_cat_id
                                      ,"CAT_NAME,LNK_TOP_CAT,IS_ACTIVE") ;
  // Top category becomes the current category and its parent becomes current top category
  $prod_cat_id = $top_cat_id ;
  $top_cat_id = $prod_cat_bc_rec['LNK_TOP_CAT'] ;
  // If the parent exists build the 
  if ($prod_cat_id)
  {
    $bread_crumb = ' > ' . buildHRef($prod_cat_bc_rec['CAT_NAME'],
                     'pub_product_catalogue_view?prod_cat_id=' . $prod_cat_id) . $bread_crumb ;
  }

}
$home = buildHRef('HOME','index') ;
$params['bread_crumb'] =  $home . ' ' . $bread_crumb . ' > ' . $prod_cat_rec['CAT_NAME'] ;

?>
