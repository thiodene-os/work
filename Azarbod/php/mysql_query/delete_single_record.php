<?php

//if delivery_type is DELIVERY
if($delivery_type == DELIVERY_TYPE_DELIVERY)
{
  $city = lookupColumnById("CATERING_TEMP", "UID", $customer_id, "DEL_CITY") ;
  if(strtolower($city) == "caledon")
  {
    //if city is caledon update the shopping cart
    $new_rec_uin = array() ;
    $new_rec_uin['LNK_PRODUCT'] = DELIVERY_WITHIN_CALEDON ;
    $new_rec_uin['QTY'] = 1 ;
    $new_rec_uin['PRICE'] = lookupColumnById("PRODUCT", "UID", DELIVERY_WITHIN_CALEDON, "PRICE") ;
    $new_rec_uin['SUB_TOTAL'] = lookupColumnById("PRODUCT", "UID", DELIVERY_WITHIN_CALEDON, "PRICE") ;
    $do_record = new doRecord("SHOPPING_CART") ;
    $do_record -> id_column_val = $shoping_cart_recs['UID'] ;
    $do_record -> new_record = $new_rec_uin ;
    $do_record -> updateRecord();
  }
  else
  {
    //if city is not caledon update the shopping cart
    $new_rec_uin = array() ;
    $new_rec_uin['LNK_PRODUCT'] = DELIVERY_OUTSIDE_CALEDON ;
    $new_rec_uin['QTY'] = 1 ;
    $new_rec_uin['PRICE'] = lookupColumnById("PRODUCT", "UID", DELIVERY_OUTSIDE_CALEDON, "PRICE") ;
    $new_rec_uin['SUB_TOTAL'] = lookupColumnById("PRODUCT", "UID", DELIVERY_OUTSIDE_CALEDON, "PRICE") ;
    $do_record = new doRecord("SHOPPING_CART") ;
    $do_record -> id_column_val = $shoping_cart_recs['UID'] ;
    $do_record -> new_record = $new_rec_uin ;
    $do_record -> updateRecord();
  }
} // if($delivery_type == DELIVERY_TYPE_DELIVERY)
else
{
  //if delivery_type is NOT DELIVERY
  //we have pickup and there is a delivery item, remove the delivery item
  $do_record_del = new doRecord("SHOPPING_CART");
  $do_record_del -> id_column_val = $shoping_cart_recs['UID'];
  $do_record_del -> deleteRecords();
} // else if($delivery_type == DELIVERY_TYPE_DELIVERY)

?>
