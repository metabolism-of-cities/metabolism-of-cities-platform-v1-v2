<?php
$disable_sidebar = true;
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM mfa_dataset WHERE id = $id");

$log = $db->query("SELECT mfa_data.date, mfa_data.year, mfa_materials.name, mfa_data.material
  FROM mfa_data 
    JOIN mfa_materials ON mfa_data.material = mfa_materials.id
    JOIN mfa_groups ON mfa_materials.mfa_group = mfa_groups.id
WHERE mfa_groups.dataset = $id
ORDER BY mfa_data.date ASC");

if (LOCAL) {
  $list = $db->query("SELECT DISTINCT DATE(end) AS date,
    (SELECT SUM(time) FROM mfa_activities_log 
      JOIN mfa_activities ON mfa_activities_log.activity = mfa_activities.id
    WHERE DATE(end) <= DATE(l.end) AND mfa_activities.dataset = a.dataset) AS time
  FROM mfa_activities_log l
    JOIN mfa_activities a ON l.activity = a.id
  WHERE a.dataset = $id GROUP BY DATE(end) ORDER BY DATE(end)");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Log | <?php echo $info->name ?> | <?php echo SITENAME ?></title>
  </head>

  <body class="omat">

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
      <?php
        $datapoint_counter++;
        $date = format_date("Y-m-d", $row['date']);
        $datapoints[$date] = $datapoint_counter;
      ?>
      <tr>
        <td><?php echo format_date("M d, Y", $row['date']) ?></td>
        <td><a href="omat/data/<?php echo $row['material'] ?>"><?php echo $row['name'] ?></a></td>
        <td>Value for <?php echo $row['year'] ?> added.</td>
      </tr>
      <?php } ?>
    </table>
  <?php } ?>

  <?php if (count($list)) { ?>

  <?php
    $first_date = $list[0]['date'];
    $max_record = count($list)-1;
    $last_date = $date > $list[$max_record]['date'] ? $date : $list[$max_record]['date'];

    foreach ($list as $row) {
      $newlist[$row['date']] = array(
        'time' => $row['time'],
        'datapoints' => $datapoints[$row['date']],
      );
    }

  ?>

  <h2>Date Overview</h2>

  <table class="table table-striped">
    <t $points =r>
      <th>Day #</th>
      <th>Date</th>
      <th>Time (minutes)</th>
      <th>Time (hours)</th>
      <th>Data Points</th>
    </tr>
  <?php $i = 0; do { ?>
  <?php
    $date = format_date("Y-m-d", $first_date . " +$i days");
    $i++;
    $time = $newlist[$date]['time'] ?: $time;
    if ($newlist[$date]['datapoints']) {
      $points = $newlist[$date]['datapoints'];
    } elseif ($datapoints[$date]) {
      $points = $datapoints[$date];
    } else { 
      $points = $points;
    }
  ?>
    <tr>
      <td><?php echo $i ?></td>
      <td><?php echo $date ?></td>
      <td><?php echo $time ?></td>
      <td><?php echo formatTime($time) ?></td>
      <td><?php echo $points ?></td>
    </tr>
  <?php } while($date <= $last_date); ?>
  </table>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
