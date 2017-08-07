<?php
require_once 'functions.php';
$show_breadcrumbs = true;

$id = (int)$_GET['id'];
$slug = html($_GET['slug']);

$sql = $id ? "id = $id" : "slug = '" . $slug . "'";

$info = $db->record("SELECT * FROM content WHERE $sql AND active = 1");
if (!$info->id) {
  kill("Page not found");
}

$this_page = $info->title;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->title ?> | <?php echo SITENAME ?></title>
  <style type="text/css">
    .maincontainer {
      min-height:400px;
    }
  </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  
    <h1><?php echo $info->title ?></h1>

    <?php echo $info->content ?>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
