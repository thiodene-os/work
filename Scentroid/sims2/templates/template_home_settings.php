<!-- Index content -->
<h2>Settings</h2>

<div class="input_fields">

  <?php
  echo $update_company_select_field ;
  ?>

</div>

<button id="company_section" class="section">Company Information: [USER,MANAGER]</button>
<div class="panel">
  <?php echo $company_info_div ; ?>
</div>

<button id="user_section" class="section">Users: [MANAGER]</button>
<div class="panel">
  <?php echo $user_list_table ; ?>
</div>

<button class="section">Company: [ADMIN]</button>
<div class="panel">
  <?php echo $company_list_table ; ?>
</div>

<button class="section">Equipments: [ADMIN]</button>
<div class="panel">
  <?php echo $equipment_list_table ; ?>
</div>

<button class="section">Sensors: [ADMIN]</button>
<div class="panel">
  <?php echo $sensor_list_table ; ?>
</div>

<button id="notification_section" class="section">Notifications: [USER,MANAGER]</button>
<div class="panel">
  <?php echo $notification_table ; ?>
</div>

<button id="aqi_section" class="section">Health Categories for Concentration to AQI: [USER,MANAGER]</button>
<div class="panel">
  <?php echo $aqi_table ; ?>
</div>
<div id="settings_container"></div>

<p><span class="big_button btn_save_settings">SAVE</span></p>