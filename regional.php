<?php
require_once 'functions.php';
$section = 5;
$page = 99;

$list = $db->query("SELECT papers.*, regional.* 
FROM regional 
  JOIN papers
  ON regional.paper = papers.id
ORDER BY papers.year, regional.name
");

if ($_GET['message'] == 'saved') {
  $print = "Information has been saved";
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Regional Material Flow Analysis | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Regional Material Flow Analysis</h1>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <p>
    This page provides an overview of regional studies that have been done, with in-depth
    details about the methodology, approaches, outcomes and challenges.
  </p>

  <div class="alert alert-info">
    <strong><?php echo count($list) ?></strong> studies found.
  </div>

  <?php if ($list) { ?>

  <table class="table table-striped ellipsis">
    <tr>
      <th class="large">Region</th>
      <th class="small">Year</th>
      <th class="large">Paper</th>
      <th class="large">Authors</th>
    </tr>
  <?php foreach ($list as $row) { ?>
    <tr>
      <td><a href="regional.edit.php?id=<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
      <td><?php echo $row['year'] ?></td>
      <td><a href="publication/<?php echo $row['paper'] ?>"><?php echo $row['title'] ?></a></td>
      <td><?php echo $row['author'] ?></td>
    </tr>
  <?php } ?>
  </table>

  <?php } ?>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
