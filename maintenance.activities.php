<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 2;
$sub_page = 4;

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM mfa_dataset WHERE id = $id");

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $check = $db->query("SELECT * FROM mfa_activities_log WHERE activity = $delete");
  if (count($check)) {
    die("Sorry, we could not delete this activity type because there are records logged already!");
  }
  $db->query("DELETE FROM mfa_activities WHERE id = $delete AND dataset = $id LIMIT 1");
  $print = "Activity was deleted";
}

$list = $db->query("SELECT * FROM mfa_activities WHERE dataset = $id ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <link rel="stylesheet" href="css/sidebar.css" />
    <title>Types of Activities | <?php echo $info->name ?> | OMAT | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>
<?php require_once 'include.omatheader.php'; ?>

  <h1>Types of Activities</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Types of Activities</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <?php if (!count($list)) { ?>

  <div class="alert alert-info">
    In this section you can define Activity Types for your project. By setting different types, OMAT
    can later provide you with reports broken down by activity types. It can provide you more insight into
    how much effort obtaining data points took. Types of activities could include 'Meetings', 'E-mail sending', 
    'Reading and analyzing', etc.
  </div>

  <?php } else { ?>
    <table class="table table-striped">
      <tr>
        <th>Name</th>
        <th>Edit</th>
        <th>Delete</th>
      </tr>
    <?php foreach ($list as $row) { ?>
      <tr>
        <td><?php echo $row['name'] ?></td>
        <td><a href="omat/<?php echo $project ?>/maintenance-activity/<?php echo $row['id'] ?>" class="btn btn-primary">Edit</a></td>
        <td><a href="omat/<?php echo $project ?>/maintenance-activities/<?php echo $row['id'] ?>/delete" class="btn btn-danger" onclick="javascript:return confirm('Are you sure?')">Delete</a></td>
      </tr>
    <?php } ?>
    </table>
  <?php } ?>

  <p><a href="omat/<?php echo $project ?>/maintenance-activity/0" class="btn btn-success">Add activity type</a></p>

<?php require_once 'include.omatfooter.php'; ?>
<?php require_once 'include.footer.php'; ?>

  </body>
</html>
