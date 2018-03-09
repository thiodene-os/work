<?php

// This function handles the saved values from the purchase Order page
// and stores them into the database PURCHASE_ORDER
function savePurchaseOrderValues($po_info)
{
  // Take the Inventory values from their respective inventory level and decode with JSON
  $po_info = json_decode($po_info) ;
  
  // Save these values into the PURCHASE_ORDER_ITEM table (each item)
  foreach($po_info -> po_items as $cur_po_value)
  {
    //debug($po_info,"po_info","File: " . __FILE__ . " Line: " . __LINE__) ;
    //debug($cur_po_value,"cur_po_value","File: " . __FILE__ . " Line: " . __LINE__) ;
    
    $do_record = new doRecord("PURCHASE_ORDER_ITEM") ;
    $new_rec = array() ;
    $new_rec['NUM_PACKS'] = $cur_po_value -> num_packs ;
    $new_rec['PRICE_PER_PACK'] = $cur_po_value -> price_pack ;
    $new_rec['VENDOR_PART_NUM'] = $cur_po_value -> sku_num ;
    $new_rec['DATE_REQUIRED'] = $po_info -> po_date ;
    
    $do_record -> new_record = $new_rec ;
    $do_record -> id_column_val = $cur_po_value -> po_item_id ;
    $do_record -> update() ;
    unset($new_rec) ;
    unset($do_record) ;
  }
  
  // Now update the PURCHASE ORDER table
  $do_record = new doRecord("PURCHASE_ORDER") ;
  $new_rec = array() ;
  $new_rec['PO_DATE'] = $po_info -> po_date ;
  $new_rec['DATE_REQUIRED'] = $po_info -> due_date ;
  $new_rec['PO_NOTES'] = xmlbDecodeFromAJAX($po_info -> po_notes) ;
  $new_rec['LICENSE_NUMBER'] = xmlbDecodeFromAJAX($po_info -> license_number) ;
  $new_rec['SUB_TOTAL'] = $po_info -> po_total ;
  
  $do_record -> new_record = $new_rec ;
  $do_record -> id_column_val = $po_info -> po_id ;
  $do_record -> update() ;
  unset($new_rec) ;
  unset($do_record) ;
} // savePurchaseOrderValues

?>
