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
  // Add the notifications to Menu Bar
  if($_SESSION['notifications'] != 0)
    $display_notifications = '<span class="button__badge">' . $_SESSION['notifications'] . '</span>' ;
  else
    $display_notifications = '' ;
  
  // Add the wrap around markup
  $final_menu = '<nav id="sidebar" role="navigation"> <!-- Sidebar Div -->
<a href="#sidebar" title="Show navigation">Show navigation</a>
<a href="#" title="Hide navigation">Hide navigation</a>
<ul class="clearfix">
<li module_id="home"><a href="http://sims2.scentroid.com" aria-haspopup="true"><img src="/images/nav_home_bt.png"" alt="Home" /></a>
<ul>
<h1>HOME</h1>
<li><a href="http://sims2.scentroid.com/overview/"  title="">Overview</a></li>
<li><a href="#">Companies</a>
' . buildCompanyListDropDown()
. '
</li>
</ul>
</li>
<li module_id="analysis"><a href="http://sims2.scentroid.com/analysis" aria-haspopup="true"><img src="/images/nav_analysis_bt.png"" alt="Analysis" /></a>
</li>
<li module_id="notifications"><a href="http://sims2.scentroid.com/notifications" aria-haspopup="true"><img src="/images/nav_notification_bt.png"" alt="Notifications" /></a>
' . $display_notifications . '
</li>
<li module_id="settings"><a href="http://sims2.scentroid.com/settings" aria-haspopup="true"><img src="/images/nav_settings_bt.png"" alt="Settings" /></a>
</li>
<li module_id="tour"><a href="javascript:void(0);" onclick="startIntro();" aria-haspopup="true"><img src="/images/take_a_tour.png"" alt="Website Tour" /></a>
</li>
<li module_id="customers"><a href="http://sims2.scentroid.com/logout" aria-haspopup="true"><img src="/images/nav_signout_bt.png"" alt="Customer Management" /></a>
</li>
</ul>
</nav>' ;

  return $final_menu ;
} // buildUserMenu



function build_overview_menu1() {
    // Add the wrap around markup
    $final_menu = '
    <nav id="sidebar" role="navigation"> <!-- Sidebar Div -->
        <ul class="clearfix">
            
            <li module_id="home">
                <a href="http://sims2.scentroid.com/overview/" aria-haspopup="true" id="test_image"><img src="/images/nav_home_b.png"" alt="Home" /></a>
                <ul>
                    <li><a href="http://sims2.scentroid.com/"  title="">HOME</a></li>
                    <li><a href="http://sims2.scentroid.com/equipment/"  title="">Equipments</a></li>
                </ul>
            </li>
            
            <li module_id="materials">
                <a href="http://sims2.scentroid.com/analysis_ademir" aria-haspopup="true"><img src="/images/nav_analysis_b.png"" alt="Raw Materials and Procurement" /></a>
            </li>
            
            <li module_id="settings">
                <a href="http://sims2.scentroid.com/settings_ademir" aria-haspopup="true"><img src="/images/nav_settings_b.png"" alt="Accounting" /></a>
            </li>
            
            <li module_id="notifications">
                <a href="http://sims2.scentroid.com/notifications_ademir" aria-haspopup="true"><img src="/images/nav_notif_b.png" alt="Notifications" /></a>
            </li>
            <li module_id="signout">
                <a href="http://sims2.scentroid.com/logout" aria-haspopup="true"><img src="/images/nav_signout_b.png"" alt="Customer Management" /></a>
            </li>
        </ul>
    </nav>' ;

    return $final_menu ;
} // buildUserMenu for Overview page



?>