<?php
require_once 'functions.php';
$section = 5;
$page = 99;

$id = (int)$_GET['id'];
$type = (int)$_GET['type'];

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("DELETE FROM analysis WHERE id = $delete LIMIT 1");
  $print = "Item successfully removed";
}

$info = $db->record("SELECT * FROM case_studies WHERE id = $id");

$list = $db->query("SELECT *, analysis.id AS analysis_id, analysis_options.id AS id
FROM analysis_options 
  LEFT JOIN analysis ON analysis_options.id = analysis.analysis_option AND analysis.case_study = $id
WHERE type = $type");


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Analysis | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Analysis</h1>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <p>Case study: <a href="casestudy/<?php echo $id ?>"><?php echo $info->name ?></a></p>

  <table class="table table-striped ellipsis">
    <tr>
      <th>Name</th>
      <?php if ($type == 1) { ?>
        <th>Year</th>
      <?php } ?>
      <th>Information</th>
      <th>Actions</th>
    </tr>
    <?php foreach ($list as $row) { ?>
    <tr>
      <td><?php echo $row['name'] ?></td>
      <?php if ($type == 1) { ?>
        <td><?php echo $row['year'] ?></td>
      <?php } ?>
      <td><?php echo $row['result'] ? $row['result'] : $row['notes'] ?></td>
      <td>
        <?php if ($row['analysis_id']) { ?>
          <a class="btn btn-info" href="analysis.edit.php?case=<?php echo $id ?>&amp;edit=<?php echo $row['analysis_id'] ?>">Edit</a>
          <a class="btn btn-danger" onclick="javascript:return confirm('Are you sure?')" href="analysis.case.php?type=<?php echo $type ?>&amp;id=<?php echo $id ?>&amp;delete=<?php echo $row['analysis_id'] ?>">Delete</a>
        <?php } ?>
        <a class="btn btn-success" href="analysis.edit.php?case=<?php echo $id ?>&amp;option=<?php echo $row['id'] ?>">Add</a>
      </td>
    </tr>
  <?php } ?>
  </table>



<?php require_once 'include.footer.php'; ?>

  </body>
</html>
