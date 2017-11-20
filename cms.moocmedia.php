<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 21;

$id = (int)$_GET['id'];
$list = $db->query("SELECT * FROM mooc_media WHERE module = $id ORDER BY position");

$info = $db->record("SELECT * FROM mooc_modules WHERE id = $id");
$mooc = 1;
$mooc_info = $db->record("SELECT * FROM mooc WHERE id = $mooc");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>MOOC Media | <?php echo SITENAME ?></title>
  </head>

  <body class="notranslate">

<?php require_once 'include.header.php'; ?>

  <h1>MOOC Media</h1>

  <ol class="breadcrumb">
    <li class="active"><a href="cms.moocs.php">MOOCs</a></li>
    <li><a href="cms.modules.php?id=<?php echo $mooc_info->id ?>"><?php echo $mooc_info->name ?></a></li>
    <li><a href="cms.module.php?id=<?php echo $info->id ?>"><?php echo $info->title ?></a></li>
    <li>Media</li>
  </ol>

  <p><a href="cms.media.php?module=<?php echo $id ?>" class="btn btn-info">Add Media Object</a></p>

  <table class="table table-striped">
    <tr>
      <th>Title</th>
      <th>URL</th>
      <th>Position</th>
      <th>Options</th>
    </tr>
    <?php foreach ($list as $row) { ?>
    <tr>
      <td><a href="cms.media.php?id=<?php echo $row['id'] ?>"><?php echo $row['title'] ?></a></td>
      <td><?php echo $row['url'] ?></td>
      <td><?php echo $row['position'] ?></td>
      <td>
        <a href="cms.media.php?id=<?php echo $row['id'] ?>">Edit</a>
      </td>
    </tr>
  <?php } ?>
  </table>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
