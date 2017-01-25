<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 16;

$list = $db->query("
   SELECT wishlist.*, t.name AS type, users.user_name
     FROM wishlist
     JOIN wishlist_types t ON wishlist.type = t.id
LEFT JOIN users ON wishlist.assigned_to = users.user_id
    WHERE wishlist.status != 'removed'
      AND status = 'open'
");

$replace = array('<br></p>' => '</p>');

foreach ($list as $row) {
  $row['description'] = strtr($row['description'], $replace);
  $array = array(
    'description' => $row['description'],
    'id' => $row['id'],
    'assigned_to' => $row['assigned_to'],
    'status' => $row['status'],
    'public' => $row['public'],
    'user_name' => $row['user_name'],
  );
  if ($row['parent_item']) {
    $children[$row['parent_item']][] = $array;
  } else {
    $wishlist[$row['type']][] = $array;
  }
}

if ($_GET['saved']) {
  $print = "Information was saved";
} elseif ($_GET['closed']) {
  $print = "Great work -- this item was closed!";
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>To Do List | <?php echo SITENAME ?></title>
    <style type="text/css">
    .bar{padding:5px;background:#f4f4f4;color:#000;font-weight:bold;display:inline-block;margin-right:10px}
    .bar i{margin-right:10px}
    .wishlist li p{margin:0;display:inline}
    .public0 .bar {background:#ccc}
    .bar{float:left}
    .wishlist li{clear:both}
    </style>
  </head>

  <body class="notranslate">

<?php require_once 'include.header.php'; ?>

  <a href="cms/wishlistitem" class="btn btn-success pull-right">Add new item</a>

  <h1>To Do List</h1>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <?php foreach ($wishlist as $key => $value) { ?>
    <h2><?php echo $key ?></h2>
    <ul class="wishlist">
      <?php foreach ($value as $row) { ?>
      <li class="<?php echo $row['status'] ?> public<?php echo $row['public'] ?>">
        <span class="bar">
          <a href="cms.wishlistitem.php?id=<?php echo $row['id'] ?>"><i class="fa fa-pencil"></i></a>
          <a href="cms.wishlistitem.php?parent=<?php echo $row['id'] ?>" title="Add a sub-item"><i class="fa fa-plus"></i></a>
          <a href="cms.wishlistitem.php?id=<?php echo $row['id'] ?>&amp;done=true" title="This was done!"><i class="fa fa-check-square-o"></i></a>
        </span>
        <?php echo $row['description'] ?>
          <?php if ($row['user_name']) { ?>
          <strong class="badge badge-primary"><?php echo $row['user_name'] ?></strong>
          <?php } ?>
          <?php if (is_array($children[$row['id']])) { ?>
            <ul>
            <?php foreach($children[$row['id']] as $row) { ?>
              <li class="<?php echo $row['status'] ?> public<?php echo $row['public'] ?>">
                <span class="bar">
                  <a href="cms.wishlistitem.php?id=<?php echo $row['id'] ?>"><i class="fa fa-pencil"></i></a>
                  <a href="cms.wishlistitem.php?id=<?php echo $row['id'] ?>&amp;done=true" title="This was done!"><i class="fa fa-check-square-o"></i></a>
                </span>
                <?php echo $row['description'] ?>
                  <?php if ($row['user_name']) { ?>
                  <strong class="badge badge-info"><?php echo $row['user_name'] ?></strong>
                  <?php } ?>
              </li>
            <?php } ?>
            </ul>
          <?php } ?>
        </li>
      <?php } ?>
    </ul>
  <?php } ?>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
