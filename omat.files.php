<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 4;

$id = (int)$project;

$list = $db->query("SELECT s.name AS source_name, f.*
FROM mfa_files f
  JOIN mfa_sources s ON f.source = s.id
  LEFT JOIN mfa_sources_types t ON s.type = t.id
WHERE s.dataset = $id
ORDER BY f.name");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Sources | <?php echo SITENAME ?></title>
    <style type="text/css">
    .right{float:right;margin-left:6px}
    th{width:190px;}
    th.long{width:300px}
    th.short{width:50px}
    </style>
  </head>

  <body class="omat">

<?php require_once 'include.header.php'; ?>

  <h1>Files</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Files</li>
  </ol>

  <div class="alert alert-info">
    <strong><?php echo count($list) ?></strong> files found.
  </div>

  <?php if (count($list)) { ?>

    <table class="table table-striped ellipsis">
      <tr>
        <th class="long">File</th>
        <th>Source</th>
        <th>Original Name</th>
        <th class="short">Uploaded</th>
        <th class="short">Size</th>
      </tr>
    <?php foreach ($list as $row) { ?>
      <tr>
        <td><a href="omat/<?php echo $project ?>/file/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
        <td><a href="omat/<?php echo $project ?>/viewsource/<?php echo $row['source'] ?>"><?php echo $row['source_name'] ?></a></td>
        <td><?php echo $row['original_name'] ?></td>
        <td><?php echo format_date("M d, Y", $row['uploaded']) ?></td>
        <td><?php echo number_format($row['size']/1024,1) ?> Mb</td>
      </tr>
    <?php } ?>
    </table>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
