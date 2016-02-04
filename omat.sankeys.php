<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 7;

$id = (int)$project;

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("DELETE FROM mfa_sankey WHERE id = $delete AND dataset = $project LIMIT 1");
  $print = "The sankey diagram was deleted";
}

$list = $db->query("SELECT * FROM mfa_sankey WHERE dataset = $project ORDER BY name");

if ($_GET['saved']) {
  $print = "Information was saved";
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Sankey Diagrams | <?php echo SITENAME ?></title>
    <style type="text/css">
    a.pull-right{margin-left:5px}
    </style>
  </head>

  <body class="omat">

<?php require_once 'include.header.php'; ?>

  <a href="omat/<?php echo $project ?>/sankey/0" class="btn btn-success pull-right"><i class="fa fa-cogs"></i> Add sankey diagram</a>

  <h1>Sankey Diagrams</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Sankey Diagrams</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <div class="alert alert-info">
    <strong><?php echo count($list) ?></strong> sankey diagrams found.
  </div>

  <?php if (count($list)) { ?>

    <table class="table table-striped ellipsis">
      <tr>
        <th class="long">Name</th>
        <th class="short">Edit</th>
        <th class="short">Delete</th>
      </tr>
    <?php foreach ($list as $row) { ?>
      <tr>
        <td><a href="omat/<?php echo $project ?>/viewsankey/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
        <td>
          <a href="omat/<?php echo $project ?>/sankey/<?php echo $row['id'] ?>" class="btn btn-primary">Edit</a>
          <a href="omat/<?php echo $project ?>/sankeynodes/<?php echo $row['id'] ?>" class="btn btn-primary">Nodes</a>
        </td>
        <td><a href="omat/<?php echo $project ?>/sankeys/delete/<?php echo $row['id'] ?>" class="btn btn-danger" onclick="javascript:return confirm('Are you sure?')">Delete</a></td>
      </tr>
    <?php } ?>
    </table>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
