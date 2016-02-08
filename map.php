<?php
require_once 'functions.php';
$section = 5;
$page = 98;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Map with urban metabolism studies | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Map with urban metabolism studies</h1>

<iframe width="100%" height="590" frameborder="0" src="https://mfatools.cartodb.com/viz/eb35be38-ce37-11e5-823c-0ecfd53eb7d3/embed_map" allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>

  <p>
    This map shows urban metabolism studies that have been identified as part of 
    our database with <a href="publications/list">publications</a>. This map is periodically 
    synced so the most recent publications are not necessarily included. If you would like to
    add a study, be sure to <a href="publications/add">add it online</a>.
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
