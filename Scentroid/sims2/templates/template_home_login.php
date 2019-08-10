<!-- Index content -->
<form id="form_login">
<div class="login">
  <p>
  <img src="/images/logo.png" />
  </p>
  <p>
    <input type="text" id="email" placeholder="Email" />
  </p>
  <p>
    <input type="password" id="password" placeholder="Password" />
  </p>
  <p>
    <span><a style="color:blue;" href="/login/reset_password.php">Forgot your password?</a></span>
  </p>
  <p>
    <input type="checkbox" name="remember" value="remember"> Remember Me
    <input type="hidden" name="login" value="TRUE">
  </p>
  <p>
    <button class="big_button btn_login" type="button">LOGIN<i class="fa fa-sign-in fa-2x"></i></button>
  </p>
  <div id="login_container"></div>
</div>
</form>