// Export the sales order report to the excel format
function exportInventoryReportToExcel() 
{ 
  
  global $absolute_base_folder ;
  
  define("START_COLUMN",1) ;    // The starting column to wrtie the stuff
  define("START_ROW",1) ;       // The starting row. We need the top rows to show main info
  define("TOTAL_COLUMNS",6) ;   // Total number of columns on this sheet
  define("TITLE_ROWS",4) ;      // Number of rows to skip for title
  
  // Used to style the titles
  $titles_style = array("font" => array("bold" => true,"size" => 12)) ;
  $totals_style = array("font" => array("bold" => true,"size" => 13)) ;
  $row_no = START_ROW ; 
  $col_no = START_COLUMN ;
  
  // First include the required classes
  include $absolute_base_folder . "/" . plugin_folder . "/ExcelWriter/Classes/PHPExcel.php";
  include $absolute_base_folder . "/" . plugin_folder . "/ExcelWriter/Classes/PHPExcel/Writer/Excel2007.php" ;
   
     
  // Create new PHPExcel object
  $excel_obj  = new PHPExcel();
  
  // Set properties
  
  $excel_obj -> getProperties() -> setCreator("Azarbod Inc.- Azarbod Software");
  $excel_obj -> getProperties() -> setLastModifiedBy("Azarbod Inc.- Azarbod Software Project");
  $excel_obj -> getProperties() -> setTitle("Inventory Report");
  $excel_obj -> getProperties() -> setSubject("Inventory Report");
  $excel_obj -> getProperties() -> setDescription("Inventory Report " . date("Y-m-d")) ;

  $excel_obj -> setActiveSheetIndex(0) ; // Always use the first sheet only
  
  
  //********* First write the main information on the top
  $title_start_cell = coordinateNoToExcelMethod($col_no,$row_no) ;
  $title_end_cell = coordinateNoToExcelMethod($col_no + 30,$row_no) ;
  $sheet_title = " Liquor Inventory Report Details - " 
                  . $GLOBALS['config_mgr'] -> getParam("host_company_name") ;
  $excel_sheet = $excel_obj -> getActiveSheet() ;   
  
  $excel_sheet -> SetCellValue($title_start_cell,$sheet_title) ;
  // Change the font and make it big
  $excel_sheet -> getStyle($title_start_cell) -> applyFromArray(array("font" => array("size" => "18")));
  // Merge the title columns so when we auto size the rest of the rows, it does not size it to this one
  $excel_sheet -> mergeCells($title_start_cell . ":" . $title_end_cell);
  $row_no += TITLE_ROWS ; // Skip title rows to separate title from data
  
  
  // **** First put the header row
  $col_no = START_COLUMN ;
  $excel_sheet -> SetCellValue(coordinateNoToExcelMethod($col_no,$row_no),"Product Name") ; 
  $col_no++ ;
  $excel_sheet -> SetCellValue(coordinateNoToExcelMethod($col_no,$row_no),"Bin #") ;  
  $col_no++ ;
  $excel_sheet -> SetCellValue(coordinateNoToExcelMethod($col_no,$row_no),"Vintage") ;  
  $col_no++ ;
  $excel_sheet -> SetCellValue(coordinateNoToExcelMethod($col_no,$row_no),"Quantity") ;
  $col_no++ ;
  $excel_sheet -> SetCellValue(coordinateNoToExcelMethod($col_no,$row_no),"Purchase Price") ;
  $col_no++ ;
  $excel_sheet -> SetCellValue(coordinateNoToExcelMethod($col_no,$row_no),"Selling Price") ;
  $col_no++ ;
  
  // Apply titles format to titles
  for($col_no = START_COLUMN ; $col_no < TOTAL_COLUMNS + START_COLUMN; $col_no++)
  {
    $excel_cell_id = coordinateNoToExcelMethod($col_no,$row_no) ;
    $excel_sheet -> getStyle($excel_cell_id) -> applyFromArray($titles_style);
  }
  
  // Get the Inventory values fro the INV ITEM table
  //
  $sql_str = "SELECT *
           , INV_ITEM.UID AS INV_ITEM_ID 
           , PRODUCT_GEN.PRODUCT_NAME
           , PRODUCT_LIQUOR.BIN_NUMBER
           , PRODUCT_LIQUOR.VINTAGE
           , IFNULL(PRODUCT_GEN.AVG_PURCHASE_PRICE, 0) AS AVG_PURCHASE_PRICE
           , IFNULL(PRODUCT_GEN.AVG_SELLING_PRICE, 0) AS AVG_SELLING_PRICE
           , INV_LEVEL.LEVEL_CODE
           , INV_LEVEL.UID AS LEVEL_ID
           , PACKAGE_TYPE.PACKAGE_NAME
           , PACKAGE_TYPE.CAPACITY AS PACKAGE_CAPACITY
           FROM INV_ITEM
           INNER JOIN PRODUCT_GEN ON PRODUCT_GEN.UID = INV_ITEM.LNK_PRODUCT
           INNER JOIN PRODUCT_LIQUOR ON PRODUCT_GEN.UID = PRODUCT_LIQUOR.LNK_PRODUCT_GEN
           INNER JOIN PACKAGE_TYPE ON PRODUCT_LIQUOR.LNK_PACKAGE_TYPE = PACKAGE_TYPE.UID
           INNER JOIN INV_LEVEL ON INV_LEVEL.UID = INV_ITEM.LNK_INV_LEVEL
           WHERE CUR_QTY > 0 ORDER BY LNK_INV_LEVEL, PRODUCT_GEN.PRODUCT_NAME" ;
  $qry = new dbQuery($sql_str,"File: " . __FILE__ . " LINE " . __LINE__) ;
  $inv_item_recs = $qry -> getRecords() ;
  $rec_count = $qry -> getCount() ;
  unset($qry) ;

  $previous_level_id = FALSE ;
  $total_selling_price = 0 ;
  $total_purchase_price = 0 ;
  foreach($inv_item_recs as $inv_item_rec)
  {
    
    // First put the warehouse corresponding to the inventory items listed
    $level_id = $inv_item_rec['LEVEL_ID'] ;
    if ($level_id != $previous_level_id)
    {
      // Add the Total Prices at the end of the list
      if ($previous_level_id)
      {
        $row_no++ ;
        $col_no = START_COLUMN ;
        $col_no++ ;
        $col_no++ ;
        $col_no++ ;
        
        $excel_sheet -> SetCellValue(coordinateNoToExcelMethod($col_no,$row_no),'Total Prices:') ;
        $col_no++ ;
        
        $excel_sheet -> SetCellValue(coordinateNoToExcelMethod($col_no,$row_no),$total_purchase_price) ;
        $col_no++ ; 
        
        $excel_sheet -> SetCellValue(coordinateNoToExcelMethod($col_no,$row_no),$total_selling_price) ;
        $col_no++ ; 
        
        $total_purchase_price = 0 ;
        $total_selling_price = 0 ;
        
        
      }
      
      $row_no++ ;
      $row_no++ ;
      $col_no = START_COLUMN ;     
      $excel_sheet -> SetCellValue(coordinateNoToExcelMethod($col_no,$row_no),$inv_item_rec['LEVEL_CODE']) ;
      // Apply Title style
      $excel_cell_id = coordinateNoToExcelMethod($col_no,$row_no) ;
      $excel_sheet -> getStyle($excel_cell_id) -> applyFromArray($titles_style);
      
    }
    // Save old warehouse ID for comparison
    $previous_level_id = $level_id ;
    
    // Report details of the Inventory Report
    $row_no++ ;
    $col_no = START_COLUMN ;     
    $excel_sheet -> SetCellValue(coordinateNoToExcelMethod($col_no,$row_no),$inv_item_rec['PRODUCT_NAME']) ;
    $col_no++ ;
    
    $excel_sheet -> SetCellValue(coordinateNoToExcelMethod($col_no,$row_no),$inv_item_rec['BIN_NUMBER']) ;
    $col_no++ ;
 
    $excel_sheet -> SetCellValue(coordinateNoToExcelMethod($col_no,$row_no),$inv_item_rec['VINTAGE']) ;
    $col_no++ ;

    $excel_sheet -> SetCellValue(coordinateNoToExcelMethod($col_no,$row_no),$inv_item_rec['CUR_QTY']) ;
    $col_no++ ; 
  
    $excel_sheet -> SetCellValue(coordinateNoToExcelMethod($col_no,$row_no),$inv_item_rec['AVG_PURCHASE_PRICE']) ;
    $col_no++ ; 
    
    $excel_sheet -> SetCellValue(coordinateNoToExcelMethod($col_no,$row_no),$inv_item_rec['AVG_SELLING_PRICE']) ;
    $col_no++ ; 
    
    // Update the total prices at this warehouse
    $total_purchase_price += $inv_item_rec['AVG_PURCHASE_PRICE'] * $inv_item_rec['CUR_QTY'] ;
    $total_selling_price += $inv_item_rec['AVG_SELLING_PRICE'] * $inv_item_rec['CUR_QTY'] ;
  
  }

  // After last record add total prices
  $row_no++ ;
  $col_no = START_COLUMN ;
  $col_no++ ;
  $col_no++ ;
  $col_no++ ;
  
  $excel_sheet -> SetCellValue(coordinateNoToExcelMethod($col_no,$row_no),'Total Prices:') ;
  $col_no++ ;
  
  $excel_sheet -> SetCellValue(coordinateNoToExcelMethod($col_no,$row_no),$total_purchase_price) ;
  $col_no++ ; 
  
  $excel_sheet -> SetCellValue(coordinateNoToExcelMethod($col_no,$row_no),$total_selling_price) ;
  $col_no++ ; 
  
  // *** Set all the columns as auto-width for better reading and do alignment left or right
  for($col_no = START_COLUMN ; $col_no <= TOTAL_COLUMNS ; $col_no++)
  {
    $excel_cell_id = coordinateNoToExcelMethod($col_no) ;
    $excel_sheet -> getColumnDimension($excel_cell_id) -> setAutoSize(true);
    
    // Up to column 3 align left and the rest that are $ values, align right  
    $col_cells = $excel_cell_id . (TITLE_ROWS + 1) . ":" . $excel_cell_id . $row_no ; 
    // + 1 is to skip the title row
    
    if ($col_no <= START_COLUMN + 2 || $col_no == START_COLUMN + 8) 
      $excel_sheet -> getStyle($col_cells) -> getAlignment() 
                   -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT) ;  
    else
      $excel_sheet -> getStyle($col_cells) -> getAlignment() 
                   -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT) ;  
    $excel_sheet -> getStyle($col_cells) -> getNumberFormat()
                 -> setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
  }
  
  
  //****** Set the title of sheet and make sure it does not exceed 31 chars 
  // which is the Excel limit
  $excel_sheet -> setTitle(substr($sheet_title,0,31)); 
      
  // Save the file as Excel 2007 file
  $file_name = "Inventory Report Details_" . date("Y_m_d") . ".xlsx" ;
  $full_file_name = $absolute_base_folder . "/" . temp_folder . "/" . $file_name ;
  $objWriter = new PHPExcel_Writer_Excel2007($excel_obj );
  $objWriter -> save($full_file_name);
  
  // Finally force the file to be downloaded
  forceFileDownload($full_file_name) ;
} // exportInventoryReportToExcel
