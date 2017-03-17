<?php
require_once 'functions.php';

$load_menu = 1;
$sub_page = 1;
$section = 6;
$page = 2;
$id = (int)$_GET['id'];

$info = $db->record("SELECT * FROM mfa_materials WHERE id = $id");

$list = $db->query("SELECT mfa_data.*, mfa_scales.name AS scalename, mfa_sources.name AS sourcename
FROM mfa_data
  LEFT JOIN mfa_scales ON mfa_data.scale = mfa_scales.id
  LEFT JOIN mfa_sources ON mfa_data.source_id = mfa_sources.id
WHERE mfa_data.material = $id
  ORDER BY mfa_data.year, mfa_data.scale");

if ($info->mfa_group) {
  $groupinfo = $db->record("SELECT * FROM mfa_groups WHERE id = {$info->mfa_group}");
  $project = $groupinfo->dataset;
  $projectinfo = $db->record("SELECT * FROM mfa_dataset WHERE id = $project");
}
require_once 'functions.omat.php';

if ($_GET['message'] ==  "delete-all") {
  $db->query("DELETE FROM mfa_data WHERE material = $id");
  header("Location: " . URL . "omat/data/$id/all-gone");
  exit();
}


if ($_GET['message'] == 'deleted') {
  $print = "Data point was deleted";
} elseif ($_GET['message'] == 'saved') {
  $print = "Information was saved";
} elseif ($_GET['message'] == "all-gone") {
  $print = "All data points have been deleted";
}

$notes = $db->record("SELECT COUNT(*) AS total FROM mfa_materials_notes WHERE material = $id");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->name ?> | <?php echo SITENAME ?></title>
    <style type="text/css">
    #google_translate_element{position:absolute}
    a.right{float:right}
    table {border:1px solid #ccc; width:100px;table-layout: fixed;}
    th, td { max-width:150px;white-space:nowrap; overflow:hidden; text-overflow: ellipsis; }
    th.large, td.large{max-width:250px}
    th.small, td.small{max-width:70px}
    </style>
  </head>

  <body class="omat">

<?php require_once 'include.header.php'; ?>

  <a href="omat/material-comments/<?php echo $id ?>" class="btn btn-warning right">
  <i class="fa fa-comments"></i>
  Comments
  <?php if ($notes->total) { ?> (<?php echo $notes->total ?>)<?php } ?>
  </a>

  <h1><?php echo $info->name ?></h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/manage">Data</a></li>
    <li><a href="omat/datagroup/<?php echo $groupinfo->id ?>"><?php echo $groupinfo->name ?></a></li>
    <li class="active"><?php echo $info->name ?></li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <div class="alert alert-info">
    A total of <strong><?php echo count($list) ?></strong> data <?php echo count($list) == 1 ? "point was" : "points were" ?> found.
  </div>

  <?php if (count($list)) { ?>

    <table class="table table-striped">
      <tr>
        <?php if ($projectinfo->multiscale) { ?>
          <th>Scale</th>
        <?php } ?>
        <th class="small">Year</th>
        <th>Quantity</th>
        <th class="large">Source</th>
        <th class="large">Comments</th>
        <th>Actions</th>
      </tr>
    <?php foreach ($list as $row) { ?>
      <tr>
        <?php if ($projectinfo->multiscale) { ?>
          <td><?php echo $row['scalename'] ?></td>
        <?php } ?>
        <td class="small"><?php echo $row['year'] ?></td>
        <td>
          <?php echo number_format($row['data'],$projectinfo->decimal_precision) ?>
          <?php if ($row['multiplier'] != 1) { ?>
            <span class="badge">x <?php echo $row['multiplier'] ?></span>
          <?php } ?>
        </td>
        <td class="large">
        <?php if ($row['sourcename']) { ?>
          <a href="omat/<?php echo $project ?>/viewsource/<?php echo $row['source_id'] ?>"><?php echo $row['sourcename'] ?></a>
        <?php } elseif ($row['source_link']) { ?>
          <a href="<?php echo $row['source_link'] ?>"><?php echo $row['source'] ? $row['source'] : $row['source_link'] ?></a>
        <?php } else { ?>
          <?php echo $row['source'] ?>
        <?php } ?>
        </td>
        <td class="large"><?php echo $row['comments'] ?></td>
        <td>
          <a title="Edit" href="omat/data-edit/<?php echo $row['id'] ?>" class="btn btn-primary"><i class="fa fa-edit"></i></a>
          <a title="Delete" href="omat/data-delete/<?php echo $row['id'] ?>" class="btn btn-danger" onclick="javascript:return confirm('Are you sure?')">
            <i class="fa fa-trash"></i>
          </a>
        </td>
      </tr>
    <?php } ?>
    </table>

  <?php } ?>

  <p>
    <a href="omat/data-entry/<?php echo $id ?>" class="btn btn-success">Add single data point</a>
    <a href="omat/multiple-data-entry/<?php echo $id ?>" class="btn btn-success">Add multiple points</a>
    <a href="omat/data/<?php echo $id ?>/delete-all" class="btn pull-right btn-danger" onclick="javascript:return confirm('You are about to delete all these data points (<?php echo count($list) ?> in total). Are you sure?')">Delete all data points</a>
  </p>

  <p><a href="omat/datagroup/<?php echo $info->mfa_group ?>" class="btn btn-primary">&laquo; Back to data group</a></p>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
