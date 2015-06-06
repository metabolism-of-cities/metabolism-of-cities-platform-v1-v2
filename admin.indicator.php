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
    'name' => html($_POST['name']),
  );
  if ($id) {
    $db->update("analysis_options_types",$post,"id = $id");
  } else {
    $db->insert("analysis_options_types",$post);
    $id = $db->insert_id;
  }
  header("Location: " . URL . "casestudies.php?added=true#meta");
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Indicators | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Add Meta Information Group</h1>

  <form method="post" class="form form-horizontal">

    <div class="form-group">
      <label class="col-sm-2 control-label">Name</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="name" />
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
