<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';

$sub_page = 11;

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("DELETE FROM datavisualizations WHERE id = $id LIMIT 1");
  $print = "Data visualization was deleted";
}

$list = $db->query("SELECT * FROM datavisualizations ORDER BY date DESC");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Dataviz Posts | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Dataviz Posts</h1>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <table class="table table-striped">
    <tr>
      <th>Title</th>
      <th>Date</th>
      <th>Actions</th>
    </tr>
    <?php foreach ($list as $row) { ?>
    <tr>
      <td><a href="datavisualizations/<?php echo $row['id'] ?>-<?php echo flatten($row['title']) ?>"><?php echo $row['title'] ?></a></td>
      <td><?php echo format_date("M d, Y", $row['date']) ?></td>
      <td><a href="cms.dataviz.php?id=<?php echo $row['id'] ?>" class="btn btn-info">Edit</a>
      <a href="datavisualizations/<?php echo $row['id'] ?>-<?php echo flatten($row['title']) ?>" class="btn btn-success">View post</a>
      <a href="cms.datavizlist.php?delete=<?php echo $row['id'] ?>" class="btn btn-danger" onclick="javascript:return confirm('Are you sure?')">Delete</a></td>
    </tr>
  <?php } ?>
  </table>

  <p><a href="cms/dataviz" class="btn btn-primary">Add dataviz post</a></p>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
