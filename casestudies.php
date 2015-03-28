<?php
require_once 'functions.php';
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
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Material Flow Analysis Case Studies | <?php echo SITENAME ?></title>
    <style type="text/css">
    .table.ellipsis{border-top:0}
    .table > tbody > tr > th {border-top:0}
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

  <ul class="nav nav-tabs">
    <li<?php if (!$type) { echo ' class="active"'; } ?>><a href="page/casestudy">All</a></li>
    <?php foreach ($studies as $key => $value) { ?>
      <li<?php if ($type == $key) { echo ' class="active"'; } ?>><a href="page/casestudy/<?php echo $key ?>"><?php echo $value ?></a></li>
    <?php } ?>
  </ul>

  <?php if ($list) { ?>

  <table class="table table-striped ellipsis">
    <tr>
      <th class="large">Region</th>
      <th class="small">Year</th>
      <th class="large">Paper</th>
      <th class="large">Authors</th>
      <th class="small">Information</th>
    </tr>
  <?php foreach ($list as $row) { ?>
    <tr>
      <td><a href="casestudy/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
      <td><?php echo $row['year'] ?></td>
      <td><a href="publication/<?php echo $row['paper'] ?>"><?php echo $row['title'] ?></a></td>
      <td><?php echo $row['author'] ?></td>
      <td>
        <a href="analysis/<?php echo $row['id'] ?>/2"><i class="fa fa-comments-o"></i></a>
        <a href="analysis/<?php echo $row['id'] ?>/1"><i class="fa fa-bar-chart-o"></i></a>
      </td>
    </tr>
  <?php } ?>
  </table>

  <?php } ?>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
