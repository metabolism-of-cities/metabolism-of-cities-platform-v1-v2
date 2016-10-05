<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';

$id = (int)$_GET['id'];
$sub_page = $id ? 11 : 12;

if ($_POST) {
  $post = array(
    'title' => html($_POST['title']),
    'date' => mysql_clean(format_date("Y-m-d", $_POST['date'])),
    'description' => $_POST['description'],
    'contributor' => html($_POST['contributor']),
    'url' => html($_POST['url']),
    'source_details' => html($_POST['source_details']),
    'paper' => (int)$_POST['paper'] ?: NULL,
    'year' => (int)$_POST['year'] ?: NULL,
  );
  if ($id) {
    $db->update("datavisualizations",$post,"id = $id");
  } else {
    $db->insert("datavisualizations",$post);
    $last = $db->record("SELECT id FROM datavisualizations ORDER BY id DESC LIMIT 1");
    $id = $last->id;
  }
  if ($_FILES['file']['name']) {
    $file = "media/dataviz/$id";
    move_uploaded_file($_FILES['file']['tmp_name'],$file);
    $image = new SimpleImage();
    $image->load($file);
    $image->resizeToWidth(1024);
    $image->save($file.".jpg");
    $image->resizeToWidth(250);
    $image->save($file.".thumb.jpg");
    unlink($file);
  }
  header("Location: " . URL . "cms/datavizlist");
  exit();
}

$papers = $db->query("SELECT id, title FROM papers WHERE status = 'active' ORDER BY title");
$info = $db->record("SELECT * FROM datavisualizations WHERE id = $id");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Data Visualization Post | <?php echo SITENAME ?></title>
    <link rel="stylesheet" href="css/select2.min.css" />
    <link href="css/summernote.css" rel="stylesheet">
    <style type="text/css">
    .navbar-fixed-top{z-index:1}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Data Visualization Post</h1>

    <form method="post" class="form form-horizontal" enctype="multipart/form-data">
  
    <div class="form-group">
      <label class="col-sm-2 control-label">Title</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="title" value="<?php echo $info->title ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Contributor</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="contributor" value="<?php echo $info->contributor ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Date</label>
      <div class="col-sm-10">
        <input class="form-control" type="date" name="date" value="<?php echo $info->date ?>" />
      </div>
    </div>

    <p><strong>Description:</strong></p>

    <textarea name="description" class="hidden"><?php echo $info->description ?></textarea>

    <div id="summernote"><?php echo $info->description ?></div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Publication</label>
      <div class="col-sm-10">
        <select name="paper" class="form-control select2">
            <option value=""></option>
          <?php foreach ($papers as $row) { ?>
            <option value="<?php echo $row['id'] ?>"<?php if ($info->paper == $row['id']) { echo ' selected'; } ?>><?php echo substr($row['title'], 0, 100) ?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Sourc details</label>
      <div class="col-sm-10">
        <textarea 
        placeholder="Only required if not linked to a publication"
        class="form-control" name="source_details"><?php echo $info->source_details ?></textarea>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">URL</label>
      <div class="col-sm-10">
        <input class="form-control" type="url" name="url" value="<?php echo $info->url ?>" 
        placeholder="Only required if not linked to a publication"
        />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Year</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="year" value="<?php echo $info->year ?>" placeholder="Only required if not linked to a publication" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">File</label>
      <div class="col-sm-10">
        <input class="form-control" type="file" name="file" />
      </div>
    </div>

    <?php if (file_exists("media/dataviz/$id.jpg")) { ?>
    <div class="well">
      <img src="media/dataviz/<?php echo $id ?>.jpg" alt="" />
    </div>
    <?php } ?>


    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>

  </form>

<?php require_once 'include.footer.php'; ?>

<script src="//cdn.jsdelivr.net/webshim/1.15.8/extras/modernizr-custom.js"></script>
<script src="//cdn.jsdelivr.net/webshim/1.15.8/polyfiller.js"></script>
<script type="text/javascript" src="js/select2.min.js"></script>
<script src="js/editor.js"></script>
<script>
  webshims.setOptions('waitReady', false);
  webshim.setOptions("forms-ext", {
    "widgets": {
      "startView": 2,
      "openOnFocus": true
    }
  });
  webshims.polyfill('forms forms-ext');
</script>

<script type="text/javascript">
$(function(){
  $(".select2").select2();
  $('#summernote').summernote();
  $("form").submit(function() {
    $('textarea[name="description"]').html($('#summernote').summernote('code'));
  });
});
</script>


  </body>
</html>
