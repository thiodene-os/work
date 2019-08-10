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
<li module_id="home"><a href="#" aria-haspopup="true"><img src="/images/nav_home_bt.png"" alt="Home" /></a>
<ul>
<h1>HOME</h1>
<li><a href="http://207.246.86.177/"  title="">SIMS 2.0</a></li>
<li><a href="http://207.246.86.177/overview/"  title="">Overview</a></li>
<li><a href="#">Companies</a>
' . buildCompanyListDropDown()
. '
</li>
<li><a href="http://207.246.86.177/analysis"  title="">Analysis</a></li>
<li><a href="http://207.246.86.177/equipment/"  title="">Equipments</a></li>
<li><a href="http://207.246.86.177/production/wo_suggest_list.php"  title="">Logs</a></li>
<li><a href="http://207.246.86.177/settings/"  title="">Settings</a></li>
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
<li module_id="notifications"><a href="http://207.246.86.177/notifications" aria-haspopup="true"><img src="/images/nav_notification_bt.png"" alt="Notifications" /></a>
</li>
<li module_id="customers"><a href="http://207.246.86.177/logout" aria-haspopup="true"><img src="/images/nav_signout_bt.png"" alt="Customer Management" /></a>
</li>
</ul>
</nav>' ;

  return $final_menu ;
} // buildUserMenu



function build_overview_menu() {
    // Add the wrap around markup
    $final_menu = '
    <nav id="sidebar" role="navigation"> <!-- Sidebar Div -->
        <ul class="clearfix">
            
            <li module_id="home">
                <a href="http://207.246.86.177/overview/" aria-haspopup="true" id="test_image"><img src="/images/nav_home_b.png"" alt="Home" /></a>
                <ul>
                    <li><a href="http://207.246.86.177/"  title="">HOME</a></li>
                    <li><a href="#">Companies</a>' . buildCompanyListDropDown() . '</li>
                    <li><a href="http://207.246.86.177/equipment/"  title="">Equipments</a></li>
                </ul>
            </li>
            
            <li module_id="materials">
                <a href="http://207.246.86.177/analysis_ademir" aria-haspopup="true"><img src="/images/nav_analysis_b.png"" alt="Raw Materials and Procurement" /></a>
            </li>
            
            <li module_id="settings">
                <a href="http://207.246.86.177/settings_ademir" aria-haspopup="true"><img src="/images/nav_settings_b.png"" alt="Accounting" /></a>
            </li>
            
            <li module_id="notifications">
                <a href="#" aria-haspopup="true"><img src="/images/nav_notif_b.png" alt="Notifications" /></a>
            </li>
            <li module_id="customers">
                <a href="http://207.246.86.177/logout" aria-haspopup="true"><img src="/images/nav_signout_b.png"" alt="Customer Management" /></a>
            </li>
        </ul>
    </nav>' ;

    return $final_menu ;
} // buildUserMenu for Overview page



?>