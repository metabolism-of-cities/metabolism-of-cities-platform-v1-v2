<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 6;
$info = $db->record("SELECT * FROM users WHERE user_id = $user_id");
$wishlist = $db->record("SELECT COUNT(*) AS total FROM wishlist WHERE status = 'open' AND assigned_to = $user_id");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Dashboard | <?php echo SITENAME ?></title>
    <style type="text/css">
    .alert-info{margin-top:30px}
    </style>
  </head>

  <body class="notranslate">

<?php require_once 'include.header.php'; ?>

  <div class="jumbotron">
    <h1>Dashboard</h1>
    <p>Welcome, <?php echo $info->user_name ?></p>
    <p class="alert alert-success"><i class="fa fa-check"></i> Admin access granded</p>
    <p><i class="fa fa-warning"></i> With great power comes great responsibility.</p>
    
    <p class="alert alert-info">You have <strong><?php echo $wishlist->total ?></strong> item(s) on your <a href="cms/wishlist">to-do list</a></p>
  </div>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
