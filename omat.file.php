<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 3;

$id = (int)$_GET['id'];
$project = (int)$_GET['project'];

$info = $db->record("SELECT f.*, s.name AS source_name
FROM mfa_files f
  JOIN mfa_sources s ON f.source = s.id
  LEFT JOIN mfa_sources_types t ON s.type = t.id
  JOIN mfa_status_options o ON s.status = o.id
WHERE f.id = $id AND s.dataset = $project");

if (!count($info)) {
  die("This file was not found");
}

if ($_POST['fileform']) {
  if ($_FILES) {
    $original_name = $_FILES['file']['name'];
    $type = $_FILES['file']['type'];
    $size = $_FILES['file']['size']/1024;
  } else {
    $original_name = $info->original_name;
    $type = $info->type;
    $size = $info->size;
  }
  $post = array(
    'name' => html($_POST['name']),
    'original_name' => mysql_clean($original_name),
    'url' => mysql_clean($_POST['url']),
    'type' => mysql_clean($type),
    'size' => (int)$size,
  );
  $db->update("mfa_files",$post,"id = $id");
  $file_id = $id;
  if ($_FILES['file']['name']) {
    if (!$_FILES['file']['error']) {
      $location = UPLOAD_PATH . "$project.{$info->source}.$file_id";
      if (!is_writable(UPLOAD_PATH)) {
        $error = "Directory is not writeable.";
        echo $error;
        die();
      }
      move_uploaded_file($_FILES['file']['tmp_name'], $location);
    } else {
      $error = "File could not be uploaded.";
      echo $error;
      die();
    }
  }
  header("Location: " . URL . "omat/$project/viewsource/{$info->source}/file-saved");
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->name ?> | <?php echo SITENAME ?></title>
    <style type="text/css">
    dd,dt{margin-bottom:5px}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Edit File</h1>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/sources">Sources</a></li>
    <li><a href="omat/<?php echo $project ?>/viewsource/<?php echo $info->source ?>"><?php echo $info->source_name ?></a></li>
    <li>File: <?php echo $info->name ?></li>
  </ol>

    <h2>File information</h2>

    <dl class="dl-horizontal">
      <dt>Original name</dt>
      <dd><?php echo $info->original_name ?></dd>

      <dt>Filesize</dt>
      <dd><?php echo number_format($info->size/1024,1) ?> Mb</dd>

      <dt>Uploaded</dt>
      <dd><?php echo format_date("r", $info->uploaded) ?></dd>

      <dt>Type</dt>
      <dd><?php echo $info->type ?></dd>

    </dl>

    <form method="post" class="form form-horizontal" enctype="multipart/form-data">

      <fieldset>

        <div class="form-group">
          <label class="col-sm-2 control-label">Name</label>
          <div class="col-sm-10">
            <input class="form-control" type="text" name="name" value="<?php echo $info->name ?>" />
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">File</label>
          <div class="col-sm-10">
            <input class="" type="file" name="file" />
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">URL</label>
          <div class="col-sm-10">
            <input class="form-control" type="url" name="url" value="<?php echo $info->url ?>" />
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary" name="fileform" value="true">Save</button>
          </div>
        </div>
        
      </fieldset>
    
    </form>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
