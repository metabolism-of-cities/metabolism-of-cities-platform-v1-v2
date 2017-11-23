<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 21;

$id = 1;
$list = $db->query("SELECT * FROM mooc_modules ORDER BY title");
$info = $db->record("SELECT * FROM mooc WHERE id = $id");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>MOOC Modules | <?php echo SITENAME ?></title>
  </head>

  <body class="notranslate">

<?php require_once 'include.header.php'; ?>

  <h1>MOOC Modules</h1>

  <ol class="breadcrumb">
    <li class="active"><a href="cms.moocs.php">MOOCs</a></li>
    <li><?php echo $info->name ?></li>
  </ol>

  <p><a href="cms.module.php" class="btn btn-info">Add Module</a></p>

  <table class="table table-striped">
    <tr>
      <th>Title</th>
      <th>Options</th>
    </tr>
    <?php foreach ($list as $row) { ?>
    <tr>
      <td><a href="cms.module.php?id=<?php echo $row['id'] ?>"><?php echo $row['title'] ?></a></td>
      <td>
        <a href="mooc/<?php echo $row['id'] ?>">View</a> |
        <a href="cms.moocmedia.php?id=<?php echo $row['id'] ?>">Media</a> |
        <a href="cms.moocquestions.php?id=<?php echo $row['id'] ?>">Questions</a> |
        <a href="cms.module.php?id=<?php echo $row['id'] ?>">Edit</a>
      </td>
    </tr>
  <?php } ?>
  </table>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
