<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';

$sub_page = 1;

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("UPDATE blog SET active = 0 WHERE id = $delete");
  $print = "Blog post was deactivated";
}

if ($_GET['undelete']) {
  $undelete = (int)$_GET['undelete'];
  $db->query("UPDATE blog SET active = 1 WHERE id = $undelete");
  $print = "Blog post was reactivated";
}

$list = $db->query("SELECT * FROM blog ORDER BY date DESC");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Blog Posts | <?php echo SITENAME ?></title>
    <style type="text/css">
    .active-0{opacity:0.6;}
  td {
      max-width: 200px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
  }
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Blog Posts</h1>

  <p><a href="cms.blog.php">Add Blog</a></p>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <table class="table table-striped">
    <tr>
      <th>Title</th>
      <th>Date</th>
      <th>Actions</th>
    </tr>
    <?php foreach ($list as $row) { ?>
    <tr class="active-<?php echo $row['active'] ?>">
      <td><a href="blog/<?php echo $row['id'] ?>-<?php echo flatten($row['title']) ?>"><?php echo $row['title'] ?></a></td>
      <td><?php echo format_date("M d, Y", $row['date']) ?></td>
      <td><a href="cms.blog.php?id=<?php echo $row['id'] ?>" class="btn btn-info">Edit</a>
      <a href="blog/<?php echo $row['id'] ?>-<?php echo flatten($row['title']) ?>" class="btn btn-success">View post</a>
      <?php if ($row['active']) { ?>
      <a href="cms.bloglist.php?delete=<?php echo $row['id'] ?>" class="btn btn-danger">Deactivate</a>
      <?php } else { ?>
      <a href="cms.bloglist.php?undelete=<?php echo $row['id'] ?>" class="btn btn-success">Reactivate</a>
      <?php } ?>
      </td>
    </tr>
  <?php } ?>
  </table>

  <p><a href="cms/blog" class="btn btn-primary">Add blog post</a></p>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
