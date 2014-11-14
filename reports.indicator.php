<?php
if ($_GET['public_login']) {
  $public_login = true;
}
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 3;
$sub_page = 2;

$id = (int)$_GET['id'];
$dataset = $db->record("SELECT * FROM mfa_dataset WHERE id = $project");

if (!$dataset->year_start || !$dataset->year_end) {
  $error = "You have not set the start and end year of your dataset. Set this first";
}

$years = range($dataset->year_start, $dataset->year_end);

$info = $db->record("SELECT i.*, t.name AS type_name, papers.title
FROM mfa_indicators i
  JOIN mfa_indicators_types t ON i.type = t.id 
  LEFT JOIN papers ON i.more_information = papers.id  
WHERE i.id = $id");

$indicator_types = $db->query("SELECT * FROM mfa_indicators WHERE type = {$info->type} AND (dataset = $project OR dataset IS NULL)");

$formula = $db->query("SELECT f.*, mfa_groups.section, mfa_groups.name
FROM mfa_indicators_formula f
  JOIN mfa_groups ON f.mfa_group = mfa_groups.id
WHERE indicator = $id AND mfa_groups.dataset = $project ORDER BY f.id");

$all_addition = true;
foreach ($formula as $row) {
  $sql_group .= $row['mfa_group'] . ",";
  if ($row['type'] == "subtract") {
    $all_addition = false;
  }
}

if ($sql_group) {
  $sql_group = substr($sql_group, 0, -1);
  $dataresults = $db->query("SELECT SUM(data) AS total, mfa_materials.mfa_group, mfa_data.year
    FROM mfa_data
    JOIN mfa_materials ON mfa_data.material = mfa_materials.id
  WHERE mfa_materials.mfa_group IN ($sql_group)
  GROUP BY mfa_materials.mfa_group, mfa_data.year");
}

if (count($dataresults)) {
  foreach ($dataresults as $row) {
    $data[$row['year']][$row['mfa_group']] = $row['total'];
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Indicators | <?php echo SITENAME ?></title>
    <style type="text/css">
    h2{font-size:23px}
    .moreinfo{opacity:0.7}
    .moreinfo:hover{opacity:1}
    #chart{height:400px}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>
    <?php echo $info->name ?>
    <?php if ($info->abbreviation) { ?>(<?php echo $info->abbreviation ?>)<?php } ?>  
  </h1>

  <ol class="breadcrumb">
      <?php if ($public_login) { ?>
        <li><a href="omat/<?php echo $project ?>/projectinfo"><?php echo $check->name ?></a></li>
      <?php } else { ?>
        <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
      <?php } ?>
    <li><a href="<?php echo $public_login ? 'omat-public' : 'omat'; ?>/<?php echo $project ?>/reports-indicators">Indicators</a></li>
    <li class="active"><?php echo $info->name ?></li>
  </ol>

  <div class="panel panel-info">
    <div class="panel-heading">
      <h3 class="panel-title">Indicator description</h3>
    </div>
    <div class="panel-body">
      <p>
        <?php echo $info->description ?>
      </p>
      <?php if ($info->title) { ?>
      <p class="moreinfo">
      <strong>More information</strong>: 
        <a href="publication/<?php echo $info->more_information ?>"><?php echo $info->title ?></a>
        </p>
        <?php } ?>
    </div>
  </div>

  <?php if (!count($formula)) { ?>
    <div class="alert alert-warning">
      <p>We do not have a formula saved for automatically calculating this indicator. You can define the 
      formula yourself and the system will proceed to calculate the values for this indicator.</p>
      <p>
        <a href="omat/<?php echo $project ?>/reports-indicator-formula/<?php echo $id ?>" class="btn btn-success">Set up formula</a>
      </p>
    </div>
  <?php } elseif ($error) { ?>
    <div class="alert alert-danger"><?php echo $error ?></div>
  <?php } else { ?>

  <h2>Data</h2>

  <table class="table table-striped data">
    <tr>
      <th></th>
      <?php foreach ($years as $year) { ?>
        <th><?php echo $year ?></th>
      <?php } ?>
    </tr>
    <tr>
    <?php foreach ($formula as $row) { ?>
      <td><?php echo $row['name'] ?></td>
      <?php foreach ($years as $year) { ?>
      <?php 
        $datapoint = $data[$year][$row['mfa_group']];
        $final[$year] += $row['type'] == "add" ? $datapoint : $datapoint*-1;
      ?>
        <td><?php echo number_format($datapoint,$dataset->decimal_precision) ?></td>
      <?php } ?>
      </tr>
    <?php } ?>

    <tr>
      <th><?php echo $info->name ?></th>
      <?php foreach ($years as $year) { ?>
        <th><?php echo number_format($final[$year],$decimal_precision) ?></th>
      <?php } ?>
    </tr>
  </table>

  <h2>Graphs</h2>

  <div id="chart"></div>

  <?php if (!$public_login) { ?>

    <div class="well">

      <a href="omat/<?php echo $project ?>/reports-indicator-formula/<?php echo $id ?>" class="btn btn-success pull-right">Edit formula</a>

      <h2>Formula</h2>

      <table class="table table-striped">
      <?php foreach ($formula as $row) { ?>
        <tr>
          <td><?php echo $row['type'] == "add" ? $plus : "-" ?></td>
          <td><a href="omat/<?php echo $project ?>/datagroup/<?php echo $row['mfa_group'] ?>"><?php echo $row['name'] ?></a></td>
        </tr>
      <?php 
      // We don't define the plus sign earlier so that it does not show up for the first element, which looks weird
      $plus = "+"; } ?>
      <tr>
        <th>=</th>
        <th><?php echo $info->name ?></th>
      </tr>
      </table>

    </div>

    <?php } ?>
  <?php } ?>

  <?php if ($dataset->banner_text) { ?>
    <div class="alert alert-info info-bar">
      <i class="fa fa-info-circle"></i>
      <?php echo $dataset->banner_text ?>
    </div>
  <?php } ?>

  <?php if (!$public_login) { ?>

  <div class="panel panel-info">
    <div class="panel-heading">
      <h3 class="panel-title">Indicator Group: <strong><?php echo $info->type_name ?></strong></h3>
    </div>
    <div class="panel-body">
      <ul class="nav nav-pills">
        <?php foreach ($indicator_types as $row) { ?>
          <li class="<?php echo $row['id'] == $id ? 'active' : 'regular'; ?>"><a href="omat/<?php echo $project ?>/reports-indicator/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

    <script type="text/javascript" src="//www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);
function drawChart() {

  <?php if ($all_addition && count($formula) > 1) { ?>
  var data = google.visualization.arrayToDataTable([
    ['Year', <?php $count = 0; foreach ($formula as $row) { $count++; ?>'<?php echo $row['name'] ?>'<?php echo $count == count($formula) ? "" : ","; ?><?php } ?>],
    <?php $count = 0; foreach ($years as $year) { $count++; ?>
    ['<?php echo $year ?>',<?php
      $subcount = 0; foreach ($formula as $row) { $subcount++;
      echo (float)$data[$year][$row['mfa_group']] ?><?php echo $subcount == count($formula) ? "" : ","; 
      } ?>
      ]<?php 
      echo $count == count($years) ? "\n" : ",\n"; 
      } ?>
  ]);
  <?php } else { ?>
  var data = google.visualization.arrayToDataTable([
    ['Year', '<?php echo $info->abbreviation ? $info->abbreviation : $info->name ?>'],
    <?php $count = 0; foreach ($final as $year => $value) { $count++; ?>
    ['<?php echo $year ?>',  <?php echo (float)$value ?>]<?php echo $count == count($final) ? "" : ","; ?>
    <?php } ?>
  ]);
  <?php } ?>

  var options = {
    title: '<?php echo $info->name ?>',
    hAxis: {title: 'Year', titleTextStyle: {color: '#333'}},
    isStacked: true
  };

  var chart = new google.visualization.ColumnChart(document.getElementById('chart'));

  chart.draw(data, options);

}
    </script>

  </body>
</html>
