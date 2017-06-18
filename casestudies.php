<?php
$show_breadcrumbs = true;
$skip_login = true;
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 5;
$page = 2;

if ($_GET['preview']) {
  setcookie("preview", "true", time()+60*60*24*7, "/");
}

$sql = false;

if ($_GET['message'] == 'saved') {
  $print = "Information has been saved";
}

$options = array(
  "overview" => "Case Study Overview",
  "indicators" => "Indicators",
  "filters" => "Filter Data",
  "download" => "Download Data",
  "map" => "Map",
);

if (!$_GET['message']) {
  $page = "overview";
}

if ($options[$_GET['message']]) {
  $page = $_GET['message'];
}

if ($_GET['deleted']) {
  $print = "Case study was deleted";
}

if ($page == "overview") {
$list = $db->query("SELECT papers.*, case_studies.* 
FROM case_studies 
  JOIN papers
  ON case_studies.paper = papers.id
  $sql 
ORDER BY papers.year, case_studies.name
");
} elseif ($page == "indicators") {
  $list = $db->query("SELECT 
  i.*, a.name AS area, s.name AS subarea,
  (SELECT COUNT(*) FROM data WHERE indicator = i.id) AS total
  FROM data_indicators i
  JOIN data_subareas s ON i.subarea = s.id
  JOIN data_areas a ON s.area = a.id
  ORDER BY a.name, s.name, i.name
  ");
}


$count = $db->record("SELECT COUNT(*) AS total FROM case_studies");
$count_indicators = $db->record("SELECT COUNT(*) AS total FROM data_indicators");
$total_count = $db->record("SELECT COUNT(*) AS total FROM data");
$count_per_study = $db->query("SELECT COUNT(*) AS total, case_study 
FROM data 
GROUP BY case_study
");

foreach ($count_per_study as $row) {
  $study_count[$row['case_study']] = $row['total'];
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Material Flow Analysis Case Studies | <?php echo SITENAME ?></title>
    <style type="text/css">
    .table.ellipsis{border-top:0}
    .table > tbody > tr > th {border-top:0}
    .optionlist{max-width:500px}
    hgroup h2{font-size:29px;margin:0}
    hgroup h3{font-size:15px;margin:0}
    .explanation{border-bottom:2px dotted #999}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Global Urban Metabolism Data</h1>

    
<div class="row">
  <div class="col-sm-6 col-md-4">
    <div class="thumbnail alert alert-info">
      <div class="caption">
        <hgroup>
        <h2><?php echo $count->total ?></h2>
        <h3>Case Studies</h3>
        </hgroup>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-md-4">
    <div class="thumbnail alert alert-warning">
      <div class="caption">
      <hgroup>
        <h2><?php echo $count_indicators->total ?></h2>
        <h3>Total Indicators</h3>
      </hgroup>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-md-4">
    <div class="thumbnail alert alert-success">
      <div class="caption">
      <hgroup>
        <h2><?php echo number_format($total_count->total,0) ?></h2>
        <h3>Data Points</h3>
      </hgroup>
      </div>
    </div>
  </div>
</div>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>


  <ul class="nav nav-tabs">
    <?php foreach ($options as $key => $value) { ?>
      <li<?php if ($page == $key) { echo ' class="active"'; } ?>><a href="page/casestudies/<?php echo $key ?>"><?php echo $value ?></a></li>
    <?php } ?>
  </ul>

  <?php if ($page == "overview") { ?>

  <table class="table table-striped ellipsis">
    <tr>
      <th class="large">City</th>
      <th class="small">Year</th>
      <th class="small">
      <span class="explanation" data-toggle="tooltip" data-placement="bottom"  title="This displays the total number of indicators that have been extracted from this study, so far">
        Quantity
        <i class="fa fa-question-circle"></i>
      </span>
      </th>
      <th class="large">Publication</th>
      <th class="large">Authors</th>
      <th class="small hide">Information</th>
    </tr>
  <?php foreach ($list as $row) { ?>
    <tr>
      <td><a href="casestudy/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
      <td><?php echo $row['year'] ?></td>
      <td><a class="badge badge-info" href="casestudy/<?php echo $row['id'] ?>"><?php echo (int)$study_count[$row['id']] ?></a></td>
      <td><a href="publication/<?php echo $row['paper'] ?>"><?php echo $row['title'] ?></a></td>
      <td><?php echo $row['author'] ?></td>
      <td class="hide">
        <a href="analysis/<?php echo $row['id'] ?>/2"><i class="fa fa-comments-o"></i></a>
        <a href="analysis/<?php echo $row['id'] ?>/1"><i class="fa fa-bar-chart-o"></i></a>
        <a href="analysis/<?php echo $row['id'] ?>/3"><i class="fa fa-user"></i></a>
      </td>
    </tr>
  <?php } ?>
  </table>

  <?php } ?>

  <?php if ($page == "indicators") { ?>

  <table class="table table-striped ellipsis">
    <tr>
      <th class="small">Area</th>
      <th class="small">Sub-area</th>
      <th class="large">Indicator</th>
      <th class="small">
      <span class="explanation" data-toggle="tooltip" data-placement="bottom"  title="This displays the total number of indicators that have been extracted from this study, so far">
        Quantity
        <i class="fa fa-question-circle"></i>
      </span>
      </th>
    </tr>
  <?php foreach ($list as $row) { ?>
    <tr>
      <td><a href="data/areas/<?php echo $row['id'] ?>"><?php echo $row['area'] ?></a></td>
      <td><a href="data/subareas/<?php echo $row['id'] ?>"><?php echo $row['subarea'] ?></a></td>
      <td><a href="data/indicators/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
      <td><a class="badge badge-info" href="data/indicators/<?php echo $row['id'] ?>"><?php echo (int)$row['total'] ?></a></td>
    </tr>
  <?php } ?>
  </table>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

<script type="text/javascript">
$(function(){
    $('[data-toggle="tooltip"]').tooltip({
      container: 'body'
    });
});
</script>

  </body>
</html>
