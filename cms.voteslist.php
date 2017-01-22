<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 15;

$id = (int)$_GET['id'];

$list = $db->query("
  SELECT * 
    FROM votes
  WHERE datavisualization = $id
  ");

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
    A total of <strong><?php echo count($list) ?></strong> people casted their vote.
  </div>

  <table class="table table-striped">
      <tr>
          <th>Date</th>
          <th>IP</th>
          <th>Name</th>
          <th>Email</th>
          <th>Comments</th>
      </tr>
    <?php foreach ($list as $row) { ?>
      <tr>
          <td><?php echo format_date("M d, Y H:i", $row['date']) ?></td>
          <td><?php echo $row['ip'] ?></td>
          <td><?php echo $row['name'] ?></td>
          <td><?php echo $row['email'] ?></td>
          <td><?php echo $row['comments'] ?></td>
      </tr>
  <?php } ?>
  </table>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
