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

$type = $info->type;
$this_page = $info->title;

if ($type == 'news') {
  $section = 8;
  $page = 5;
  $months = $db->query("SELECT COUNT(*) AS total, MONTH(date) AS month, YEAR(date) AS year FROM content WHERE type = 'news' AND active = 1 
  GROUP BY MONTH(date), YEAR(date)
  ORDER BY date DESC
  ");
}
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

  
    <?php if ($type == 'news') { ?>
        <div class="row">
          <div class="col-md-9">
            <h1><?php echo $info->title ?></h1>
            <?php echo $info->content ?>
          </div>
          
          <?php require_once 'include.news-aside.php'; ?>

        </div>
    <?php } else { ?>
      <h1><?php echo $info->title ?></h1>
      <?php echo $info->content ?>
    <?php } ?>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
