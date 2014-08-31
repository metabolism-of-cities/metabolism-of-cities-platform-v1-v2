<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 2;
$sub_page = 1;

$id = (int)$_GET['id'];
$info = $db->record("SELECT name FROM mfa_dataset WHERE id = $id");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>XXXX | <?php echo $info->name ?> | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Title</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/xxx">Information</a></li>
    <li class="active">Page</li>
  </ol>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
