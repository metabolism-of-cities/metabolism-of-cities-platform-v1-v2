<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';

$sub_page = 1;
$type = 'blog';
if ($_GET['type'] == 'news') {
  $sub_page = 17;
  $type = 'news';
} elseif ($_GET['type'] == 'page') {
  $sub_page = 20;
  $type = 'page';
}

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("UPDATE content SET active = 0 WHERE id = $delete");
  $print = "Post was deactivated";
}

if ($_GET['undelete']) {
  $undelete = (int)$_GET['undelete'];
  $db->query("UPDATE content SET active = 1 WHERE id = $undelete");
  $print = "Post was reactivated";
}

$list = $db->query("SELECT * FROM content WHERE type = '$type' ORDER BY date DESC");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo ucfirst($type) ?> | <?php echo SITENAME ?></title>
    <style type="text/css">
    .active-0{opacity:0.6;}
  td {
      max-width: 200px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
  }
  a.btn-info.pull-right {margin:10px 0}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1><?php echo ucfirst($type) ?></h1>

  <p><a href="cms.blog.php?type=<?php echo $type ?>" class="btn btn-info pull-right">Add <?php echo $type ?></a></p>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <table class="table table-striped">
    <tr>
      <th>Title</th>
      <th>Date</th>
      <th>Actions</th>
    </tr>
    <?php foreach ($list as $row) { ?>
    <tr class="active-<?php echo $row['active'] ?>">
      <td><a href="cms.blog.php?id=<?php echo $row['id'] ?>"><?php echo $row['title'] ?></a></td>
      <td><?php echo format_date("M d, Y", $row['date']) ?></td>
      <td><a href="cms.blog.php?id=<?php echo $row['id'] ?>" class="btn btn-info">Edit</a>
      <?php if ($row['type'] == 'blog') { ?>
      <a href="blog/<?php echo $row['id'] ?>-<?php echo flatten($row['title']) ?>" class="btn btn-success">View post</a>
      <?php } else { ?>
      <a href="content/<?php echo $row['id'] ?>-<?php echo flatten($row['title']) ?>" class="btn btn-success">View post</a>
      <?php } ?>
      <?php if ($row['active']) { ?>
      <a href="cms.bloglist.php?delete=<?php echo $row['id'] ?>&amp;type=<?php echo $type ?>" class="btn btn-danger">Deactivate</a>
      <?php } else { ?>
      <a href="cms.bloglist.php?undelete=<?php echo $row['id'] ?>&amp;type=<?php echo $type ?>" class="btn btn-success">Reactivate</a>
      <?php } ?>
      </td>
    </tr>
  <?php } ?>
  </table>

  <p><a href="cms.blog.php?type=<?php echo $type ?>" class="btn btn-info pull-right">Add <?php echo $type ?></a></p>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
