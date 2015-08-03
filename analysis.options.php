<?php
require_once 'functions.php';
$section = 5;
$page = 99;

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM analysis_options WHERE id = $id");

if (!$info) {
  die("Not found");
}

$list = $db->query("SELECT *, case_studies.name, papers.title, analysis.year
FROM analysis 
  JOIN analysis_options o ON analysis.analysis_option = o.id
  JOIN case_studies ON analysis.case_study = case_studies.id
  JOIN papers ON case_studies.paper = papers.id
WHERE analysis.analysis_option = $id
ORDER BY case_studies.name, analysis.year");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->name ?> | Regional Case Studies | <?php echo SITENAME ?></title>
    <style type="text/css">
    .table.ellipsis{border-top:0}
    .table > tbody > tr > th {border-top:0}
    .optionlist{max-width:500px}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h2><?php echo $info->name ?></h2>

  <table class="table table-striped">
    <tr>
      <th>Region</th>
      <th>Year</th>
      <th>Value</th>
      <th>Source</th>
    </tr>
  <?php foreach ($list as $row) { ?>
    <tr>
      <td><a href="casestudy/<?php echo $row['case_study'] ?>"><?php echo $row['name'] ?></a></td>
      <td><?php echo $row['year'] ?></td>
      <td><?php echo $row['result'] ? number_format($row['result'],2) : $row['notes'] ?></td>
      <td><a href="publication/<?php echo $row['paper'] ?>"><?php echo $row['title'] ?></a></td>
    </tr>
  <?php } ?>
  </table>  

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
