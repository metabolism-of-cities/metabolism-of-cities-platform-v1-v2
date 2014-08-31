<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 2;

$id = (int)$_GET['id'];

$list = $db->query("SELECT c.*, t.name AS type
FROM mfa_contacts c
LEFT JOIN mfa_contacts_types t ON c.type = t.id
WHERE c.dataset = $id");

$types = $db->query("SELECT * FROM mfa_sources_types WHERE dataset = $id ORDER BY name");

if ($_GET['deleted']) {
  $print = "The contact has been deleted";
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Contacts | <?php echo SITENAME ?></title>
    <style type="text/css">
    a.right{float:right}
    th{width:120px;}
    th.long{width:auto}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <a href="omat/<?php echo $id ?>/contact/0" class="btn btn-success right">Add Contact</a>

  <h1>Contacts</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Contacts</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <div class="alert alert-info">
    <strong><?php echo count($list) ?></strong> contacts found.
  </div>

  <?php if (count($list)) { ?>

    <table class="table table-striped">
      <tr>
        <th class="long">Name</th>
        <?php if (count($types)) { ?>
          <th>Type</th>
        <?php } ?>
        <th>Added</th>
        <th>Status</th>
      </tr>
    <?php foreach ($list as $row) { ?>
      <tr>
        <td><a href="omat/<?php echo $project ?>/viewcontact/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
        <?php if (count($types)) { ?>
          <td><?php echo $row['type'] ?></td>
        <?php } ?>
        <td><?php echo format_date("M d, Y", $row['created']) ?></td>
        <td>
        <?php if ($row['pending']) { ?>Pending<?php } else { ?>
        <i class="fa fa-check"></i> Processed
        <?php } ?>
        </td>
      </tr>
    <?php } ?>
    </table>

  <?php } ?>

  <a href="omat/<?php echo $id ?>/contact/0" class="btn btn-success">Add Contact</a>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
