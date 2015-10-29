<?php
$admin_login = true;
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 2;
$page = 6;

$edit = (int)$_GET['edit'];

if ($edit) {
  $details = $db->record("SELECT * FROM analysis_options WHERE id = $edit");
  $info = $db->record("SELECT * FROM analysis_options_types WHERE id = {$details->type}");
  $id = $info->id;
} else {
  $id = (int)$_GET['id'];
  $info = $db->record("SELECT * FROM analysis_options_types WHERE id = $id");
}

if ($_POST) {
  $post = array(
    'type' => $id,
    'name' => html($_POST['name']),
    'measure' => html($_POST['measure']),
  );
  if ($edit) {
    $db->update("analysis_options",$post,"id = $edit");
  } else {
    $db->insert("analysis_options",$post);
  }
  header("Location: " . URL . "casestudies.php?added=true#meta");
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Meta Information | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1><?php echo $edit ? "Edit" : "Add" ?> Meta Data</h1>

  <form method="post" class="form form-horizontal">

    <div class="form-group">
      <label class="col-sm-2 control-label">Group</label>
      <div class="col-sm-10" style="margin-top:6px">
        <?php echo $info->name ?>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Name</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="name" value="<?php echo $details->name ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Measure</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="measure" placeholder="E.g. kg/cap" value="<?php echo $details->measure ?>" />
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
