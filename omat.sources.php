<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 3;

$id = (int)$_GET['id'];

$list = $db->query("SELECT s.*, t.name AS type
FROM mfa_sources s
LEFT JOIN mfa_sources_types t ON s.type = t.id
WHERE s.dataset = $id");

$types = $db->query("SELECT * FROM mfa_sources_types WHERE dataset = $id ORDER BY name");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Sources | <?php echo SITENAME ?></title>
    <style type="text/css">
    a.right{float:right}
    th{width:120px;}
    th.long{width:auto}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <a href="omat/<?php echo $id ?>/source/0" class="btn btn-success right">Add Source</a>

  <h1>Sources</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Sources</li>
  </ol>

  <div class="alert alert-info">
    <strong><?php echo count($list) ?></strong> sources found.
  </div>

  <?php if (count($list)) { ?>

    <table class="table table-striped">
      <tr>
        <th class="long">Name</th>
        <?php if (count($types)) { ?>
          <th>Type</th>
        <?php } ?>
        <th>Created</th>
        <th>Status</th>
      </tr>
    <?php foreach ($list as $row) { ?>
      <tr>
        <td><a href="omat/<?php echo $project ?>/viewsource/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
        <?php if (count($types)) { ?>
          <td><?php echo $row['type'] ?></td>
        <?php } ?>
        <td><a href="omat/<?php echo $project ?>/source/<?php echo $row['id'] ?>">Edit</a></td>
        <td><?php echo $row['pending'] ? 'Pending' : 'Processed'; ?></td>
      </tr>
    <?php } ?>
    </table>

  <?php } ?>

  <a href="omat/<?php echo $id ?>/source/0" class="btn btn-success">Add Source</a>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
