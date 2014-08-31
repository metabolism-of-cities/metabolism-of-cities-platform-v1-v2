<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 4;

$id = (int)$_GET['id'];

if ($_POST['flag']) {
  $flag = (int)$_POST['flag'];
  $sql_sources .= " AND EXISTS (SELECT * FROM mfa_sources_flags WHERE source = mfa_sources.id AND flag = $flag)";
  $sql_contacts .= " AND EXISTS (SELECT * FROM mfa_contacts_flags WHERE contact = mfa_contacts.id AND flag = $flag)";
}

if ($_GET['random-contact']) {
  $contact = $db->query("SELECT * FROM mfa_contacts WHERE dataset = $project AND pending = 1 ORDER BY RAND() LIMIT 1");
  if ($contact->id) {
    header("Location: " . URL . "omat/$project/viewcontact/{$contact->id}");
    exit();
  }
}
if ($_GET['random-source']) {
  $source = $db->query("SELECT * FROM mfa_sources WHERE dataset = $project AND pending = 1 ORDER BY RAND() LIMIT 1");
  if ($source->id) {
    header("Location: " . URL . "omat/$project/viewsource/{$source->id}");
    exit();
  }
}

$contacts = $db->query("SELECT * FROM mfa_contacts WHERE dataset = $project AND pending = 1 $sql_contacts ORDER BY created");
$sources = $db->query("SELECT * FROM mfa_sources WHERE dataset = $project AND pending = 1 $sql_sources ORDER BY created");

$flags = $db->query("SELECT * FROM mfa_special_flags ORDER BY name");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Worksheet | <?php echo SITENAME ?></title>
    <style type="text/css">
    button.right{float:right;margin-left:5px}
    th{width:120px;}
    th.long{width:auto}
    h2.alert{font-size:1.5em}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <?php foreach ($flags as $row) { ?>
    <form method="post">
      <button 
      type="submit" class="btn btn-<?php echo $_POST['flag'] == $row['id'] ? 'success' : 'default' ?> right" 
      name="flag" value="<?php echo $_POST['flag'] == $row['id'] ? 0 : $row['id'] ?>"><?php echo $row['name'] ?></button>
    </form>
  <?php } ?>

  <h1>Worksheet</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Worksheet</li>
  </ol>

  <h2 class="alert alert-info"><strong><?php echo count($sources) ?></strong> pending sources.</h2>

  <?php if (count($sources)) { ?>

    <table class="table table-striped">
      <tr>
        <th class="long">Name</th>
        <th>Created</th>
        <th>Edit</th>
        <th>Status</th>
      </tr>
    <?php foreach ($sources as $row) { ?>
      <tr>
        <td><a href="omat/<?php echo $project ?>/viewsource/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
        <td><?php echo format_date("M d, Y", $row['created']) ?></td>
        <td><a href="omat/<?php echo $project ?>/source/<?php echo $row['id'] ?>">Edit</a></td>
        <td><?php echo $row['pending'] ? 'Pending' : 'Processed'; ?></td>
      </tr>
    <?php } ?>
    </table>

  <?php } ?>

  <h2 class="alert alert-info"><strong><?php echo count($contacts) ?></strong> pending contacts.</h2>

  <?php if (count($contacts)) { ?>

    <table class="table table-striped">
      <tr>
        <th class="long">Name</th>
        <th>Created</th>
        <th>Edit</th>
        <th>Status</th>
      </tr>
    <?php foreach ($contacts as $row) { ?>
      <tr>
        <td><a href="omat/<?php echo $project ?>/viewcontact/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
        <td><?php echo format_date("M d, Y", $row['created']) ?></td>
        <td><a href="omat/<?php echo $project ?>/contact/<?php echo $row['id'] ?>">Edit</a></td>
        <td><?php echo $row['pending'] ? 'Pending' : 'Processed'; ?></td>
      </tr>
    <?php } ?>
    </table>

  <?php } ?>

  <p><a href="omat/<?php echo $project ?>/worksheet/random-contact" class="btn btn-success">Open random pending contact</a>
  <a href="omat/<?php echo $project ?>/worksheet/random-source" class="btn btn-success">Open random pending source</a></p>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
