<?php
require_once 'functions.php';
$section = 5;
$page = 99;

$id = (int)$_GET['id'];

$info = $db->record("SELECT papers.*, case_studies.* 
FROM case_studies 
  JOIN papers
  ON case_studies.paper = papers.id
  WHERE case_studies.id = $id");

$indicators = $db->query("SELECT * 
FROM analysis 
  JOIN analysis_options o ON analysis.option = o.id
WHERE analysis.case_study = $id AND o.type = 1 
ORDER BY o.name, analysis.year");

foreach ($indicators as $row) {
  if ($row['name'] == $previous) {
    $show_table[$row['name']] = true;
    $table[$row['name']][$row['year']] = $row['result'];
  }
  $previous = $row['name'];
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->name ?> | Material Flow Analysis Case Studies | <?php echo SITENAME ?></title>
    <style type="text/css">
    .table.ellipsis{border-top:0}
    .table > tbody > tr > th {border-top:0}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Regional Material Flow Analysis</h1>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <dl class="dl-horizontal">
    <dt>Paper</dt>
    <dd><a href="publication/<?php echo $info->paper ?>"><?php echo $info->title ?></a></dd>
    
    <dt>Author(s)</dt>
    <dd><?php echo $info->author ?></dd>

    <dt>City/Region</dt>
    <dd><?php echo $info->name ?></dd>

  </dl>

  <div class="well">
    <em>We soon plan to provide per-capita material flow data from this paper
    here. Stay tuned!</em>
  </div>

  <?php if ($indicators && LOCAL) { ?>

    <h2>Indicators</h2>

    <?php if (is_array($show_table)) { ?>
    <?php foreach ($show_table as $value => $key) { ?>
      <h3><?php echo $value ?></h3>
      <table class="table table-striped">
        <tr>
          <th>Year</th>
          <th>Value</th>
        </tr>
        <?php foreach ($table[$value] as $year => $data) { ?>
          <tr>
            <td><?php echo $year ?></td>
            <td><?php echo $data ?></td>
          </tr>
        <?php } ?>
        </table>
      <?php } ?>
    <?php } ?>
  <?php } ?>

  <div class="alert alert-warning">
    You can help! We want to go through this study and extract the
    per-capita material flow data from it. By entering the numbers for
    each study, we can generate one large overview of the material flow data
    found on an urban level for many different cities, materials and year. Over
    time, this could provide very useful and comparative insights for
    researchers. We would like to work on this in June-August 2015. Are you
    willing to help? <a href="page/contact">Get in touch!</a>
  </div>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
