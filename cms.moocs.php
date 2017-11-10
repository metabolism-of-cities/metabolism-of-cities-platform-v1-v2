<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 21;

$list = $db->query("SELECT * FROM mooc ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>MOOCs | <?php echo SITENAME ?></title>
  </head>

  <body class="notranslate">

<?php require_once 'include.header.php'; ?>

  <h1>MOOCs</h1>

  <p><a href="cms.mooc.php" class="btn btn-info">Add MOOC</a></p>

  <table class="table table-striped">
    <tr>
      <th>Title</th>
      <th>Options</th>
    </tr>
    <?php foreach ($list as $row) { ?>
    <tr>
      <td><a href="cms.mooc.php?id=<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
      <td>
        <a href="cms.modules.php?id=<?php echo $row['id'] ?>">Manage modules</a> |
        <a href="cms.mooc.php?id=<?php echo $row['id'] ?>">Edit</a>
      </td>
    </tr>
  <?php } ?>
  </table>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
