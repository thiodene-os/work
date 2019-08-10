<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="/includes/javascript/date_picker.js" type="text/javascript"></script>
<script src="/includes/javascript/table_scroll.js" type="text/javascript"></script>
<script src="/includes/javascript/update_button.js" type="text/javascript"></script>
<script>
// Initialize and add the map
function initMap() 
{
  // The location of Equipment
  var equipment = <?php echo $equipment_geoposition_js ; ?>
  
  <?php echo $all_equipments_geoposition_js ; ?>
  // The map, centered at Equipment
  var map = new google.maps.Map(
      document.getElementById('map'), {zoom: 12, center: equipment, mapTypeId: 'satellite'});
  // The marker, positioned at Equipment
  
  <?php echo $all_equipments_marker_js ; ?>
  
  <?php echo $notice ; ?>
  
  var marker = new google.maps.Marker({position: equipment, map: map,
      label: {
        text: "AQI: <?php echo $aqi ; ?>",
        color: "<?php echo $color_code ; ?>",
        fontWeight: "bold",
        fontSize: "16px"
      },icon: {
      labelOrigin: new google.maps.Point(11,50),url: "https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_red.png"}});
  
  <?php echo $listener ; ?>
  
  <?php echo $equipment_wind_triangle_js ; ?>
  
}
</script>

<style>
@import url(/includes/css/common.css);
@import url(/includes/css/show_table.css);
@import url(/includes/css/show_map.css);                     
</style>

</head>
<body>

<button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>

<nav id="sidebar" role="navigation"> <!-- Sidebar Div -->
<a href="#sidebar" title="Show navigation">Show navigation</a>
<a href="#" title="Hide navigation">Hide navigation</a>
<ul class="clearfix">
<a href="http://sims2.scentroid.com"><img src="/images/scentroid.png"" alt="Home" /></a>
<li module_id="home"><a href="#" aria-haspopup="true"><img src="/images/nav_home_bt.png"" alt="Home" /></a>
<ul>
<h1>HOME</h1>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/production/ws_dashboard.php"  title="">SIMS Managements</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/production/work_order_list.php"  title="">Companies</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/production/work_orders_dashboard.php"  title="">Users</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/production/work_order_scheduled_list.php"  title="">Equipements</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/production/wo_suggest_list.php"  title="">Logs</a></li>
<li><a href="#">Settings</a>
<ul>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/base_info/product_list.php"  title="">Products</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/base_info/product_mass_edit_console.php"  title="">Mass Edit</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/base_info/product_cat_list.php"  title="">Categories</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/base_info/products_mass_upload_docs.php"  title="">Document Upload</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/base_info/product_archive_list.php"  title="">EOL Products</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/base_info/warehouse_setup.php"  title="">Warehouse Setup</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/production/work_center_setup.php"  title="">Setup Workstations</a></li>
</ul>
</li>
</ul>
</li>
<li module_id="materials"><a href="#" aria-haspopup="true"><img src="/images/nav_base_info_bt.png"" alt="Raw Materials and Procurement" /></a>
<ul>
<h1>RAW MATERIALS</h1>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/production/work_order_preprocess_console.php"  title="">Pre-process Console</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/production/work_order_list_preprocess.php"  title="">Pre-process Work Orders</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/purchasing/po_short_list.php"  title="">Required Purchasing</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/purchasing/purchase_order_list.php?purchase_orders={{po_receive_status:1,2}}"  title="">Purchase Orders</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/purchasing/po_receive_list.php"  title="">Receiving</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/production/inv_count_list.php?inv_counts={{inv_count_status:1,2}}"  title="">Cycle Counts</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/purchasing/supplier_list.php"  title="">Suppliers</a></li>
</ul>
</li>
<li module_id="accounting"><a href="#" aria-haspopup="true"><img src="/images/nav_public_site_bt.png"" alt="Accounting" /></a>
<ul>
<h1>ACCOUNTING</h1>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/production/work_order_list.php"  title="">Balance Sheet</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/production/work_orders_dashboard.php"  title="">Profit & Loss</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/reports/acct_synch_list.php"  title="">Accounting Synch</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/accounting/mgr_payment_list.php"  title="">All Payments</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/customer/customer_balance_list.php"  title="">Customers Balance</a></li>
<li><a href="#">Settings</a>
<ul>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/accounting/chart_of_accounts_setup.php"  title="">Accounts Setup</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/accounting/manage_acct_base_info.php"  title="">Other Base Info.</a></li>
</ul>
</li>
</ul>
</li>
<li module_id="specials"><a href="#" aria-haspopup="true"><img src="/images/nav_liquor_bt.png"" alt="Specials" /></a>
<ul>
<h1>QUOTING</h1>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/quote/quote_request_list.php?quote_requests={{quote_req_status:1}}"  title="">Quote Requests</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/quote/quote_list.php"  title="">Quotes</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/quote/quote_request_new.php"  title="">New Quote Request</a></li>
<li><a href="#">Settings and Tools</a>
<ul>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/quote/quote_head_master_list.php"  title="">Quote Headers</a></li>
</ul>
</li>
</ul>
</li>
<li module_id="customers"><a href="#" aria-haspopup="true"><img src="/images/nav_customers_bt.png"" alt="Customer Management" /></a>
<ul>
<h1>Customers</h1>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/ordering/mgr_sales_order_list.php"  title="">Orders</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/ordering/invoice_list.php"  title="">Invoices</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/base_info/customer_ad_source_list.php?customer_ad_source_list={{is_active:1}}"  title="">Adv. Sources</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/customer/customer_list.php"  title="">Customers</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/manage/prod_promo_list.php"  title="">Promotions</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/manage/mgr_shipment_list.php"  title="">Shipments</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/manage/shipping_console.php"  title="">Shipping Console</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/manage/shipments_in_progress.php"  title="">Shipments in Progress</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/customer/email_marketing_console.php"  title="">Mass Emails</a></li>
</ul>
</li>
<li module_id="reports"><a href="#" aria-haspopup="true"><img src="/images/nav_reports_bt.png"" alt="Reports" /></a>
<ul>
<h1>Reports</h1>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/reports/report_overstock_products.php"  title="">Overstock</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/reports/report_pending_quotes.php"  title="">Pending Quotes</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/production/scrap_list.php"  title="">Defects</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/manage/sys_alert_list.php"  title="">Alerts</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/reports/report_items_on_sales_order.php"  title="">Items on Sales Orders</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/reports/report_items_on_purchase_order.php"  title="">Items on Purchase Orders</a></li>
</ul>
</li>
<li module_id="settings"><a href="#" aria-haspopup="true"><img src="/images/nav_manage_bt.png"" alt="Settings" /></a>
<ul>
<h1>Settings</h1>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/base_info/sys_setting_list.php"  title="">Global Setting</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/base_info/attached_doc_cat_list.php"  title="">Document Categories</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/manage/staff_list.php"  title="">Staff</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/manage/manage_users_and_privileges.php"  title="">Users & Privilegs</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/manage/mgr_email_template_list.php"  title="">Email Templates</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/manage/email_queue_list.php?sys_email_queue_list={{send_status:1}}"  title="">Email Queue</a></li>
<li><a href="http://ec2-54-213-253-13.us-west-2.compute.amazonaws.com/base_info/package_type_list.php"  title="">Packaging Setup</a></li>
</ul>
</li>
</ul>
</nav>

<div id="main_content">
<div style="background-color:white;color:black;padding:10px 70px 0px 70px; margin:0;"><a id="logo" href="http://sims2.scentroid.com" title="SIMS 2.0"><img src="/images/logo.jpg" /></a>
<div class="user_nav"><img style="vertical-align: middle" src="/images/mnu_icon_user_def.png" /><span class="user_name">Admin User(IDES2)</span>
<a class="user_logout" href="sims2.scentroid.com/logout"> <i class="fa fa-sign-in fa-2x"></i>Sign Out</a></div>
</div>
<div style="background-color:#F0F0F0;padding:10px 70px 2500px; margin:0;">