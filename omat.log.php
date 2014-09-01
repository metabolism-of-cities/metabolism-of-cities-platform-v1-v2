<?php
$disable_sidebar = true;
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;

$id = (int)$_GET['id'];

$log = $db->query("SELECT mfa_data.date, mfa_data.year, mfa_materials.name, mfa_data.material
  FROM mfa_data 
    JOIN mfa_materials ON mfa_data.material = mfa_materials.id
    JOIN mfa_groups ON mfa_materials.mfa_group = mfa_groups.id
WHERE mfa_groups.dataset = $id
ORDER BY mfa_data.date DESC");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Log | <?php echo $info->name ?> | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Log</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Log</li>
  </ol>

  <div class="alert alert-info">
    A total of <strong><?php echo count($log) ?></strong> activities have been logged.
  </div>

  <?php if (count($log)) { ?>
    <table class="table table-striped">
      <tr>
        <th>Date</th>
        <th>Material</th>
        <th>Action</th>
      </tr>
      <?php foreach ($log as $row) { ?>
      <tr>
        <td><?php echo format_date("M d, Y", $row['date']) ?></td>
        <td><a href="omat/data/<?php echo $row['material'] ?>"><?php echo $row['name'] ?></a></td>
        <td>Value for <?php echo $row['year'] ?> added.</td>
      </tr>
      <?php } ?>
    </table>
  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
