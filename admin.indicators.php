<?php
$admin_login = true;
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 2;
$page = 6;

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM analysis_options_types WHERE id = $id");

if ($_POST) {
  $post = array(
    'type' => $id,
    'name' => html($_POST['name']),
    'measure' => html($_POST['measure']),
  );
  $db->insert("analysis_options",$post);
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

  <h1>Add Meta Data</h1>

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
        <input class="form-control" type="text" name="name" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Measure</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="measure" placeholder="E.g. kg/cap" />
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Add</button>
      </div>
    </div>


  
  </form>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
