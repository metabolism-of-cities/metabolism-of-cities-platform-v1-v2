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
    'url_download' => html($_POST['url_download']),
    'duration' => html($_POST['duration']),
    'type' => html($_POST['type']),
  );
  if ($_FILES['file']['name']) {
    $source = $_FILES['file']['name'];
    $extension = strtolower(substr($source, strrpos($source, '.') + 1));
    $post['file_extension'] = mysql_clean($extension);
  }
  if ($id) {
    $db->update("mooc_media",$post,"id = $id");
  } else {
    $db->insert("mooc_media",$post);
    $last = $db->record("SELECT id FROM mooc_media ORDER BY id DESC LIMIT 1");
    $id = $last->id;
  }
  if ($_FILES['file']['name']) {
    $file = "files/mooc-$id.$extension";
    move_uploaded_file($_FILES['file']['tmp_name'],$file);
  }
  header("Location: ".URL."cms.moocmedia.php?id=".$_POST['module']);
  exit();
}
$mooc = 1;
$mooc_info = $db->record("SELECT * FROM mooc WHERE id = $mooc");

$module = $info->module ?: (int)$_GET['module'];
$module_info = $db->record("SELECT * FROM mooc_modules WHERE id = $module");

$types = array(
  'youtube' => 'Youtube',
  'vimeo' => 'Vimeo',
  'external_file' => 'External file (please provide URL)',
  'uploaded_file' => 'Uploaded file (please upload the file)',
  'text' => 'Text only',
);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->title ?: 'Add Media Object' ?> | <?php echo SITENAME ?></title>
    <style type="text/css">
    .file{display:none}
    </style>
    <script type="text/javascript">
    $(function(){
      $("select[name='type']").change(function(){
        var type = $(this).val();
        if (type == "uploaded_file") {
          $(".file").show('fast');
        } else {
            if (type == "text") {
              $(".texthide").hide('fast');
            } else {
              $(".texthide").show('fast');
            }
          $(".file").hide('fast');
        }
      });
      $("select[name='type']").change();
    });
    </script>
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


  <form method="post" class="form form-horizontal" enctype="multipart/form-data">
  
    <div class="form-group">
      <label class="col-sm-2 control-label">Title</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="title" value="<?php echo $info->title ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Type</label>
      <div class="col-sm-10">
        <select name="type" class="form-control">
          <?php foreach ($types as $key => $value) { ?>
            <option value="<?php echo $key ?>"<?php if ($key == $info->type) { echo ' selected'; } ?>><?php echo $value ?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <div class="form-group texthide">
      <label class="col-sm-2 control-label">URL</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="url" value="<?php echo $info->url ?>" />
      </div>
    </div>

    <div class="form-group texthide">
      <label class="col-sm-2 control-label">Download URL</label>
      <div class="col-sm-10">
        <input class="form-control" type="url" name="url_download" value="<?php echo $info->url_download ?>" />
      </div>
    </div>

    <div class="form-group texthide">
      <label class="col-sm-2 control-label">Duration</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="duration" value="<?php echo $info->duration ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Position</label>
      <div class="col-sm-10">
        <input class="form-control" type="number" name="position" value="<?php echo $info->position ?>" />
      </div>
    </div>

    <div class="form-group file">
      <label class="col-sm-2 control-label">File</label>
      <div class="col-sm-10">
        <input class="form-control" type="file" name="file" />
      </div>
    </div>

    <p><strong>Description:</strong></p>

    <textarea id="content" name="content"><?php echo $info->description ?></textarea>

    <input type="hidden" name="module" value="<?php echo $_GET['module'] ?: $info->module ?>" />

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>

  </form>

  <div class="well">
    
    <h3>Instructions</h3>

    <p>For videos, please only put the boldface text in the URL field. Like so:</p>

    <ul>
       <li>https://www.youtube.com/watch?v=<strong>15B8qN9dre4</strong></li>
       <li>https://vimeo.com/<strong>20563513</strong></li>  
    </ul>

  </div>

<?php require_once 'include.footer.php'; ?>
<?php require_once 'include.editor.php'; ?>

  </body>
</html>
