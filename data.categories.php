<?php
require_once 'functions.php';
$section = 2;
$page = 6;

$list = $db->query("SELECT 
i.*,
a.name AS area,
s.name AS subarea
FROM data_indicators i
JOIN data_subareas s ON i.subarea = s.id
JOIN data_areas a ON s.area = a.id
ORDER BY a.name, s.name, i.name
");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Categories | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>


  <h1>Data Categories</h1>

  <table class="table table-striped">
  <?php foreach ($list as $row) { ?>
  <?php if ($row['area'] != $area) { ?>
    <tr>
      <th colspan="3" style="text-align:left;font-size:22px">
        <?php echo $row['area']; $area = $row['area']; ?>
      </th>
    </tr>
    <tr>
      <th>ID</th>
      <th>Subarea</th>
      <th>Indicator</th>
    </tr>
  <?php } ?>
    <tr>
      <td><?php echo $row['id'] ?></td>
      <td><?php echo $row['subarea'] ?></td>
      <td><?php echo $row['name'] ?></td>
    </tr>
  <?php } ?>
  </table>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
