<?php
$skip_login = true;
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 5;
$page = 99;

$sql = false;
if ((int)$_GET['message'] > 0) {
  $type = (int)$_GET['message'];
  $sql = "WHERE EXISTS (SELECT * FROM tags_papers WHERE tags_papers.paper = papers.id AND tags_papers.tag = $type)";
}

$list = $db->query("SELECT papers.*, case_studies.* 
FROM case_studies 
  JOIN papers
  ON case_studies.paper = papers.id
  $sql 
ORDER BY papers.year, case_studies.name
");

if ($_GET['message'] == 'saved') {
  $print = "Information has been saved";
}

$studies = array(
  87 => "Ecological Footprint",
  85 => "EW MFA",
  54 => "LCA",
  65 => "PIOT",
  86 => "SFA",
);

$options = $db->query("SELECT a.*, t.name AS type_name, t.id AS type,
  (SELECT COUNT(*) FROM analysis WHERE analysis.analysis_option = a.id) AS total
FROM analysis_options_types t
  LEFT JOIN analysis_options a ON a.type = t.id
ORDER BY t.id, a.name");

if ($_GET['deleted']) {
  $print = "Case study was deleted";
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
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Regional Material Flow Analysis</h1>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <p>
    This page provides an overview of case studies that have been done, with in-depth
    details about the methodology, approaches, outcomes and challenges.
  </p>

  <div class="alert alert-info">
    <strong><?php echo count($list) ?></strong> studies found.
  </div>

  <div class="alert alert-warning">
    You can help! We want to go through all these studies and extract the
    per-capita material flow data from each study. By entering the numbers for
    each study, we can generate one large overview of the material flow data
    found on an urban level for many different cities, materials and year. Over
    time, this could provide very useful and comparative insights for
    researchers. We would like to work on this in the second half of 2015. Are you
    willing to help? <a href="page/contact">Get in touch!</a>
  </div>

  <ul class="nav nav-tabs">
    <li<?php if (!$type) { echo ' class="active"'; } ?>><a href="page/casestudies">All</a></li>
    <?php foreach ($studies as $key => $value) { ?>
      <li<?php if ($type == $key) { echo ' class="active"'; } ?>><a href="page/casestudies/<?php echo $key ?>"><?php echo $value ?></a></li>
    <?php } ?>
  </ul>

  <?php if ($list) { ?>

  <table class="table table-striped ellipsis">
    <tr>
      <th class="large">Region</th>
      <th class="small">Year</th>
      <th class="large">Paper</th>
      <th class="large">Authors</th>
      <th class="small hide">Information</th>
    </tr>
  <?php foreach ($list as $row) { ?>
    <tr>
      <td><a href="casestudy/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
      <td><?php echo $row['year'] ?></td>
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

  <?php if (defined("ADMIN")) { ?>
  <h2 id="meta">Meta Information</h2>

  <?php if ($_GET['added']) { ?>

    <div class="alert alert-success">
      Information was saved
    </div>

  <?php } ?>

  <div class="optionlist">

  <?php $type = false; foreach ($options as $row) { ?>

    <?php if ($row['type_name'] != $type ) { ?>
      <?php if ($type) { ?>
        <p>
          <a href="admin.indicators.php?id=<?php echo $prevtype ?: $row['type']; ?>" class="btn btn-warning">
            <i class="fa fa-plus"></i>
            Add Type
          </a>
        </p>
      <?php } ?>

      <h3><?php echo $row['type_name'] ?></h3>
      <?php if ($type) { ?></ul>
      <?php } ?>
      <ul class="nav nav-list">
    <?php } $type = $row['type_name']; ?>

    <li>
      <a href="regional/options/<?php echo $row['id'] ?>/<?php echo flatten($row['name']) ?>">
        <?php echo $row['name'] ?>
        <span class="badge pull-right"><?php echo $row['total'] ?></span>
      </a>
    </li>

  <?php $prevtype = $row['type']; } ?>

  </ul>

  <p>
    <a href="admin.indicators.php?id=<?php echo $prevtype ?>" class="btn btn-warning">
      <i class="fa fa-plus"></i>
      Add Type
    </a>
  </p>

  </div>

  <h3>Manage Groups</h3>

  <p>
    <a href="admin.indicator.php" class="btn btn-warning">
      <i class="fa fa-plus"></i>
        Add Group
    </a>
  </p>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
