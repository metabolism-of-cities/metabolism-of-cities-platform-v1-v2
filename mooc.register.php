<?php
require_once 'functions.php';

$skip_login = true;
require_once 'functions.omat.php';
$section = 6;
$page = 2;

// load the registration class
require_once("login/classes/Registration.php");

if ($login->isUserLoggedIn() == true || $registration->message) {
  header("Location: " . URL . "mooc");
  exit();
}


// create the registration object. when this object is created, it will do all registration stuff automatically
// so this single line handles the entire registration process.
$registration = new Registration();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Register | <?php echo SITENAME ?></title>
  </head>

  <body class="omat">

<?php require_once 'include.header.php'; ?>

<h1>Register</h1>

<?php
// show potential errors / feedback (from registration object)
if (isset($registration)) {
    if ($registration->errors) {
        foreach ($registration->errors as $error) {
            echo '<div class="alert alert-danger">'.$error.'</div>';
        }
    }
    if ($registration->messages) {
        foreach ($registration->messages as $message) {
            echo '<div class="alert alert-success">'.$message.'</div>';
        }
    }
} 
?>

<!-- register form -->
<form method="post" name="registerform" class="form form-horizontal">

  <div class="form-group">
    <label class="col-sm-2 control-label">Your name</label>
    <div class="col-sm-10">
      <input class="form-control " type="text" name="name" required />
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label">E-mail</label>
    <div class="col-sm-10">
      <input id="login_input_email" class="form-control login_input" type="email" name="user_email" required />
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label">City</label>
    <div class="col-sm-10">
      <input class="form-control " type="text" name="city"  />
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label">Country</label>
    <div class="col-sm-10">
      <input class="form-control " type="text" name="country"  />
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label">Job title / role</label>
    <div class="col-sm-10">
      <input class="form-control " type="text" name="job" placeholder="Student, government official, academic, etc."  />
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label">Affiliation</label>
    <div class="col-sm-10">
      <input class="form-control " type="text" name="affiliation" placeholder="University name, employer, etc."  />
    </div>
  </div>


  <div class="form-group">
    <label class="col-sm-2 control-label">Password</label>
    <div class="col-sm-10">
      <input id="login_input_password_new" placeholder="Min. 6 characters" class="login_input form-control" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label">Repeat password</label>
    <div class="col-sm-10">
      <input id="login_input_password_repeat" class="login_input form-control" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary" name="register" value="Register">Register</button>
      <input type="hidden" name="mooc" value="1" />
    </div>
  </div>

</form>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
