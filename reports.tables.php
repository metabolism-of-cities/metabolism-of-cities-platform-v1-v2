<?php
if ($_GET['public_login']) {
  $public_login = true;
}
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 3;
$sub_page = 3;

$list = $db->query("SELECT * FROM mfa_groups WHERE dataset = $project ORDER BY section");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Data Tables | <?php echo SITENAME ?></title>
    <style type="text/css">
    h2{font-size:23px}
    .badge{z-index:100}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Tables</h1>

  <ol class="breadcrumb">
      <?php if ($public_login) { ?>
        <li><a href="omat/<?php echo $project ?>/projectinfo"><?php echo $check->name ?></a></li>
      <?php } else { ?>
        <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
      <?php } ?>
    <li class="active">Data Tables</li>
  </ol>

  <p>Select a table:</p>

  <ul class="nav nav-list nav-stacked">
  <?php foreach ($list as $row) { ?>
    <li><a href="<?php echo $public_login ? 'omat-public' : 'omat'; ?>/<?php echo $project ?>/reports-table/<?php echo $row['id'] ?>/values-only"><?php echo $row['section'] ?>. <?php echo $row['name'] ?></a></li>
  <?php } ?>
  </ul>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
