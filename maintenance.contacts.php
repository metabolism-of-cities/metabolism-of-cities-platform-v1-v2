<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 2;
$sub_page = 3;

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM mfa_dataset WHERE id = $id");

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $check = $db->query("SELECT * FROM mfa_contacts WHERE type = $delete");
  if (count($check)) {
    die("Sorry, we could not delete this contact type because there are contacts that have this type set!");
  }
  $db->query("DELETE FROM mfa_contacts_types WHERE id = $delete AND dataset = $id LIMIT 1");
  $print = "Contact was deleted";
}

$list = $db->query("SELECT * FROM mfa_contacts_types WHERE dataset = $id ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <link rel="stylesheet" href="css/sidebar.css" />
    <title>Types of Contacts | <?php echo $info->name ?> | OMAT | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>
<?php require_once 'include.omatheader.php'; ?>

  <h1>Types of Contacts</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Types of Contacts</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <?php if (!count($list)) { ?>

  <div class="alert alert-info">
    In this section you can define Contact Types for your project. By setting different types, OMAT
    can later provide you with reports broken down by contact types. Types of contacts could 
    include 'Private Sector', 'Public Sector', 'Academia', etc.
  </div>

  <?php } else { ?>
    <table class="table table-striped">
      <tr>
        <th>Name</th>
        <th>Edit</th>
        <th>Delete</th>
      </tr>
    <?php foreach ($list as $row) { ?>
      <tr>
        <td><?php echo $row['name'] ?></td>
        <td><a href="omat/<?php echo $project ?>/maintenance-contact/<?php echo $row['id'] ?>" class="btn btn-primary">Edit</a></td>
        <td><a href="omat/<?php echo $project ?>/maintenance-contacts/<?php echo $row['id'] ?>/delete" class="btn btn-danger" onclick="javascript:return confirm('Are you sure?')">Delete</a></td>
      </tr>
    <?php } ?>
    </table>
  <?php } ?>

  <p><a href="omat/<?php echo $project ?>/maintenance-contact/0" class="btn btn-success">Add contact type</a></p>

<?php require_once 'include.omatfooter.php'; ?>
<?php require_once 'include.footer.php'; ?>

  </body>
</html>
