<!-- Index content -->
<h2>Settings</h2>
<button class="section">Section 1</button>
<div class="panel">
  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
</div>

<button class="section">Section 2</button>
<div class="panel">
  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
</div>

<button class="section">Health Categories for Concentration to AQI</button>
<div class="panel">
  <table class="aqi"><thead><tr><th>Sensor</th><th>Average</th><th colspan="2" style="background:#00e400;">Good</th><th colspan="2" style="background:#ff0;">Moderate</th><th colspan="2" style="background:#ff7e00;">Sensitive</th><th colspan="2" style="background:#f00; color: #fff;">Unhealthy</th><th colspan="2" style="background:#99004c; color: #fff;">Very Unhealthy</th><th colspan="2"style="background:#7e0023; color: #fff;">Hazardous</th><th>Unit</th></tr>
  <tr><th></th><th></th><th style="background:#00e400;">0</th><th style="background:#00e400;">50</th><th style="background:#ff0;">51</th><th style="background:#ff0;">100</th><th style="background:#ff7e00;">101</th><th style="background:#ff7e00;">150</th><th style="background:#f00; color: #fff;">151</th><th style="background:#f00; color: #fff;">200</th><th style="background:#99004c; color: #fff;">201</th><th style="background:#99004c; color: #fff;">300</th><th style="background:#7e0023; color: #fff;">301</th><th style="background:#7e0023; color: #fff;">500</th><th></th></tr></thead>
  <tbody>
  <tr chemical="NH3"><td>NH3-LC</td><td><select name="time_average"><option value="1">1 Hour</option><option value="8">8 Hours</option><option value="24" selected>24 Hours</option></select></td><td>0</td><td><input type="number" step="0.01" cat="good"></td><td class="good"></td><td><input type="number" step="0.01" cat="moder"></td><td class="moder"></td><td><input type="number" step="0.01" cat="sensi"></td><td class="sensi"></td><td><input type="number" step="0.01" cat="unhea"></td><td class="unhea"></td><td><input type="number" step="0.01" cat="very"></td><td class="very"></td><td><input type="number" step="0.01"></td><td>PPM</td></tr>
  <tr chemical="O3"><td>O3</td><td><select name="time_average"><option value="1">1 Hour</option><option value="8">8 Hours</option><option value="24" selected>24 Hours</option></select></td><td>0</td><td><input type="number" step="0.01" cat="good"></td><td class="good"></td><td><input type="number" step="0.01" cat="moder"></td><td class="moder"></td><td><input type="number" step="0.01" cat="sensi"></td><td class="sensi"></td><td><input type="number" step="0.01" cat="unhea"></td><td class="unhea"></td><td><input type="number" step="0.01" cat="very"></td><td class="very"></td><td><input type="number" step="0.01"></td><td>PPB</td></tr>
  <tr chemical="PM10"><td>PM10</td><td><select name="time_average"><option value="1">1 Hour</option><option value="8">8 Hours</option><option value="24" selected>24 Hours</option></select></td><td>0</td><td><input type="number" step="0.01" cat="good"></td><td class="good"></td><td><input type="number" step="0.01" cat="moder"></td><td class="moder"></td><td><input type="number" step="0.01" cat="sensi"></td><td class="sensi"></td><td><input type="number" step="0.01" cat="unhea"></td><td class="unhea"></td><td><input type="number" step="0.01" cat="very"></td><td class="very"></td><td><input type="number" step="0.01"></td><td>ug/m3</td></tr>
  <tr chemical="PM2.5"><td>PM2.5</td><td><select name="time_average"><option value="1">1 Hour</option><option value="8">8 Hours</option><option value="24" selected>24 Hours</option></select></td><td>0</td><td><input type="number" step="0.01" cat="good"></td><td class="good"></td><td><input type="number" step="0.01" cat="moder"></td><td class="moder"></td><td><input type="number" step="0.01" cat="sensi"></td><td class="sensi"></td><td><input type="number" step="0.01" cat="unhea"></td><td class="unhea"></td><td><input type="number" step="0.01" cat="very"></td><td class="very"></td><td><input type="number" step="0.01"></td><td>ug/m3</td></tr>
  <tr><td colspan="14">&nbsp;</td></tr>
  </tbody>
  </table>
</div>

<p><span class="big_button btn_save_settings">SAVE</span></p>