<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 10;

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $count = $db->query("SELECT * FROM tags_papers WHERE tag = $delete");
  if (count($count)) {
    die("Sorry, you can only delete a tag if there are no papers associated with it");
  }
  $db->query("DELETE FROM tags WHERE id = $delete LIMIT 1");
  $print = "Tag was deleted";
}

$list = $db->query("SELECT tags.*, tags_parents.name AS parentname 
FROM tags JOIN tags_parents ON tags.parent = tags_parents.id ORDER BY tags.parent, tags.tag");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Tags | <?php echo SITENAME ?></title>
    <style type="text/css">
      table {border:1px solid #ccc; width:100px;table-layout: fixed;}
      th, td { max-width:100px;white-space:nowrap; overflow:hidden; text-overflow: ellipsis; }
    </style>
  </head>

  <body class="notranslate">

<?php require_once 'include.header.php'; ?>

  <h1>Tags</h1>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <table class="table table-striped">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Description</th>
      <th>Actions</th>
    </tr>
    <?php foreach ($list as $row) { ?>
    <?php if ($row['parentname'] != $parent) { ?>
    <tr>
      <th colspan="3"><?php echo $row['parentname'] ?></th>
    </tr>
    <?php } $parent = $row['parentname']; ?>
    <tr>
      <td><?php echo $row['id'] ?></td>
      <td><?php echo $row['tag'] ?></td>
      <td><?php echo $row['description'] ?></td>
      <td>
        <a href="tags/<?php echo $row['id'] ?>/edit" class="btn btn-default">Edit</a>
        <a href="cms.tags.php?delete=<?php echo $row['id'] ?>" class="btn btn-danger" onclick="javascript:return confirm('Are you sure?')">Delete tag</a>
      </td>
    </tr>
  <?php } ?>
  </table>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
