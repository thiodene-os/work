// Builds the drop down used for bottle size for liquor
function buildBottleSizeDropDown($class , $add_no_value = true , $add_new_size_option = true)
{
  $sql_str = "SELECT LIQUOR_SIZE.UID AS LIQUOR_SIZE_ID 
                FROM LIQUOR_SIZE ORDER BY SIZE" ;
  $qry = new dbQuery($sql_str,"File: " . __FILE__ . " LINE " . __LINE__) ;
  $liquor_size_recs = $qry -> getRecords() ;
  unset($qry) ;

  $result = '<select class="' . $class . '" name="' . $class . '">' ;
  if ($add_no_value)
    $result .= '<option value="' . DEF_SHOW_ON_NO_VALUE . '">' . DEF_SHOW_ON_NO_VALUE . '</option>' ;
  
  foreach($liquor_size_recs as $liquor_size_rec)
  {
    $size_str = buildLiquorSizeName($liquor_size_rec['LIQUOR_SIZE_ID']) ;  
    $result .= '<option value="' . $liquor_size_rec['LIQUOR_SIZE_ID'] . '">' . $size_str . '</option>' ;
  }
  
  // Also add the option so user can create new size at the bttom if needed.
  // This is handled by javascript
  if ($add_new_size_option)
    $result .= '<option value="new_size">** New Size **</option>' ;
  
  $result .= '</select>' ;
  
  return $result ;
} // buildBottleSizeDropDown
