<?php
if ($_GET['public_login']) {
  $public_login = true;
}
require_once 'functions.php';
require_once 'functions.omat.php';

$section = 6;
$load_menu = 3;
$sub_page = 6;
$project = (int)$_GET['id'];

$list = $db->query("SELECT * FROM mfa_groups WHERE dataset = $project ORDER BY section");

$check_access = $db->query("SELECT * FROM mfa_dataset WHERE id = $project");
if ($check_info->access == "private") {
  kill("No access");
}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Graphs | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Graphs</h1>

    <ol class="breadcrumb">
      <?php if ($public_login) { ?>
        <li><a href="omat/<?php echo $project ?>/projectinfo"><?php echo $check->name ?></a></li>
      <?php } else { ?>
        <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
      <?php } ?>
      <li class="active">Graphs</li>
    </ol>

  <p>Select on of the material groups to review the related graphs:</p>

  <ul class="nav nav-pills nav-stacked">
    <?php foreach ($list as $row) { ?>
      <li><a href="omat<?php echo $public_login ? "-public" : ''; ?>/<?php echo $project ?>/reports-graph/<?php echo $row['id'] ?>"><?php echo $row['section'] ?>. <?php echo $row['name'] ?></a></li>
    <?php } ?>
  </ul>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
