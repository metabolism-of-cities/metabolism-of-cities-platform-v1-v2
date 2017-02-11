<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 10;
$tab = 2;

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM tags_parents WHERE id = $id");

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $check = $db->query("SELECT * FROM tags WHERE parent = $delete");
  if (count($check)) {
    $getunclassified = $db->record("SELECT * FROM tags_parents WHERE name = 'Unclassified'");
    if ($getunclassified->id) {
      $db->query("UPDATE tags SET parent = {$getunclassified->id} WHERE parent = $delete");
    } else {
      die("You can not delete this parent tag -- first reclassify or remove the associated tags");
    }
  }
  $db->query("DELETE FROM tags_parents WHERE id = $delete LIMIT 1");
}

if ($_POST) {
  $post = array(
    'name' => html($_POST['name']),
    'parent_order' => (int)$_POST['parent_order'],
  );
  if ($id) {
    $db->update("tags_parents",$post,"id = $id");
  } else {
    $db->insert("tags_parents",$post);
    $id = $db->insert_id;
  }
  header("Location: " . URL . "cms/tagparents/saved");
  exit();
}

$list = $db->query("SELECT * FROM tags_parents WHERE name != 'Unclassified'");

if ($_GET['saved']) {
  $print = "Information was saved";
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Tag headers | <?php echo SITENAME ?></title>
  </head>

  <body class="notranslate">

<?php require_once 'include.header.php'; ?>

  <h1>Tag headers</h1>

  <?php require_once 'include.cmstags.php'; ?>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <?php if ($_GET['add'] || $id ) { ?>
  
  <h2><?php echo $id ? "Edit" : "Add"; ?> tag header</h2>

  <form method="post" class="form form-horizontal">
  
    <div class="form-group">
      <label class="col-sm-2 control-label">Name</label>
      <div class="col-sm-10">
        <input class="form-control" type="name" name="name" value="<?php echo $info->name ?>" />
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="parent_order" value="<?php echo $info->parent_order ?>" />
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>

  </form>
  
  <?php } else { ?>

  <table class="table table-striped">
    <tr>
      <th>Name</th>
      <th>Options</th>
    </tr>
    <?php foreach ($list as $row) { ?>
    <tr>
      <td><?php echo $row['name'] ?></td>
      <td>
        <a href="cms.tagparents.php?id=<?php echo $row['id'] ?>" class="btn btn-default">Edit</a>
        <?php if ($row['name'] != 'Unclassified') { ?>
          <a href="cms.tagparents.php?delete=<?php echo $row['id'] ?>" class="btn btn-danger" onclick="javascript:return confirm('Are you sure?')">Delete</a>
        <?php } ?>
      </td>
    </tr>
  <?php } ?>
  </table>

  <p><a href="cms.tagparents.php?add=true" class="btn btn-success"><i class="fa fa-plus"></i> Add</a></p>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
