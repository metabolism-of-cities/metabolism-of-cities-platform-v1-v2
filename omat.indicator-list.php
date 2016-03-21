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
mfa_indicators.name");

$population_list = $db->query("SELECT * FROM mfa_population WHERE dataset = $id");

foreach ($population_list as $row) {
  $population[$row['year']] = $row['population'];
}

foreach ($list as $row) {
  $total = 0;
  $formula[$row['id']] = $row['name'];
  if ($row['mfa_material']) {
    $data_materials = materialFlow($row['mfa_group'], $row['mfa_material']);
  } else {
    $data = materialGroupFlow($row['mfa_group']);
  }
  foreach ($years as $year) {
    if ($row['mfa_material']) {
      $value = $data_materials[$year][$row['mfa_group']][$row['mfa_material']];
    } else {
      $value = $data[$year][$row['mfa_group']];
    }
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
    <title>Indicator List | <?php echo SITENAME ?></title>
  </head>

  <body class="omat">

<?php require_once 'include.header.php'; ?>

  <h1>Indicator List</h1>

  <ol class="breadcrumb">
      <?php if ($public_login) { ?>
        <li><a href="omat/<?php echo $project ?>/projectinfo"><?php echo $check->name ?></a></li>
      <?php } else { ?>
        <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
      <?php } ?>
    <li class="active">Indicators</li>
  </ol>

  <?php if (!count($population_list) && !$public_login) { ?>
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
        <th>
          <?php echo $value ?> - total<br />
          (<?php echo $dataset->measurement ?>)
        </th>
        <?php if ($per_capita) { ?>
          <th>
            <?php echo $value ?> - per capita<br />
            (<?php echo $dataset->measurement ?>)
          </th>
        <?php } ?>
      <?php } ?>
    </tr>

  <?php foreach ($formula as $id => $name) { ?>
    <tr>
      <td><?php echo $name ?></td>
      <?php foreach ($years as $key => $value) { ?>
        <td>
          <a href="<?php echo $omat_link ?>/<?php echo $project ?>/reports-indicator/<?php echo $id ?>">
            <?php echo number_format($result[$id][$value],0) ?>
          </a>
        </td>
        <?php if ($per_capita) { ?>
          <?php if ($population[$value]) { ?>
            <td><?php echo number_format($result[$id][$value]/$population[$value],2) ?></td>
          <?php } else { ?>
            <td></td>
          <?php } ?>
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
