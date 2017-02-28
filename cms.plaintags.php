<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 10;
$tab = 1;

$list = $db->query("SELECT tags.*, tags_parents.name AS parentname 
FROM tags JOIN tags_parents ON tags.parent = tags_parents.id 
$sql
ORDER BY tags.tag");

$i = 1;

$parents = $db->query("SELECT * FROM tags_parents ORDER BY name");

$alltags = $db->query("SELECT * FROM tags ORDER BY tag");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Tags | <?php echo SITENAME ?></title>
    <style type="text/css">
      table {border:1px solid #ccc; width:100px;table-layout: fixed;}
      th, td { max-width:100px;white-space:nowrap; overflow:hidden; text-overflow: ellipsis; }
    </style>
  </head>

  <body class="notranslate">

<?php require_once 'include.header.php'; ?>

  <a href="cms/tags" class="pull-right btn btn-primary"><i class="fa fa-arrow-left"></i> Back to regular list</a>

  <h1>Tags</h1>

  <?php require_once 'include.cmstags.php'; ?> 

  <div class="alert alert-success"><span id="counter"><?php echo count($list) ?></span> tags found</div>

  <table class="table table-striped">
    <tr>
      <th>Name</th>
      <th>Header</th>
    </tr>
    <?php foreach ($list as $row) { ?>
    <tr>
      <td>
        <?php echo $row['tag'] ?>
      </td>
      <td>
        <?php echo $row['parentname'] ?>
      </td>
    </tr>
  <?php } ?>
  </table>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
