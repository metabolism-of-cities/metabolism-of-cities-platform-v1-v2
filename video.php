<?php
require_once 'functions.php';
$section = 8;
$page = 3;
$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM videos WHERE id = $id");

if (!$info->id) {
  kill("Record not found");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->title ?> | Videos | <?php echo SITENAME ?></title>
    <style type="text/css">
    .well dd{margin-bottom:20px}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <ol class="breadcrumb">
    <li><a href="./">Home</a></li>
    <li><a href="videos">Videos</a></li>
    <li class="active"><?php echo $info->title ?></li>
  </ol>

  <h1><?php echo $info->title ?></h1>

  <iframe width="100%" height="480" src="https://www.youtube.com/embed/<?php echo $info->url ?>" frameborder="0" allowfullscreen></iframe>

  <div class="well">
    <dl class="dl">
      <dt>Description</dt>
      <dd><?php echo $info->description ?></dd>
      <dt>Author</dt>
      <dd><?php echo $info->author ?></dd>
      <dt>Link</dt>
      <dd><a href="https://youtube.com/watch?v=<?php echo $info->url ?>">https://youtube.com/watch?v=<?php echo $info->url ?></a></dd>
  </div>

  <p><a href="videos" class="btn btn-primary">View all videos</a></p>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
