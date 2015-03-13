<?php
require_once 'functions.php';
$section = 6;
$page = 3;

$sql = LOCAL ? '' : "WHERE access = 'public'";
$list = $db->query("SELECT * FROM mfa_dataset $sql");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>List of projects | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

<h1>List of projects</h1>

<div class="alert alert-info">
  We have found a total of <strong><?php echo count($list) ?></strong> public <?php echo count($list) == 1 ? "dataset" : "datasets" ?>.
</div>

<?php if (count($list)) { ?>

  <table class="table table-striped">
    <tr>
      <th>Dataset</th>
      <th>Start</th>
      <th>End</th>
      <th>View</th>
    </tr>
  <?php foreach ($list as $row) { ?>
    <tr>
      <td><?php echo $row['name'] ?></td>
      <td><?php echo $row['year_start'] ?></td>
      <td><?php echo $row['year_end'] ?></td>
      <td><a href="omat/<?php echo $row['id'] ?>/projectinfo">View</a></td>
    </tr>
  <?php } ?>
  </table>

<?php } ?>

<p>Would you like to manage your own dataset online? Create your project now!</p>

<p><a href="omat/add" class="btn btn-primary">Create a project</a></p>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
