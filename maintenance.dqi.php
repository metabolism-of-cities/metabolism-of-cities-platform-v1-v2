<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 2;
$sub_page = 1;

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM mfa_dataset WHERE id = $id");

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("DELETE FROM dqi_sections WHERE id = $delete AND dataset = $id LIMIT 1");
  $print = "Data Quality Indicator was deleted";
}

if ($_GET['standard']) {
  $list = $db->query("SELECT * FROM dqi_sections WHERE dataset IS NULL");
  foreach ($list as $row) {
    $post = array(
      'name' => mysql_clean($row['name']),
      'dataset' => $id,
    );
    $db->insert("dqi_sections",$post);
    $section = $db->lastInsertId();
    $db->query("INSERT INTO dqi_classifications 
      (section, score, name)
    SELECT $section, score, name
      FROM dqi_classifications WHERE section = {$row['id']}");
  }
  header("Location: " . URL . "omat/$id/maintenance-dqi/loaded");
  exit();
}

if ($_GET['loaded']) {
  $print = "The standard DQIs have been loaded. You can review and edit them using the list below.";
}

$list = $db->query("SELECT * FROM dqi_sections WHERE dataset = $id ORDER BY name");
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

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Data Quality Indicators</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <?php if (!count($list)) { ?>

  <div class="alert alert-info">
    In this section you can define Data Quality Indicators (DQI) for your project. You can create 
    the indicators first (for instance 'Reliability' or 'Completeness'), and then define the different
    scores. 
  </div>

  <ul class="plainlist">
    <li><a class="btn btn-primary" href="omat/<?php echo $project ?>/maintenance-dqi/standard">Load a standard set of indicators</a></li>
    <li><a class="btn btn-primary" href="omat/<?php echo $project ?>/maintenance-dqi-indicator/0">Load an empty skeleton</a></li>
  </ul>

  <?php } else { ?>
    <table class="table table-striped">
      <tr>
        <th>Name</th>
        <th>Edit</th>
        <th>Delete</th>
      </tr>
    <?php foreach ($list as $row) { ?>
      <tr>
        <td><a href="omat/<?php echo $project ?>/maintenance-dqi-section/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
        <td><a href="omat/<?php echo $project ?>/maintenance-dqi-indicator/<?php echo $row['id'] ?>" class="btn btn-primary">Edit</a></td>
        <td><a href="omat/<?php echo $project ?>/maintenance-dqi/<?php echo $row['id'] ?>/delete" class="btn btn-danger" onclick="javascript:return confirm('Are you sure?')">Delete</a></td>
      </tr>
    <?php } ?>
    </table>
    <p><a href="omat/<?php echo $project ?>/maintenance-dqi-indicator/0" class="btn btn-success">Add indicator</a></p>
  <?php } ?>

<?php require_once 'include.omatfooter.php'; ?>
<?php require_once 'include.footer.php'; ?>

  </body>
</html>
