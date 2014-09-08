<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 3;

$id = (int)$project;

$status = (int)$_GET['status'];

if ($status) {
  $sql .= " AND s.status = $status";
  $status_name = $db->record("SELECT * FROM mfa_status_options WHERE id = $status");
}

$list = $db->query("SELECT s.*, t.name AS type, o.status
FROM mfa_sources s
  LEFT JOIN mfa_sources_types t ON s.type = t.id
  JOIN mfa_status_options o ON s.status = o.id
WHERE s.dataset = $id $sql
ORDER BY s.name");

$types = $db->query("SELECT * FROM mfa_sources_types WHERE dataset = $id ORDER BY name");

$status_options = $db->query("SELECT * FROM mfa_status_options ORDER BY id");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Sources | <?php echo SITENAME ?></title>
    <style type="text/css">
    .right{float:right;margin-left:6px}
    th{width:120px;}
    th.long{width:auto}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <a href="omat/<?php echo $id ?>/source/0" class="btn btn-success right">Add Source</a>

  <div class="dropdown right">
    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
      <?php echo $_GET['status'] ? "Status: <strong>" . $status_name->status . "</strong>" : 'Filter by Status'; ?>
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
      <li role="presentation"<?php if (!$_GET['status']) { echo ' class="active"'; } ?>>
        <a role="menuitem" tabindex="-1" href="omat.sources.php?project=<?php echo $id ?>&amp;type=<?php echo $type ?>">
          <?php if (!$_GET['status']) { ?>
            <i class="fa fa-check"></i>
          <?php } ?>
          All
        </a>
      </li>
    <?php foreach ($status_options as $row) { ?>
      <li role="presentation"<?php if ($_GET['status'] == $row['id']) { echo ' class="active"'; } ?>>
        <a role="menuitem" tabindex="-1" href="omat.sources.php?project=<?php echo $id ?>&amp;type=<?php echo $type ?>&amp;status=<?php echo $row['id'] ?>">
          <?php if ($row['id'] == $_GET['status']) { ?>
            <i class="fa fa-check"></i>
          <?php } ?>
          <?php echo $row['status'] ?>
        </a>
      </li>
    <?php } ?>
    </ul>
  </div>


  <h1>Sources</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Sources</li>
  </ol>

  <div class="alert alert-info">
    <strong><?php echo count($list) ?></strong> sources found.
  </div>

  <?php if (count($list)) { ?>

    <table class="table table-striped">
      <tr>
        <th class="long">Name</th>
        <?php if (count($types)) { ?>
          <th>Type</th>
        <?php } ?>
        <th>Created</th>
        <th>Status</th>
      </tr>
    <?php foreach ($list as $row) { ?>
      <tr>
        <td><a href="omat/<?php echo $project ?>/viewsource/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
        <?php if (count($types)) { ?>
          <td><?php echo $row['type'] ?></td>
        <?php } ?>
        <td><a href="omat/<?php echo $project ?>/source/<?php echo $row['id'] ?>">Edit</a></td>
        <td><?php echo $row['status'] ?></td>
      </tr>
    <?php } ?>
    </table>

  <?php } ?>

  <a href="omat/<?php echo $id ?>/source/0" class="btn btn-success">Add Source</a>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
