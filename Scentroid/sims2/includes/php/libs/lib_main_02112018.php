<?php

// Main libraries for global page use

// Function calculates how long ago a timestamp or a date string is
function timeElapsedString($timestamp) 
{

  $timestamp = time() - $timestamp; // to get the time since that moment
  $timestamp = ($timestamp<1)? 1 : $timestamp;
  $tokens = array (
      31536000 => 'year',
      2592000 => 'month',
      604800 => 'week',
      86400 => 'day',
      3600 => 'hour',
      60 => 'minute',
      1 => 'second'
  );

  foreach ($tokens as $unit => $text) {
      if ($timestamp < $unit) continue;
      $numberOfUnits = floor($timestamp / $unit);
      return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'').' ago';
  }

} // timeElapsedString

// Builds and returns the html menu based on user privileges
function buildUserMenu()
{

  // Add the wrap around markup
  $final_menu = '<nav id="sidebar" role="navigation"> <!-- Sidebar Div -->
<a href="#sidebar" title="Show navigation">Show navigation</a>
<a href="#" title="Hide navigation">Hide navigation</a>
<ul class="clearfix">
<a href="http://207.246.86.177"><img src="/images/scentroid.png"" alt="Home" /></a>
<li module_id="home"><a href="#" aria-haspopup="true"><img src="/images/nav_home_bt.png"" alt="Home" /></a>
<ul>
<h1>HOME</h1>
<li><a href="http://207.246.86.177/"  title="">SIMS 2.0</a></li>
<li><a href="http://207.246.86.177/overview/"  title="">Overview</a></li>
<li><a href="http://207.246.86.177/company/"  title="">Companies</a></li>
<li><a href="http://207.246.86.177/analysis"  title="">Analysis</a></li>
<li><a href="http://207.246.86.177/equipment/"  title="">Equipments</a></li>
<li><a href="http://207.246.86.177/production/wo_suggest_list.php"  title="">Logs</a></li>
<li><a href="#">Settings</a>
<ul>
<li><a href="http://207.246.86.177/base_info/product_list.php"  title="">Products</a></li>
<li><a href="http://207.246.86.177/base_info/product_mass_edit_console.php"  title="">Mass Edit</a></li>
<li><a href="http://207.246.86.177/base_info/product_cat_list.php"  title="">Categories</a></li>
<li><a href="http://207.246.86.177/base_info/products_mass_upload_docs.php"  title="">Document Upload</a></li>
<li><a href="http://207.246.86.177/base_info/product_archive_list.php"  title="">EOL Products</a></li>
<li><a href="http://207.246.86.177/base_info/warehouse_setup.php"  title="">Warehouse Setup</a></li>
<li><a href="http://207.246.86.177/production/work_center_setup.php"  title="">Setup Workstations</a></li>
</ul>
</li>
</ul>
</li>
<li module_id="materials"><a href="#" aria-haspopup="true"><img src="/images/nav_base_info_bt.png"" alt="Raw Materials and Procurement" /></a>
<ul>
<h1>RAW MATERIALS</h1>
<li><a href="http://207.246.86.177/production/work_order_preprocess_console.php"  title="">Pre-process Console</a></li>
<li><a href="http://207.246.86.177/production/work_order_list_preprocess.php"  title="">Pre-process Work Orders</a></li>
<li><a href="http://207.246.86.177/purchasing/po_short_list.php"  title="">Required Purchasing</a></li>
<li><a href="http://207.246.86.177/purchasing/purchase_order_list.php?purchase_orders={{po_receive_status:1,2}}"  title="">Purchase Orders</a></li>
<li><a href="http://207.246.86.177/purchasing/po_receive_list.php"  title="">Receiving</a></li>
<li><a href="http://207.246.86.177/production/inv_count_list.php?inv_counts={{inv_count_status:1,2}}"  title="">Cycle Counts</a></li>
<li><a href="http://207.246.86.177/purchasing/supplier_list.php"  title="">Suppliers</a></li>
</ul>
</li>
<li module_id="accounting"><a href="#" aria-haspopup="true"><img src="/images/nav_public_site_bt.png"" alt="Accounting" /></a>
<ul>
<h1>ACCOUNTING</h1>
<li><a href="http://207.246.86.177/production/work_order_list.php"  title="">Balance Sheet</a></li>
<li><a href="http://207.246.86.177/production/work_orders_dashboard.php"  title="">Profit & Loss</a></li>
<li><a href="http://207.246.86.177/reports/acct_synch_list.php"  title="">Accounting Synch</a></li>
<li><a href="http://207.246.86.177/accounting/mgr_payment_list.php"  title="">All Payments</a></li>
<li><a href="http://207.246.86.177/customer/customer_balance_list.php"  title="">Customers Balance</a></li>
<li><a href="#">Settings</a>
<ul>
<li><a href="http://207.246.86.177/accounting/chart_of_accounts_setup.php"  title="">Accounts Setup</a></li>
<li><a href="http://207.246.86.177/accounting/manage_acct_base_info.php"  title="">Other Base Info.</a></li>
</ul>
</li>
</ul>
</li>
<li module_id="specials"><a href="#" aria-haspopup="true"><img src="/images/nav_liquor_bt.png"" alt="Specials" /></a>
<ul>
<h1>QUOTING</h1>
<li><a href="http://207.246.86.177/quote/quote_request_list.php?quote_requests={{quote_req_status:1}}"  title="">Quote Requests</a></li>
<li><a href="http://207.246.86.177/quote/quote_list.php"  title="">Quotes</a></li>
<li><a href="http://207.246.86.177/quote/quote_request_new.php"  title="">New Quote Request</a></li>
<li><a href="#">Settings and Tools</a>
<ul>
<li><a href="http://207.246.86.177/quote/quote_head_master_list.php"  title="">Quote Headers</a></li>
</ul>
</li>
</ul>
</li>
<li module_id="customers"><a href="#" aria-haspopup="true"><img src="/images/nav_customers_bt.png"" alt="Customer Management" /></a>
<ul>
<h1>Customers</h1>
<li><a href="http://207.246.86.177/myaccount"  title="">My Account</a></li>
<li><a href="http://207.246.86.177/logout"  title="">Logout</a></li>
</ul>
</li>
</ul>
</nav>' ;

  return $final_menu ;
} // buildUserMenu




?>