<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 3;

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $check = $db->record("SELECT id FROM content_authors_pivot WHERE author_id = $delete");
  if ($check->id) {
    die("Sorry, you can not delete an author with published posts");
  }
  $db->query("DELETE FROM content_authors WHERE id = $delete LIMIT 1");
  $print = "Author deleted";
}

$authors = $db->query("SELECT * FROM content_authors ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Blog Authors | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Blog Authors</h1>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <table class="table table-striped">
    <tr>
      <th>Name</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
    <?php foreach ($authors as $row) { ?>
    <tr>
      <td><?php echo $row['name'] ?></td>
      <td><a href="cms.author.php?id=<?php echo $row['id'] ?>" class="btn btn-info">Edit</a></td>
      <td><a href="cms.blogauthorlist.php?delete=<?php echo $row['id'] ?>" class="btn btn-danger" onclick="javascript:return confirm('Are you sure?')">Delete</a></td>
    </tr>
  <?php } ?>
  </table>

  <p><a href="cms/author" class="btn btn-primary">Add author</a></p>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
