<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 2;
$sub_page = 7;

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM mfa_dataset WHERE id = $id");

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("DELETE FROM mfa_industries_labels WHERE id = $delete AND dataset = $id LIMIT 1");
  $print = "Activity was deleted";
}

$list = $db->query("SELECT * FROM mfa_industries_labels WHERE dataset = $id ORDER BY score, type");
foreach ($list as $row) {
  $labels[$row['type']][$row['score']] = $row['label'];
}


$scores = range(1,5);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <link rel="stylesheet" href="css/sidebar.css" />
    <title>Industry Size Configuration | <?php echo $info->name ?> | OMAT | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>
<?php require_once 'include.omatheader.php'; ?>

  <h1>Industry Size Configuration</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Industry Size Configuration</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <div class="alert alert-info">
    Each industry size can be classified using a 1-5 rating. This can be used for both mass and value. These ratings make
    it easy to create quick profiles of each industry, and get a better grasp at how they compare to each other and where 
    time should best be spent on. This section allows you to define what each rating means. 
  </div>

  <h2>Mass Scores</h2>

  <table class="table table-striped">
    <tr>
      <th>Score</th>
      <th>Label</th>
      <th>Edit</th>
    </tr>
  <?php foreach ($scores as $score) { ?>
    <tr>
      <td><?php echo $score ?></td>
      <td><?php echo $labels['mass'][$score] ?></td>
      <td><a href="omat/<?php echo $project ?>/maintenance-industrysize/<?php echo $score ?>/mass" class="btn btn-primary">Edit</a></td>
    </tr>
  <?php } ?>
  </table>

  <h2>Value Scores</h2>
  <table class="table table-striped">
    <tr>
      <th>Score</th>
      <th>Label</th>
      <th>Edit</th>
    </tr>
  <?php foreach ($scores as $score) { ?>
    <tr>
      <td><?php echo $score ?></td>
      <td><?php echo $labels['value'][$score] ?></td>
      <td><a href="omat/<?php echo $project ?>/maintenance-industrysize/<?php echo $score ?>/value" class="btn btn-primary">Edit</a></td>
    </tr>
  <?php } ?>
  </table>

<?php require_once 'include.omatfooter.php'; ?>
<?php require_once 'include.footer.php'; ?>

  </body>
</html>
