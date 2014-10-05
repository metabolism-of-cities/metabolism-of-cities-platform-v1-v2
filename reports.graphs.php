<?php
require_once 'functions.php';
require_once 'functions.omat.php';

$section = 6;
$load_menu = 3;
$sub_page = 6;

$list = $db->query("SELECT * FROM mfa_groups WHERE dataset = $project ORDER BY section");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Indicators | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Indicators</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Graphs</li>
  </ol>

  <p>Select on of the material groups to review the related graphs:</p>

  <ul class="nav nav-pills nav-stacked">
    <?php foreach ($list as $row) { ?>
      <li><a href="omat/<?php echo $project ?>/reports-graph/<?php echo $row['id'] ?>"><?php echo $row['section'] ?>. <?php echo $row['name'] ?></a></li>
    <?php } ?>
  </ul>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
