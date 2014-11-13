<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 6;

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM mfa_industries WHERE dataset = $project AND id = $id");

if (!count($info)) {
  kill("No record found");
}

$contacts = $db->query("SELECT * FROM mfa_contacts WHERE industry = $id ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->name ? $info->name : "Add Industry"; ?> | <?php echo SITENAME ?></title>
    <script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <a href="omat/<?php echo $project ?>/industry/<?php echo $id ?>" class="btn btn-primary pull-right"><i class="fa fa-cogs"></i> Edit this industry</a>

  <h1>Industry Profile</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/industries">Industries</a></li>
    <li class="active"><?php echo $info->name ?></li>
  </ol>

  <h2><?php echo $info->name ?></h2>

  <?php echo $info->description_general ?>

  <?php if ($info->description_companies) { ?>
    <h3>Companies</h3>
    <p><?php echo $info->dsecription_companies ?></p>
  <?php } ?>

  <?php if (count($contacts)) { ?>

    <h3>Contacts</h3>

    <div class="alert alert-info">
      <strong><?php echo count($contacts) ?></strong> contact<?php echo count($contacts) > 1 ? 's' : ''; ?> found.
    </div>

    <table class="table table-striped">
      <tr>
        <th>Group</th>
        <th>Contact</th>
      </tr>
    <?php foreach ($contacts as $row) { ?>
      <tr>
        <td><?php echo $row['ind'] ?></td>
        <td><a href="omat/<?php echo $project ?>/viewcontact/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
      </tr>
    <?php } ?>
    </table>

  <?php } ?>
  

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
