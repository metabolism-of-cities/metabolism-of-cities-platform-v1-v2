<?php
require_once 'functions.php';
require_once 'functions.profile.php';

$sub_page = 1;
$info = $profile_info;

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Profile Dashboard | <?php echo SITENAME ?></title>
    <style type="text/css">
    .fa-info-circle{float:left;margin-right:10px;font-size:40px}
    .jumbotron{margin-bottom:50px}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <div class="jumbotron">
    <h1>Profile Dashboard</h1>
    <p>Welcome, <?php echo $info->firstname ?> <?php echo $info->lastname ?></p>
  </div>

  <p>
  <i class="fa fa-info-circle"></i>
  In your dashboard you can edit your profile, add or edit your publications, 
  and manage your datasets. If you have any comments or questions, please don't hesitate to 
  <a href="page/contact">contact us</a>.
  </p>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
