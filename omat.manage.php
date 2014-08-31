<?php
require_once 'functions.php';
require_once 'functions.omat.php';

$section = 6;
$load_menu = 1;
$sub_page = 1;
$id = (int)$_GET['id'];

$list = $db->query("SELECT * FROM mfa_groups WHERE dataset = $id ORDER BY section");

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("DELETE FROM mfa_groups WHERE dataset = $id AND id = {$delete} LIMIT 1");
  header("Location: " . URL . "omat/manage/$id/deleted");
  exit();
}

if ($_GET['message'] == 'deleted') {
  $print = "Data group was deleted.";
} elseif ($_GET['message'] == 'saved') {
  $print = "Information was saved.";
} elseif ($_GET['message'] == 'loaded') {
  $print = "Information has been loaded.";
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Manage Your Data | <?php echo SITENAME ?></title>
    <link rel="stylesheet" href="css/sidebar.css" />
    <style type="text/css">
    a.right{float:right}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>
<?php require_once 'include.omatheader.php'; ?>

  <h1>MFA Data Groups</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Data</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <table class="table table-striped">
    <tr>
      <th colspan="2">Group</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
  <?php foreach ($list as $row) { ?>
    <tr>
      <td><?php echo $row['section'] ?></td>
      <td><a href="omat/datagroup/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
      <td><a href="omat/datagroup-entry/<?php echo $row['id'] ?>" class="btn btn-primary">Edit</a></td>
      <td><a href="omat.manage.php?id=<?php echo $id ?>&amp;delete=<?php echo $row['id'] ?>" class="btn btn-danger" onclick="javascript:return confirm('Are you sure? ALL subgroups and any possible data will be deleted!')">Delete</a></td>
    </tr>
  <?php } ?>
  </table>

  <p>
    <a href="omat/datagroup-entry/dataset-<?php echo $id ?>" class="btn btn-success">Add data group</a>
    <a href="omat/<?php echo $id ?>/print" class="right btn btn-default"><i class="fa fa-print"></i> Print View</a>
  </p>

  <p><a href="omat/dashboard/<?php echo $id ?>" class="btn btn-primary">&laquo; Back to the dashboard</a></p>

<?php require_once 'include.omatfooter.php'; ?>
<?php require_once 'include.footer.php'; ?>

  </body>
</html>
