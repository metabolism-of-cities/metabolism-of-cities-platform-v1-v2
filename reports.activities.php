<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 3;
$sub_page = 4;

if ($_GET['type']) {
  $type = (int)$_GET['type'];
  $sql .= " AND l.activity = $type";
}

$list = $db->query("SELECT l.*, a.name AS activity_name,
  mfa_sources.name AS source_name,
  mfa_contacts.name AS contact_name
FROM mfa_activities_log l 
  JOIN mfa_activities a ON l.activity = a.id
  LEFT JOIN mfa_sources ON l.source = mfa_sources.id
  LEFT JOIN mfa_contacts ON l.contact = mfa_contacts.id
WHERE a.dataset = $project $sql ORDER BY l.end");

$types = $db->query("SELECT * FROM mfa_activities WHERE dataset = $project ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Activity Log | <?php echo SITENAME ?></title>
    <style type="text/css">
    select.form-control{width:120px;display:inline}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Activity Log</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Activity Log</li>
  </ol>

  <form class="form form-horizontal" action="reports.activities.php">

  <p>
    <select name="type" class="form-control">
      <option value=""></option>
      <?php foreach ($types as $row) { ?>
        <option value="<?php echo $row['id'] ?>"<?php if ($row['id'] == $type) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
      <?php } ?>
    </select>

    <button type="submit" class="btn btn-primary">Filter</button>
    <input type="hidden" name="project" value="<?php echo $project ?>" />

  </p>

  </form>

  <?php if (count($list)) { ?>

  <div class="alert alert-info">
    <?php echo count($list) ?> records found.
  </div>

  <table class="table table-striped ellipsis">
    <tr>
      <th>ID</th>
      <th>Related To</th>
      <th>Type</th>
      <th>Start</th>
      <th>End</th>
      <th>Time</th>
    </tr>
    <?php foreach ($list as $row) { ?>
    <tr>
      <td><a href="omat/<?php echo $project ?>/viewactivity/<?php echo $row['id'] ?>"><?php echo $row['id'] ?></a></td>
      <td>
        <?php if ($row['contact']) { ?>
          <a href="omat/<?php echo $project ?>/viewcontact/<?php echo $row['contact'] ?>"><?php echo $row['contact_name'] ?></a>
        <?php } else { ?>
          <a href="omat/<?php echo $project ?>/viewsource/<?php echo $row['source'] ?>"><?php echo $row['source_name'] ?></a>
        <?php } ?>
      </td>
      <td><?php echo $row['activity_name'] ?></td>
      <td><?php echo format_date("M d, Y H:i:s", $row['start']); ?></td>
      <td><?php echo format_date("M d, Y H:i:s", $row['end']); ?></td>
      <td><?php echo formatTime($row['time']); $time += $row['time']; ?></td>
    </tr>
  <?php } ?>
  <tr>
    <th colspan="5">Total</th>
    <th><?php echo formatTime($time) ?></th>
  </tr>
  </table>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>