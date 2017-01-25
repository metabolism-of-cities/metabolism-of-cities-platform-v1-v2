<?php
$show_breadcrumbs = true;
require_once 'functions.php';
$section = 2;
$page = 4;


$list = $db->query("
   SELECT wishlist.*, t.name AS type, users.user_name
     FROM wishlist
     JOIN wishlist_types t ON wishlist.type = t.id
LEFT JOIN users ON wishlist.assigned_to = users.user_id
    WHERE wishlist.status != 'removed'
      AND wishlist.public = 1
 ORDER BY wishlist.type, wishlist.id
");

foreach ($list as $row) {
  $array = array(
    'description' => $row['description'],
    'id' => $row['id'],
    'assigned_to' => $row['assigned_to'],
    'status' => $row['status'],
    'public' => $row['public'],
  );
  if ($row['parent_item']) {
    $children[$row['parent_item']][] = $array;
  } else {
    $wishlist[$row['type']][] = $array;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Wish List | <?php echo SITENAME ?></title>
    <style type="text/css">
    .finished,.done{text-decoration: line-through;background:url(img/check.png) no-repeat left center; padding-left:20px;list-style:none;position:relative;left:-20px;}
    .wishlist li p{margin:0}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <div class="jumbotron">
    <h1>Wish List</h1>
    <p>
      This page describes features, content, and other things that we would like to add to this website.
      Some of these things are included on our development roadmap and will likely be developed at some point.
      Other issues will require outside assistance from volunteers, and we hope to find people who can help us out.
      In either way, your support will be appreciated? Can you help?
    </p>
    <p>
      <a class="btn btn-lg btn-primary" href="page/contact" role="button">Let us know &raquo;</a>
      <a class="btn btn-lg btn-primary" href="https://github.com/paulhoekman/mfa-tools">View this project on github</a>
    </p>
  </div>

  <?php foreach ($wishlist as $key => $value) { ?>
    <h2><?php echo $key ?></h2>
    <ul class="wishlist">
      <?php foreach ($value as $row) { ?>
        <li class="<?php echo $row['status'] ?>"><?php echo $row['description'] ?>
          <?php if ($children[$row['id']]) { ?>
            <ul>
            <?php foreach($children[$row['id']] as $row) { ?>
              <li class="<?php echo $row['status'] ?>"><?php echo $row['description'] ?></li>
            <?php } ?>
            </ul>
          <?php } ?>
        </li>
      <?php } ?>
    </ul>
  <?php } ?>


  Can you help with any of these points? Do you have a feature you would like to request? <a href="page/contact">Let us know!</a>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
