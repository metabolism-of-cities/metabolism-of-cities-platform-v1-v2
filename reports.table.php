<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 3;
$sub_page = 3;

$id = (int)$_GET['id'];
$dataset = $db->record("SELECT * FROM mfa_dataset WHERE id = $project");

if (!$dataset->year_start || !$dataset->year_end) {
  $error = "You have not set the start and end year of your dataset. Set this first";
}

$list = $db->query("SELECT *,
  (SELECT COUNT(*) FROM mfa_materials m WHERE m.mfa_group = $id AND m.code LIKE CONCAT(mfa_materials.code, '.%')) AS subcategories 
FROM mfa_materials WHERE mfa_group = $id ORDER BY mfa_materials.code");

$years = range($dataset->year_start, $dataset->year_end);

$tables = $db->query("SELECT * FROM mfa_groups WHERE dataset = $project ORDER BY section");

$info = $db->record("SELECT * FROM mfa_groups WHERE id = $id AND dataset = $project");

$dataresults = $db->query("SELECT AVG(data*multiplier) AS total, mfa_data.year, mfa_data.material
  FROM mfa_data
  JOIN mfa_materials ON mfa_data.material = mfa_materials.id
WHERE mfa_materials.mfa_group = $id AND mfa_data.include_in_totals = 1
GROUP BY mfa_materials.code, mfa_data.year");

if (count($dataresults)) {
  foreach ($dataresults as $row) {
    $data[$row['year']][$row['material']] = $row['total'];
  }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->section ?>. <?php echo $info->name ?> | <?php echo SITENAME ?></title>
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
    <?php echo $info->section ?>.
    <?php echo $info->name ?>
  </h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/reports-tables">Data Tables</a></li>
    <li class="active"><?php echo $info->section ?>. <?php echo $info->name ?></li>
  </ol>

  <table class="table table-striped data">
    <tr>
      <th></th>
      <?php foreach ($years as $year) { ?>
        <th><?php echo $year ?></th>
      <?php } ?>
    </tr>
    <tr>
    <?php foreach ($list as $row) { ?>
      <td style="padding-left:<?php echo strlen($row['code'])*10; ?>px"><?php echo $row['code'] ?>. <?php echo $row['name'] ?></td>
      <?php foreach ($years as $year) { ?>
      <?php 
        $datapoint = $data[$year][$row['id']];
        $final[$year] += $datapoint;
      ?>
        <td>
        <?php if (!$row['subcategories'] || $datapoint) { ?>
          <a href="omat/<?php echo $project ?>/reports-data/<?php echo $year ?>/<?php echo $row['id'] ?>"><?php echo number_format($datapoint,$dataset->decimal_precision) ?></a>
        <?php } ?>
        </td>
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

  <div class="panel panel-info">
    <div class="panel-heading">
      <h3 class="panel-title">Data Tables</h3>
    </div>
    <div class="panel-body">
      <ul class="nav nav-pills">
        <?php foreach ($tables as $row) { ?>
          <li class="<?php echo $row['id'] == $id ? 'active' : 'regular'; ?>">
            <a href="omat/<?php echo $project ?>/reports-table/<?php echo $row['id'] ?>">
              <?php echo $row['section'] ?>. <?php echo $row['name'] ?>
            </a>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
