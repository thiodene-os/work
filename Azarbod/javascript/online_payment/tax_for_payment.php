$params['session_id'] = session_id();

// Also find the taxes and set the grand total
$sql_str = "SELECT SUM(SUB_TOTAL) AS TOTAL_SUM FROM SHOPPING_CART
            WHERE SESSION_ID = '" . session_id() . "'" ;
$qry = new dbQuery($sql_str,"File: " . __FILE__ . " Line: " . __LINE__) ;
$total_rec = $qry -> getSingleRecord() ;
$total_tax = round($total_rec['TOTAL_SUM'] * 13 / 100,2) ;
$params['total_taxes'] = "$" . $total_tax ;
$params['grand_total'] = "$" . ($total_tax + $total_rec['TOTAL_SUM']) ;

// Create year options for expiry for credit card
$year_options = "<option value=\"" . DEF_SHOW_ON_NO_VALUE . "\">" . DEF_SHOW_ON_NO_VALUE . "</option>" ;
for($i = 0 ; $i < 10 ; $i++)
{
  $year = date("Y") + $i ;
  $year_options .= "<option value=\"" . substr($year,2) . "\">" . $year . "</option>" ;
}
$params['year_options'] = $year_options ;

// *************** Sub-total for Discount *****************************
// Calculate the sub-total for discount. The difference is that the subtotal
// that is used for discount coupon excludes the delivery
$sql_str = "SELECT SUM(SUB_TOTAL) AS TOTAL_SUM FROM SHOPPING_CART
            WHERE SESSION_ID = '" . session_id() . "'" ;
$qry = new dbQuery($sql_str,"File: " . __FILE__ . " Line: " . __LINE__) ;
$total_discountable_rec = $qry -> getSingleRecord() ;
$params['discountable_subtotal'] = $total_discountable_rec['TOTAL_SUM'] ;

// ************************************** For Process Online  *************************************
// Put customer name in JavaScript to pass to payment processor
$params['name_on_card'] = '' ;
$params['grand_total_js'] = $total_tax + $total_rec['TOTAL_SUM'] ;
