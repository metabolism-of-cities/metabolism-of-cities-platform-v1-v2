<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 21;

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM mooc_media WHERE id = $id");

if ($_POST) {
  $post = array(
    'title' => html($_POST['title']),
    'description' => $_POST['content'],
    'position' => (int)$_POST['position'],
    'url' => html($_POST['url']),
    'module' => (int)$_POST['module'],
  );
  if ($id) {
    $db->update("mooc_media",$post,"id = $id");
  } else {
    $db->insert("mooc_media",$post);
    $id = $db->insert_id;
  }
  header("Location: ".URL."cms.moocmedia.php?id=".$_POST['module']);
  exit();
}
$mooc = 1;
$mooc_info = $db->record("SELECT * FROM mooc WHERE id = $mooc");

$module = $info->module ?: (int)$_GET['module'];
$module_info = $db->record("SELECT * FROM mooc_modules WHERE id = $module");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->title ?: 'Add Media Object' ?> | <?php echo SITENAME ?></title>
  </head>

  <body class="notranslate">

<?php require_once 'include.header.php'; ?>

  <h1><?php echo $info->title ?: 'Add Media Object' ?></h1>

  <ol class="breadcrumb">
    <li class="active"><a href="cms.moocs.php">MOOCs</a></li>
    <li><a href="cms.modules.php?id=<?php echo $mooc_info->id ?>"><?php echo $mooc_info->name ?></a></li>
    <li><a href="cms.module.php?id=<?php echo $module_info->id ?>"><?php echo $module_info->title ?></a></li>
    <li><a href="cms.moocmedia.php?id=<?php echo $module_info->id ?>">Media</a></li>
    <li><?php echo $info->title ?: "Add media" ?></li>
  </ol>


  <form method="post" class="form form-horizontal">
  
    <div class="form-group">
      <label class="col-sm-2 control-label">Title</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="title" value="<?php echo $info->title ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">URL</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="url" value="<?php echo $info->url ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Position</label>
      <div class="col-sm-10">
        <input class="form-control" type="number" name="position" value="<?php echo $info->position ?>" />
      </div>
    </div>

    <p><strong>Description:</strong></p>

    <textarea name="content" class="hidden"><?php echo $info->description ?></textarea>

    <div id="summernote"><?php echo $info->description ?></div>
    <input type="hidden" name="module" value="<?php echo $_GET['module'] ?: $info->module ?>" />

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
