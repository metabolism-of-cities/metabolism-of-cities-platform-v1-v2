<?php
require_once 'functions.php';

$id = false;
$hash = false;

setcookie("id", $id, time()-1, "/");
setcookie("hash", $hash, time()-1, "/");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Log Out | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <div class="jumbotron">
    <h1>You have been logged out</h1>
    <p> 
      If you want to log in again, simply follow the link that you received in your e-mail.
    </p>
  </div>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
