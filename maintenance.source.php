<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 2;
$sub_page = 2;

$id = (int)$_GET['id'];
$project = (int)$_GET['project'];

$info = $db->record("SELECT * FROM mfa_sources_types WHERE id = $id");
if ($id) {
  if ($info->dataset != $project) {
    die("No access");
  }
}

if ($_POST) {
  $post = array(
    'name' => html($_POST['name']),
    'dataset' => $project,
  );
  if ($id) {
    $db->update("mfa_sources_types",$post,"id = $id");
  } else {
    $db->insert("mfa_sources_types",$post);
    $id = $db->lastInsertId();
  }
  header("Location: " . URL . "omat/$project/maintenance-sources/saved");
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Sources | <?php echo $info->name ?> | OMAT | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Types of Sources</h1>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/maintenance-sources">Types of Sources</a></li>
    <li class="active"><?php echo $id ? "Edit" : "Add" ?> Type of Source</li>
  </ol>

  <form method="post" class="form form-horizontal">
  
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
