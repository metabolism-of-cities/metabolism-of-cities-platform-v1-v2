<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 6;

$id = (int)$project;

$list = $db->query("SELECT * FROM mfa_industries WHERE dataset = $project ORDER BY name");

if ($_GET['saved']) {
  $print = "Information was saved";
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Industries | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <a href="omat/<?php echo $project ?>/industry/0" class="btn btn-success pull-right"><i class="fa fa-cogs"></i> Add industry</a>

  <h1>Industries</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Industries</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <div class="alert alert-info">
    <strong><?php echo count($list) ?></strong> industries found.
  </div>

  <?php if (count($list)) { ?>

    <table class="table table-striped ellipsis">
      <tr>
        <th class="long">Industry</th>
        <th class="short">Edit</th>
        <th class="short">Delete</th>
      </tr>
    <?php foreach ($list as $row) { ?>
      <tr>
        <td><?php echo $row['name'] ?></td>
        <td><a href="omat/<?php echo $project ?>/industry/<?php echo $row['id'] ?>" class="btn btn-primary">Edit</a></td>
        <td><a href="omat/<?php echo $project ?>/industries/delete/<?php echo $row['id'] ?>" class="btn btn-danger" onclick="javascript:return confirm('Are you sure?')">Delete</a></td>
      </tr>
    <?php } ?>
    </table>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
