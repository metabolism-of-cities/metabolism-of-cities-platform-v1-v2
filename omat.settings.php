<?php
require_once 'functions.php';
$section = 6;

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM mfa_dataset WHERE id = $id");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>OMAT | Settings | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Project Settings</h1>



<?php require_once 'include.footer.php'; ?>

  </body>
</html>
