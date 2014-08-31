<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 2;
$sub_page = 1;

$id = (int)$_GET['id'];
$project = (int)$_GET['project'];

$info = $db->record("SELECT * FROM dqi_sections WHERE id = $id AND dataset = $project");

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("DELETE FROM dqi_classifications WHERE id = $delete AND section = {$info->id} LIMIT 1");
  $print = "Score was deleted";
}

$list = $db->query("SELECT * FROM dqi_classifications WHERE section = {$info->id}");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <link rel="stylesheet" href="css/sidebar.css" />
    <title>DQI | <?php echo $info->name ?> | OMAT | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>
<?php require_once 'include.omatheader.php'; ?>

  <h1>Data Quality Indicator Settings</h1>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/maintenance-dqi">Data Quality Indicators</a></li>
    <li class="active"><?php echo $info->name ?></li>
  </ol>

  <?php if (count($list)) { ?>

    <table class="table table-striped">
      <tr>
        <th>Score</th>
        <th>Description</th>
        <th>Edit</th>
        <th>Delete</th>
      </tr>
    <?php foreach ($list as $row) { ?>
      <tr>
        <td><?php echo $row['score'] ?></td>
        <td><?php echo $row['name'] ?></td>
        <td><a href="omat/<?php echo $project ?>/maintenance-dqi-score/<?php echo $row['id'] ?>" class="btn btn-primary">Edit</a></td>
        <td><a href="maintenance.dqi-section.php?project=<?php echo $project ?>&amp;id=<?php echo $id ?>&amp;delete=<?php echo $row['id'] ?>" class="btn btn-danger" onclick="javascript:return confirm('Are you sure?')">Delete</a></td>
      </tr>
    <?php } ?>
    </table>
  <?php } ?>

  <p><a href="omat/<?php echo $project ?>/maintenance-dqi-score/section-<?php echo $id ?>" class="btn btn-success">Add score</a></p>

<?php require_once 'include.omatfooter.php'; ?>
<?php require_once 'include.footer.php'; ?>

  </body>
</html>
