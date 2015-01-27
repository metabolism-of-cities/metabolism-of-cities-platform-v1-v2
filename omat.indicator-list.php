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
$list = $db->query("SELECT f.*, mfa_indicators.name, mfa_indicators.id
FROM mfa_indicators_formula f
  JOIN mfa_indicators ON f.indicator = mfa_indicators.id
  JOIN mfa_groups ON f.mfa_group = mfa_groups.id
WHERE mfa_groups.dataset = $project ORDER BY 
FIELD(mfa_indicators.id, 13,14,15,9,1,5,8,11)");

$population_list = $db->query("SELECT * FROM mfa_population WHERE dataset = $id");

foreach ($population_list as $row) {
  $population[$row['year']] = $row['population'];
}

foreach ($list as $row) {
  $sql_group .= $row['mfa_group'] . ",";
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

/*
$data[2013][95] = 0;
$data[2013][96] = 0;
/**/

foreach ($list as $row) {
  $total = 0;
  $formula[$row['id']] = $row['name'];
  foreach ($years as $year) {
    $value = $data[$year][$row['mfa_group']];
    if ($row['type'] == "add") {
      $result[$row['id']][$year] += $value; 
    } elseif ($row['type'] == "subtract") {
      $result[$row['id']][$year] -= $value; 
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Indicators | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Indicator List</h1>

  <ol class="breadcrumb">
      <?php if ($public_login) { ?>
        <li><a href="omat/<?php echo $project ?>/projectinfo"><?php echo $check->name ?></a></li>
      <?php } else { ?>
        <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
      <?php } ?>
      <li><a href="omat/<?php echo $project ?>/reports-indicators">Indicators</a></li>
    <li class="active">List</li>
  </ol>

  <?php if (!count($population_list)) { ?>
    <div class="alert alert-danger">
      You have not defined population numbers. Per-capita values can be calculated if
      you <a href="omat/<?php echo $project ?>/population">define the population first</a>.
    </div>
  <?php } else { $per_capita = true; } ?>

  <div class="row">

  <table class="table table-striped">
    <tr>
      <th>Indicator</th>
      <?php foreach ($years as $key => $value) { ?>
        <th><?php echo $value ?> - Total</th>
        <?php if ($per_capita) { ?>
          <th><?php echo $value ?> - Per capita</th>
        <?php } ?>
      <?php } ?>
    </tr>

  <?php foreach ($formula as $id => $name) { ?>
    <tr>
      <td><?php echo $name ?></td>
      <?php foreach ($years as $key => $value) { ?>
        <td><?php echo number_format($result[$id][$value],0) ?></td>
        <?php if ($per_capita) { ?>
          <td><?php echo number_format($result[$id][$value]/$population[$value],2) ?></td>
        <?php } ?>
      <?php } ?>
    </tr>    
  <?php } ?>

  </table>

  </div>

  </div>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
