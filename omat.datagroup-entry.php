<?php
require_once 'functions.php';
$section = 6;

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM mfa_groups WHERE id = $id");

$dataset = $_GET['dataset'] ? (int)$_GET['dataset'] : $info->dataset;

if (!$id) {
  $other_groups = $db->query("SELECT * FROM mfa_groups WHERE dataset = $dataset");
}

if ($_POST) {
  $post = array(
    'section' => html($_POST['section']),
    'name' => html($_POST['name']),
    'dataset' => $dataset,
  );
  if ($id) {
    $db->update("mfa_groups",$post,"id = $id");
  } else {
    $db->insert("mfa_groups",$post);
    $id = $db->lastInsertId();
  }
  header("Location: " . URL . "omat/manage/{$dataset}/saved");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $id ? "Edit" : "Add" ?> Data Group | <?php echo SITENAME ?></title>
  </head>

  <body class="omat">

<?php require_once 'include.header.php'; ?>

  <h1><?php echo $id ? "Edit" : "Add" ?> Data Group</h1>

  <?php if (!$id && !count($other_groups)) { ?>
    <div class="alert alert-info">
      You can manually add any number of data groups and materials to your dataset. Data groups are 
      main groups, under which the different materials will be logged. You can always edit the group names
      and classification without affecting the underlying data.
    </div>
  <?php } ?>

  <form method="post" class="form form-horizontal">

    <div class="form-group">
      <label class="col-sm-2 control-label">Code</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" placeholder="Code number or letter to identify this group" name="section" value="<?php echo $info->section ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Name</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="name" value="<?php echo $info->name ?>" />
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>

  </form>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
