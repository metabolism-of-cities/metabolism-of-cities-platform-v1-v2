<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 2;

$id = $project;

$type = (int)$_GET['type'];

$sql = $type ? " AND c.type = $type" : false;

$list = $db->query("SELECT c.*, t.name AS type, o.status,
  (SELECT name FROM mfa_leads
    JOIN mfa_contacts ON mfa_leads.from_contact = mfa_contacts.id
    WHERE mfa_leads.to_contact = c.id) AS referral
FROM mfa_contacts c
  LEFT JOIN mfa_contacts_types t ON c.type = t.id
  JOIN mfa_status_options o ON c.status = o.id
WHERE c.dataset = $id $sql
  ORDER BY name
");

$types = $db->query("SELECT *,
  (SELECT COUNT(*) FROM mfa_contacts WHERE type = mfa_contacts_types.id) as total
FROM mfa_contacts_types WHERE dataset = $id ORDER BY name");

$total = $db->record("SELECT COUNT(*) AS total FROM mfa_contacts WHERE dataset = $id");
$unclassified = $db->record("SELECT COUNT(*) AS total FROM mfa_contacts WHERE dataset = $id AND type IS NULL");

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
    table {width:100%;table-layout: fixed;}
    th,td{ white-space:nowrap; overflow:hidden; text-overflow: ellipsis; }
    a.right{float:right}
    .table > tbody > tr > th{border-top:0}
    .row-name{width:auto}
    .row-employer{width:200px}
    .row-status,.row-added{width:130px}
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

  <?php if (count($types)) { ?>
    <ul class="nav nav-tabs" role="tablist">
      <li class="<?php echo !$_GET['type'] ? 'active' : 'regular'; ?>"><a href="omat/<?php echo $project ?>/contacts">All (<?php echo $total->total ?>)</a></li>
    <?php foreach ($types as $row) { ?>
      <li class="<?php echo $_GET['type'] == $row['id'] ? 'active' : 'regular'; ?>">
        <a href="omat/<?php echo $project ?>/contacts/type/<?php echo $row['id'] ?>">
          <?php echo $row['name'] ?> (<?php echo $row['total'] ?>)
        </a>
      </li>
    <?php } ?>
    </ul>
  <?php } else { ?>
    <div class="alert alert-info">A total of <?php echo count($list) ?> contacts where found.</div>
  <?php } ?>

  <?php if (count($list)) { ?>

    <table class="table table-striped">
      <tr>
        <th class="row-name">Name</th>
        <th class="row-employer">Employer</th>
        <th class="row-added">Added</th>
        <th class="row-status">Status</th>
      </tr>
    <?php foreach ($list as $row) { ?>
      <tr>
        <td class="long"><a href="omat/<?php echo $project ?>/viewcontact/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
        <td class="medium"><?php echo $row['works_for_referral_organization'] ? $row['referral'] : $row['employer']; ?></td>
        <td><?php echo format_date("M d, Y", $row['created']) ?></td>
        <td>
          <?php echo $row['status'] ?>
        </td>
      </tr>
    <?php } ?>
    </table>

  <?php } ?>

  <a href="omat/<?php echo $id ?>/contact/0" class="btn btn-success">Add Contact</a>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
