<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 21;

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM mooc WHERE id = $id");

if ($_POST) {
  $post = array(
    'name' => html($_POST['name']),
    'description' => $_POST['content'],
  );
  if ($id) {
    $db->update("mooc",$post,"id = $id");
  } else {
    $db->insert("mooc",$post);
    $id = $db->insert_id;
  }
  header("Location: ".URL."cms.moocs.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->name ?: 'Add MOOC' ?> | <?php echo SITENAME ?></title>
  </head>

  <body class="notranslate">

<?php require_once 'include.header.php'; ?>

  <h1><?php echo $info->name ?: 'Add MOOC' ?></h1>

  <form method="post" class="form form-horizontal">
  
    <div class="form-group">
      <label class="col-sm-2 control-label">Name</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="name" value="<?php echo $info->name ?>" />
      </div>
    </div>

    <p><strong>Description:</strong></p>

    <textarea name="content" class="hidden"><?php echo $info->description ?></textarea>

    <div id="summernote"><?php echo $info->description ?></div>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>

  </form>

<?php require_once 'include.footer.php'; ?>
<?php require_once 'include.editor.php'; ?>

  </body>
</html>
