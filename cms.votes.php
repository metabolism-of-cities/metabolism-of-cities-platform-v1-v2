<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 15;

$list = $db->query("
  SELECT datavisualizations.*, COUNT(votes.id) AS votes 
    FROM votes
    JOIN datavisualizations ON votes.datavisualization = datavisualizations.id
GROUP BY votes.datavisualization
ORDER BY votes DESC
  ");

$votes = $db->query("SELECT DISTINCT(email) FROM votes");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Votes | <?php echo SITENAME ?></title>
  </head>

  <body class="notranslate">

<?php require_once 'include.header.php'; ?>

  <h1>Votes</h1>

  <div class="alert alert-success">
    A total of <strong><?php echo count($votes) ?></strong> people casted their vote.
  </div>

  <table class="table table-striped">
      <tr>
          <th>Dataviz</th>
          <th>Votes</th>
      </tr>
      <?php foreach ($list as $row) { ?>
      <tr>
          <td><a href="datavisualizations/<?php echo $row['id'] ?>-<?php echo flatten($row['title']) ?>"><?php echo $row['title'] ?></a></td>
          <td><?php echo $row['votes'] ?></td>
      </tr>
  <?php } ?>
  </table>





<?php require_once 'include.footer.php'; ?>

  </body>
</html>
