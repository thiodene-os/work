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
<li module_id="home"><a href="http://207.246.86.177/" aria-haspopup="true"><img src="/images/nav_home_bt.png"" alt="Home" /></a>
<ul>
<h1>HOME</h1>
<li><a href="http://207.246.86.177/overview/"  title="">Overview</a></li>
<li><a href="#">Companies</a>
' . buildCompanyListDropDown()
. '
</li>
</ul>
</li>
<li module_id="analysis"><a href="http://207.246.86.177/analysis" aria-haspopup="true"><img src="/images/nav_analysis_bt.png"" alt="Analysis" /></a>
</li>
<li module_id="notifications"><a href="http://207.246.86.177/notifications" aria-haspopup="true"><img src="/images/nav_notification_bt.png"" alt="Notifications" /></a>
</li>
<li module_id="settings"><a href="http://207.246.86.177/settings" aria-haspopup="true"><img src="/images/nav_settings_bt.png"" alt="Settings" /></a>
</li>
<li module_id="tour"><a href="javascript:void(0);" onclick="startIntro();" aria-haspopup="true"><img src="/images/take_a_tour.png"" alt="Website Tour" /></a>
</li>
<li module_id="customers"><a href="http://207.246.86.177/logout" aria-haspopup="true"><img src="/images/nav_signout_bt.png"" alt="Customer Management" /></a>
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
                <a href="http://207.246.86.177/overview/" aria-haspopup="true" id="test_image"><img src="/images/nav_home_b.png"" alt="Home" /></a>
                <ul>
                    <li><a href="http://207.246.86.177/"  title="">HOME</a></li>
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
                <a href="http://207.246.86.177/notifications_ademir" aria-haspopup="true"><img src="/images/nav_notif_b.png" alt="Notifications" /></a>
            </li>
            <li module_id="signout">
                <a href="http://207.246.86.177/logout" aria-haspopup="true"><img src="/images/nav_signout_b.png"" alt="Customer Management" /></a>
            </li>
        </ul>
    </nav>' ;

    return $final_menu ;
} // buildUserMenu for Overview page



?>