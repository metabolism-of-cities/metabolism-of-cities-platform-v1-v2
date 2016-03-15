<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 9;

$list = $db->query("SELECT 
  people_log.*, people.firstname, people.lastname 
FROM 
  people_log
  JOIN people_access ON people_log.people = people_access.id
  JOIN people ON people_access.people = people.id
ORDER BY people_log.date");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Log | <?php echo SITENAME ?></title>
  </head>

  <body class="notranslate">

<?php require_once 'include.header.php'; ?>

  <h1>Log</h1>

  <table class="table table-striped">
    <tr>
      <th>Date</th>
      <th>Person</th>
      <th>Action</th>
    </tr>
    <?php foreach ($list as $row) { ?>
    <tr>
      <td><?php echo $row['date'] ?></td>
      <td><?php echo $row['firstname'] ?> <?php echo $row['lastname'] ?></td>
      <td><?php echo $row['action'] ?></td>
    </tr>
  <?php } ?>
  </table>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
