<?php
require_once 'functions.php';

$load_menu = 1;
$sub_page = 1;
$section = 6;

$id = (int)$_GET['id'];

$list = $db->query("SELECT *,
  (SELECT COUNT(*) FROM mfa_materials_notes WHERE material = mfa_materials.id) AS comments,
  (SELECT COUNT(*) FROM mfa_material_links WHERE material = mfa_materials.id) AS links
FROM mfa_materials WHERE mfa_group = $id ORDER BY code");
$info = $db->record("SELECT * FROM mfa_groups WHERE id = $id");

$project = $info->dataset;
require_once 'functions.omat.php';

if ($_GET['delete-item']) {
  $delete = (int)$_GET['delete-item'];
  $db->query("DELETE FROM mfa_materials WHERE id = $delete LIMIT 1");
  header("Location: " . URL . "omat/datagroup/$id/deleted");
  exit();
}

if ($_GET['message'] == 'deleted') {
  $print = "Data group was deleted.";
} elseif ($_GET['message'] == 'saved') {
  $print = "Information was saved.";
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->name ?> | <?php echo SITENAME ?></title>
    <style type="text/css">
    .align-right{text-align:right}
    #google_translate_element{position:absolute}
    </style>
  </head>

  <body class="omat">

<?php require_once 'include.header.php'; ?>
<?php require_once 'include.omatheader.php'; ?>

  <a href="omat/<?php echo $project ?>/reports-table/<?php echo $id ?>" class="btn btn-primary pull-right">View data table</a>
  <h1>Material Group: <?php echo $info->name ?></h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/manage">Data</a></li>
    <li class="active"><?php echo $info->name ?></li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <div class="alert alert-info">
    A total of <strong><?php echo count($list) ?></strong> <?php echo count($list) == 1 ? 'material' : 'materials' ?> were found.
  </div>

  <?php if (count($list)) { ?>

    <table class="table table-striped">
      <tr>
        <th colspan="2">Data</th>
        <th class="align-right">Options</th>
      </tr>
    <?php foreach ($list as $row) { $row['comments'] = $row['comments']+$row['links']; ?>
      <tr>
        <td><?php echo $row['code'] ?></td>
        <td><a href="omat/data/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
        <td class="align-right">
          <a title="Comments and associated resources" href="omat/material-comments/<?php echo $row['id'] ?>" class="btn btn-warning">
          <i class="fa fa-comments"></i> 
            <?php if ($row['comments']) { ?>(<?php echo $row['comments'] ?>)<?php } ?>
          </a>
          <a title="Edit" href="omat/material-entry/<?php echo $row['id'] ?>" class="btn btn-primary">
            <i class="fa fa-edit"></i>
          </a>
          <a title="Delete" href="omat/datagroup/<?php echo $id ?>/delete-item/<?php echo $row['id'] ?>" class="btn btn-danger" onclick="javascript:return confirm('Are you sure? All data in this section will be removed as well!')">
            <i class="fa fa-trash"></i>
          </a>
        </td>
      </tr>
    <?php } ?>
    </table>

  <?php } ?>

  <p><a href="omat/material-entry/group-<?php echo $id ?>" class="btn btn-success">Add a new material</a></p>

  <p><a href="omat/manage/<?php echo $info->dataset ?>" class="btn btn-primary">&laquo; Back to the main data groups</a></p>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
