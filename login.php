<?php
require_once 'functions.php';
$skip_login = true;
require_once 'functions.omat.php';

if ($login->isUserLoggedIn() == true) {
  if (count($permissions) == 1) {
    foreach ($permissions as $row) {
      $project = $row['dataset'];
    }
    header("Location: " . URL . "omat/dashboard/$project");
    exit();
  } else {
    header("Location: " . URL . "omat/create");
    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Login | <?php echo SITENAME ?></title>
  <script type="text/javascript">
  $(function(){
    $("#login_input_username").focus();
  });
  </script>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Login</h1>

<?php
// ... ask if we are logged in here:
if ($login->isUserLoggedIn() == true) {
    // the user is logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are logged in" view.
    include("login/views/logged_in.php");

} else {
    // the user is not logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are not logged in" view.
    include("login/views/not_logged_in.php");
}
?>
<?php require_once 'include.footer.php'; ?>

  </body>
</html>
