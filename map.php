<?php
$show_breadcrumbs = true;
require_once 'functions.php';
$section = 5;
$page = 5;

$map = ID == 2 ? "84e5f4f8-e2c8-11e6-a282-0e3ebc282e83" : "8d9c273e-ce47-11e5-bff5-0e787de82d45";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Map with <?php echo $topic ?> publications | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Map with <?php echo $topic ?> publications</h1>

  <iframe width="100%" height="590" frameborder="0" src="https://metabolismofcities.cartodb.com/viz/<?php echo $map ?>/embed_map" allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>

  <p>
    This map shows <?php echo $topic ?> publications that have been identified as part
    of <a href="publications/list">our database</a>. If you
    would like to add a document, be sure to <a href="publications/add">add it
    online</a>.
  </p>

  <div class="alert alert-info">
    <a href="map.csv.php" class="btn btn-default">
      <i class="fa fa-table"></i>
      Download
    </a>
    You can download the underlying dataset as a <strong>CSV file</strong>.
    The full list with publications can be downloaded from the <a href="publications/list">publications</a> list.
  </div>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
