<?php
require_once 'functions.php';

$load_menu = 1;
$sub_page = 1;
$section = 6;

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM mfa_materials WHERE id = $id");

$group = $_GET['group'] ? (int)$_GET['group'] : $info->mfa_group;

$groupinfo = $db->record("SELECT * FROM mfa_groups WHERE id = $group");
$project = $groupinfo->dataset;

require_once 'functions.omat.php';

if ($_POST) {
  $post = array(
    'code' => html($_POST['code']),
    'name' => html($_POST['name']),
    'mfa_group' => $group,
  );
  if ($id) {
    $db->update("mfa_materials",$post,"id = $id");
  } else {
    $db->insert("mfa_materials",$post);
    $id = $db->lastInsertId();
  }
  header("Location: " . URL . "omat/datagroup/{$group}/saved");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $id ? "Edit" : "Add" ?> Material | <?php echo SITENAME ?></title>
  </head>

  <body class="omat">

<?php require_once 'include.header.php'; ?>

  <h1><?php echo $id ? "Edit" : "Add" ?> Material</h1>

  <form method="post" class="form form-horizontal">

    <div class="form-group">
      <label class="col-sm-2 control-label">Code</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" placeholder="Number(s) or letter(s) to identify this material, e.g. 1.3.1" name="code" value="<?php echo $info->code ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Name</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="name" value="<?php echo $info->name ?>" required />
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
