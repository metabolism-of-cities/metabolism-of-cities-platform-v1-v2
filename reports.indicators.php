<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 3;
$sub_page = 2;

$id = (int)$_GET['id'];
$list = $db->query("SELECT i.*, t.name AS type_name
FROM mfa_indicators i
  JOIN mfa_indicators_types t ON i.type = t.id
WHERE i.dataset = $project OR i.dataset IS NULL
ORDER BY i.id");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Indicators | <?php echo SITENAME ?></title>
    <style type="text/css">
    h2{font-size:23px}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Indicators</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Indicators</li>
  </ol>

  <div class="row">

  <?php $type = false; foreach ($list as $row) { ?>

    <?php if ($row['type_name'] != $type) { ?>
    <?php if ($type) { ?></div></div><?php } ?>
    <div class="col-md-4">
      <h2><?php echo $row['type_name'] ?></h2>
    <div class="list-group">
    <?php } $type = $row['type_name']; ?>

      <a href="omat/<?php echo $project ?>/reports-indicator/<?php echo $row['id'] ?>" class="list-group-item">
        <h4 class="list-group-item-heading"><?php echo $row['name'] ?></h4>
        <p class="list-group-item-text"><?php echo truncate($row['description'],150) ?></p>
      </a>

  <?php } ?>

  </div>

  </div>

  </div>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
