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

$subgroup = (int)$_GET['subgroup'];
if ($subgroup) {
  // A particular subgroup of contacts has been selected. We need to make sure we only
  // include activities from this subgroup and everything that falls under this subgroup

  $list = $db->query("SELECT * FROM mfa_contacts WHERE dataset = $project");

  foreach ($list as $row) {
    if ($row['belongs_to']) {
      $parent[$row['id']] = $row['belongs_to'];
    } else {
      $mainparent[$row['id']] = true;
    }
  }
  $all_contacts = $db->query("SELECT id, belongs_to FROM mfa_contacts WHERE dataset = $project");
  foreach ($all_contacts as $row) {
    findFirstParent($row['id']);
    // This will create an array ($firstparents) that holds the highest level parent for each contact
  }
  foreach ($firstparents as $key => $value) {
    if ($value == $subgroup) {
      $allowed_contacts[$key] = true;
    }
  }
  $getname = $db->record("SELECT name FROM mfa_contacts WHERE id = $subgroup AND dataset = $project");
  $subgroupname = $getname->name;
}

if ($_GET['show'] == 'calendar') {
  $show = 'calendar';
} elseif ($_GET['show'] == 'graph') {
  $show = 'graph';
} elseif ($_GET['show'] == 'heatmap') {
  $show = 'heatmap';
}

$list = $db->query("SELECT l.*, a.name AS activity_name, mfa_sources.belongs_to,
  mfa_sources.name AS source_name,
  mfa_contacts.name AS contact_name
FROM mfa_activities_log l 
  JOIN mfa_activities a ON l.activity = a.id
  LEFT JOIN mfa_sources ON l.source = mfa_sources.id
  LEFT JOIN mfa_contacts ON l.contact = mfa_contacts.id
WHERE a.dataset = $project $sql ORDER BY l.end");

if ($subgroup) {
  foreach ($list as $key => $row) {
    $include_in_totals = true;
    if ($row['contact'] && !$allowed_contacts[$row['contact']]) {
      $include_in_totals = false;
    }
    if ($row['source'] && !$allowed_contacts[$row['belongs_to']]) {
      $include_in_totals = false;
    }
    if (!$include_in_totals) {
      unset($list[$key]);
    }
  }
}

if ($show == 'heatmap') { 
  foreach ($list as $row) {
    $row['start'] = format_date("Y-m-d", $row['start']);
    if (!$startdate) {
      $startdate = strtotime($row['start']) . "000";
    }
    $startdate = min(strtotime($row['start']) . "000", $startdate);
    // Add 12:00 because that way the timezone can add or subtract up to 12 hours without impact
    // Without this, the server time zone and the browser time zone get messed up
    // Ideally this should be sorted out properly but for now this works for local use
    $date = format_date("Y-m-d 12:00:00", $row['start']);
    $date = strtotime($date);
    $data[$date] += $row['time'];
  }
} elseif ($show == 'graph') {
  foreach ($list as $row) {
    $total[$row['activity_name']] += $row['time'];
  }
  arsort($total);
} elseif ($show == 'calendar') {
  foreach ($list as $row) {
    $row['date'] = $row['end'];
    $weeknumber = format_date("W", $row['date']);
    $year = format_date("Y", $row['date']);
    $label = "W$weeknumber $year";
    $total[$label][$row['activity_name']] += $row['time'];
    $activitylist[$row['activity_name']] = true;
  }
}

$types = $db->query("SELECT * FROM mfa_activities WHERE dataset = $project ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Activity Log | <?php echo SITENAME ?></title>
    <style type="text/css">
    select.form-control{width:120px;display:inline}
    #chart{margin-top:40px;}
    </style>
    <?php if ($show == 'heatmap') { ?>

      <script type="text/javascript" src="//d3js.org/d3.v3.min.js"></script>
      <script type="text/javascript" src="//cdn.jsdelivr.net/cal-heatmap/3.3.10/cal-heatmap.min.js"></script>
      <link rel="stylesheet" href="//cdn.jsdelivr.net/cal-heatmap/3.3.10/cal-heatmap.css" />
      

    <?php } elseif ($show) { ?>
      <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <?php } ?>
    <?php if ($show == 'graph') { ?>
    <script type="text/javascript">
      google.load("visualization", "1.1", {packages:["bar"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Type', 'Time spent (hours)'],
        <?php foreach ($total as $key => $value) { ?>
          ['<?php echo $key ?>',  <?php echo $value/60 ?>],
        <?php } ?>
        ]);

        var options = {
          chart: {
            title: 'Time spent on different activities',
            subtitle: 'Time shown in number of hours',
          },
          bars: 'horizontal' // Required for Material Bar Charts.
        };

        var chart = new google.charts.Bar(document.getElementById('chart'));

        chart.draw(data, options);
      }
    </script>
    <?php } elseif ($show == 'calendar') { ?>
    <script type="text/javascript">
      google.load("visualization", "1.1", {packages:["bar"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Type', <?php foreach ($activitylist as $subkey => $subvalue) { echo "'$subkey',"; } ?> { role: 'annotation'} ],
        <?php foreach ($total as $key => $value) { ?>
          ['<?php echo $key ?>',  <?php foreach ($activitylist as $subkey => $subvalue) { ?><?php echo (float)$value[$subkey]/60 ?>,<?php } ?> ''],
        <?php } ?>
        ]);

        var options = {
          chart: {
            title: 'Breakdown of time spent',
            subtitle: 'Hours per week',
          },
          stacked: true,
          bars: 'horizontal' // Required for Material Bar Charts.
        };

        var chart = new google.charts.Bar(document.getElementById('chart'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>
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
    <?php if ($subgroup) { ?>
    Subgroup: <?php echo $subgroupname ?>
    <?php } ?>
  </div>

  <?php if ($subgroup) { ?>

  <?php } ?>

  <ul class="nav nav-tabs">
    <li class="<?php echo !$show ? 'active' : 'n'; ?>">
      <a href="reports.activities.php?type=<?php echo $type ?>&amp;project=<?php echo $project ?>&amp;subgroup=<?php echo $subgroup ?>">
        Table
      </a>
    </li>
    <li class="<?php echo $show == 'calendar' ? 'active' : 'n'; ?>">
      <a href="reports.activities.php?type=<?php echo $type ?>&amp;project=<?php echo $project ?>&amp;show=calendar&amp;subgroup=<?php echo $subgroup ?>">
        Calendar Graph
      </a>
    </li>
    <li class="<?php echo $show == 'graph' ? 'active' : 'n'; ?>">
      <a href="reports.activities.php?type=<?php echo $type ?>&amp;project=<?php echo $project ?>&amp;show=graph&amp;subgroup=<?php echo $subgroup ?>">
        Time Graph
      </a>
    </li>
    <li class="<?php echo $show == 'heatmap' ? 'active' : 'n'; ?>">
      <a href="reports.activities.php?type=<?php echo $type ?>&amp;project=<?php echo $project ?>&amp;show=heatmap&amp;subgroup=<?php echo $subgroup ?>">
        Heat Map
      </a>
    </li>
  </ul>
  <?php if ($show == 'heatmap') { ?>


			<button id="example-f-PreviousDomain-selector" style="margin-bottom: 10px;" class="btn"><i class="fa fa-chevron-left"></i></button>
			<button id="example-f-NextDomain-selector" style="margin-bottom: 10px;" class="btn"><i class="fa fa-chevron-right"></i></button>
<div id="heatmap"></div>
<script type="text/javascript">
	var cal = new CalHeatMap();

cal.init({
	itemSelector: "#heatmap",
	domain: "month",
  range: 9,

	previousSelector: "#example-f-PreviousDomain-selector",

	domainDynamicDimension: false,
	nextSelector: "#example-f-NextDomain-selector",
	subDomain: "day",
	data:  
{
  <?php foreach ($data as $key => $value) { ?>
  "<?php echo $key ?>": <?php echo $value ?>,
  <?php } ?>
}
  ,
	start: new Date(<?php echo $startdate ?>),
  cellSize: 15,
	legend: [60, 120, 180, 240]
});
</script>

  <?php } elseif ($show) { ?>

    <div id="chart" style="width: 900px; height: 500px;"></div>

  <?php } else { ?>

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

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
