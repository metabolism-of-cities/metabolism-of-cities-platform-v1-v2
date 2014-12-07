<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 3;
$sub_page = 1;

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM mfa_dataset WHERE id = $id");

$list = $db->query("SELECT mfa_data.*, mfa_materials.name AS materialname, mfa_groups.name AS groupname,
  mfa_scales.name AS scalename, mfa_materials.code, mfa_groups.section, mfa_materials.mfa_group
FROM mfa_data
  JOIN mfa_materials ON mfa_data.material = mfa_materials.id
  JOIN mfa_groups ON mfa_materials.mfa_group = mfa_groups.id
  LEFT JOIN mfa_scales ON mfa_data.scale = mfa_scales.id
WHERE mfa_groups.dataset = $id
  ORDER BY mfa_groups.section, mfa_materials.code, mfa_data.year, mfa_data.scale
");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <link rel="stylesheet" href="css/sidebar.css" />
    <title>Data Overview | <?php echo $info->name ?> | OMAT | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>
<?php require_once 'include.omatheader.php'; ?>

  <h1>Data Overview</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Data Overview</li>
  </ol>

  <div class="alert alert-info">
    <strong><?php echo count($list) ?></strong> data points found.
  </div>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <table class="table table-striped">
    <tr>
      <th>Code</th>
      <th>Section</th>
      <th>Material</th>
      <th>Year</th>
      <?php if ($info->multiscale) { ?>
        <th>Scale</th>
      <?php } ?>
      <th>Quantity (<?php echo $info->measurement ?>)</th>
    </tr>
  <?php foreach ($list as $row) { ?>
    <tr>
      <td><?php echo $row['section'] ?> <?php echo $row['code'] ?></td>
      <td><a href="omat/datagroup/<?php echo $row['mfa_group'] ?>"><?php echo $row['groupname'] ?></a></td>
      <td><a href="omat/data/<?php echo $row['material'] ?>"><?php echo $row['materialname'] ?></a></td>
      <td><?php echo $row['year'] ?></td>
      <?php if ($info->multiscale) { ?>
        <td><?php echo $row['scalename'] ?></td>
      <?php } ?>
      <td><?php echo number_format($row['data'], $info->decimal_precision) ?></td>
    </tr>
  <?php } ?>
  </table>


<?php require_once 'include.omatfooter.php'; ?>
<?php require_once 'include.footer.php'; ?>

  </body>
</html>
