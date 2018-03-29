// Start by building the select input for POs that may be in preparation
// Make a Select input with the list of the POs
$po_in_prep_list = '<select class="po_id">
              <option value="~def_show_on_no_value~">~def_show_on_no_value~</option>' ;

$sql_str = "SELECT PURCHASE_ORDER.*
            , PURCHASE_ORDER.UID AS PO_ID 
            , SUPPLIER.SUPPLIER_NAME
            FROM PURCHASE_ORDER 
            INNER JOIN SUPPLIER ON PURCHASE_ORDER.LNK_SUPPLIER = SUPPLIER.UID
            WHERE PURCHASE_ORDER.PO_STATUS = " . PURCHASE_ORDER_STATUS_PREPARATION ;
$qry = new dbQuery($sql_str,"File: " . __FILE__ . " LINE " . __LINE__) ;
$special_order_po_recs = $qry -> getRecords() ;
$special_order_po_rec_count = $qry -> getCount() ;
unset($qry) ;

foreach($special_order_po_recs as $special_order_po_rec)
{

  // List the POs and the respective supplier
  $po_in_prep_list .= '<option value="' . $special_order_po_rec['PO_ID'] . '">' 
                      . 'PO: ' . $special_order_po_rec['PO_NUMBER'] . ' -:- '
                      . $special_order_po_rec['SUPPLIER_NAME'] . '</option>' ;

}

$po_in_prep_list .= '</select>' ;
