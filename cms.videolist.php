<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';

$sub_page = 13;

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("DELETE FROM videos WHERE id = $delete LIMIT 1");
  $print = "Video was deleted";
}

$list = $db->query("SELECT * FROM videos ORDER BY title");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Videos | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Videos</h1>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <table class="table table-striped">
    <tr>
      <th>Title</th>
      <th>Author</th>
      <th>Actions</th>
    </tr>
    <?php foreach ($list as $row) { ?>
    <tr>
      <td><a href="videos/<?php echo $row['id'] ?>-<?php echo flatten($row['title']) ?>"><?php echo smartcut($row['title'],50) ?></a></td>
      <td><?php echo $row['author'] ?></td>
      <td><a href="cms.video.php?id=<?php echo $row['id'] ?>" class="btn btn-info">Edit</a>
      <a href="videos/<?php echo $row['id'] ?>-<?php echo flatten($row['title']) ?>" class="btn btn-success">View video</a>
      <a href="cms.videolist.php?delete=<?php echo $row['id'] ?>" class="btn btn-danger" onclick="javascript:return confirm('Are you sure?')">Delete</a></td>
    </tr>
  <?php } ?>
  </table>

  <p><a href="cms/video" class="btn btn-primary">Add video</a></p>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
