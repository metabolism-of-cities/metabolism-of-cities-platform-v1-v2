<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 2;
$sub_page = 6;

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM mfa_dataset WHERE id = $id");

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $check = $db->query("SELECT * FROM mfa_sources_flags WHERE flag = $delete");
  $check_contacts = $db->query("SELECT * FROM mfa_contacts_flags WHERE flag = $delete");
  if (count($check) || count($check_contacts)) {
    die("Sorry, we could not delete this tag because there are sources or contacts that have been tagged with this tag already! Untag them first.");
  }
  $db->query("DELETE FROM mfa_special_flags WHERE id = $delete AND dataset = $id LIMIT 1");
  $print = "Tag was deleted";
}

$list = $db->query("SELECT * FROM mfa_special_flags WHERE dataset = $id ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <link rel="stylesheet" href="css/sidebar.css" />
    <title>Types of Tags | <?php echo $info->name ?> | OMAT | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>
<?php require_once 'include.omatheader.php'; ?>

  <h1>Types of Tags</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Types of Tags</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <?php if (!count($list)) { ?>

  <div class="alert alert-info">
    In this section you can define tags for your project. This allows you to tag sources 
    or contacts and filter for them later (you can, for instance, create a tag to mark all the documents that are 
    in a different language and that will require translation).
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
        <td><a href="omat/<?php echo $project ?>/maintenance-tag/<?php echo $row['id'] ?>" class="btn btn-primary">Edit</a></td>
        <td><a href="omat/<?php echo $project ?>/maintenance-tags/<?php echo $row['id'] ?>/delete" class="btn btn-danger" onclick="javascript:return confirm('Are you sure?')">Delete</a></td>
      </tr>
    <?php } ?>
    </table>
  <?php } ?>

  <p><a href="omat/<?php echo $project ?>/maintenance-tag/0" class="btn btn-success">Add tag</a></p>

<?php require_once 'include.omatfooter.php'; ?>
<?php require_once 'include.footer.php'; ?>

  </body>
</html>
