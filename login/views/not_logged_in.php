<?php
// show potential errors / feedback (from login object)
if (isset($login)) {
    if ($login->errors) {
        foreach ($login->errors as $error) {
            echo '<div class="alert alert-danger">'.$error.'</div>';
        }
    }
    if ($login->messages) {
        foreach ($login->messages as $message) {
            echo '<div class="alert alert-success">'.$message.'</div>';
        }
    }
}
?>

<form method="post" name="loginform" action="page/login" class="form-horizontal">

<div class="form-group">
  <label class="col-sm-2 control-label">Username</label>
  <div class="col-sm-10">
    <input id="login_input_username" class="form-control login_input" type="text" name="user_name" required />
  </div>
</div>

<div class="form-group">
  <label class="col-sm-2 control-label">Password</label>
  <div class="col-sm-10">
    <input id="login_input_password" class="form-control login_input" type="password" name="user_password" autocomplete="off" required />
  </div>
</div>

<div class="form-group">
  <div class="col-sm-offset-2 col-sm-10">
    <button type="submit" class="btn btn-primary" name="login" value="true">Log In</button>
  </div>
</div>

</form>

<p><a class="btn btn-default" href="omat/add">Register new account</a></p>
