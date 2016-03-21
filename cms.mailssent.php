<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 7;

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM people WHERE id = $id");
$list = $db->query("SELECT people_mails.*, mails.subject, users.user_name
FROM people_mails
JOIN mails ON people_mails.mail = mails.id
JOIN users ON people_mails.sent_by = users.user_id
WHERE people = $id");

$log = $db->query("SELECT 
  people_log.*
FROM 
  people_log
  JOIN people_access ON people_log.people = people_access.id
  JOIN people ON people_access.people = people.id
WHERE people_access.people = $id
ORDER BY people_log.date");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Mails sent | <?php echo SITENAME ?></title>
  </head>

  <body class="notranslate">

<?php require_once 'include.header.php'; ?>

  <h1>Mails sent</h1>

  <table class="table table-striped">
    <tr>
      <th>ID</th>
      <th>Date</th>
      <th>Mail</th>
      <th>To</th>
      <th>Sent by </th>
    </tr>
    <?php foreach ($list as $row) { ?>
    <tr>
      <td><?php echo $row['id'] ?></td>
      <td><?php echo $row['date'] ?></td>
      <td><?php echo $row['subject'] ?></td>
      <td><?php echo $row['address'] ?></td>
      <td><?php echo $row['user_name'] ?></td>
    </tr>
  <?php } ?>
  </table>

  <h1>Log</h1>

  <table class="table table-striped">
    <tr>
      <th>Date</th>
      <th>Action</th>
    </tr>
    <?php foreach ($log as $row) { ?>
    <tr>
      <td><?php echo $row['date'] ?></td>
      <td><?php echo $row['action'] ?></td>
    </tr>
  <?php } ?>
  </table>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
