<?php
$skip_login = true;
require_once 'functions.php';
require_once 'functions.omat.php';
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
  JOIN analysis_options o ON analysis.analysis_option = o.id
WHERE analysis.case_study = $id 
ORDER BY o.name, analysis.year");

foreach ($indicators as $row) {
  if ($row['year']) {
    $show_table[$row['name']] = true;
    $table[$row['name']][$row['year']] = $row['result'];
    $table[$row['name']][$row['year']] = $row['result'];
  } 
  if ($row['notes']) {
    $infolist[$row['name']][] = $row['notes'];
  }
  $previous = $row['name'];
}

if (defined("ADMIN") || $_COOKIE['preview']) {
  $indicator_list = $db->query("SELECT * FROM analysis_options_types ORDER BY name");
  if ($_GET['delete']) {
    if (!count($indicators)) {
      $db->query("DELETE FROM case_studies WHERE id = $id");
      header("Location: " . URL . "casestudies.php?deleted=true");
      exit();
    }
  }
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

  <?php if (!$_COOKIE['preview']) { ?>
  <div class="well">
    <em>We soon plan to provide per-capita material flow data from this paper
    here. Stay tuned!</em>
  </div>
  <?php } ?>

  <?php if ($indicators && (defined("ADMIN") || $_COOKIE['preview'])) { ?>

    <h2>Data</h2>

     <table class="table table-striped">
       <tr>
         <th>Material</th>
         <th>Year</th>
         <th>Value</th>
         <th>Comments</th>
       </tr>
       <?php foreach ($indicators as $row) { ?>
       <?php
         if ((int)$row['result'] == $row['result']) {
           $decimals = 0;
         } else {
           $decimals = 2;
         }
       ?>
       <tr>
         <?php if ($row['name'] == $name) { ?>
           <td></td>
         <?php } else { ?>
         <td><?php echo $row['name'] ?></td>
         <?php } ?>
         <?php $name = $row['name']; ?>

         <td><?php echo $row['year'] ?></td>
         <td><?php echo number_format($row['result'],$decimals) ?> <?php echo $row['measure'] ?></td>
         <td><?php echo $row['notes'] ?></td>
       </tr>
     <?php } ?>
     </table>

  <?php } ?>

  <?php if (defined("ADMIN")) { ?>
  <?php if (!$indicators) { ?>
      <a href="casestudy.php?id=<?php echo $id ?>&amp;delete=true" onclick="javascript:return confirm('Are you sure?')" class="btn btn-danger">Delete this as a regional case study</a>
  <?php } ?>
    <h2>Manage meta information</h2>
    <ul class="nav">
    <?php foreach ($indicator_list as $row) { ?>
      <li><a href="analysis/<?php echo $id ?>/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></li>
    <?php } ?>
    </ul>

  <?php } else { ?>

    <div class="alert alert-warning">
      You can help! We want to go through this study and extract the
      per-capita material flow data from it. By entering the numbers for
      each study, we can generate one large overview of the material flow data
      found on an urban level for many different cities, materials and year. Over
      time, this could provide very useful and comparative insights for
      researchers. We are working on this right now (first half of 2016). Are you
      willing to help? <a href="page/contact">Get in touch!</a>
    </div>

  <?php } ?>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
