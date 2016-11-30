<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';

$id = (int)$_GET['id'];
$sub_page = $id ? 13 : 14;

if ($_POST) {
  $post = array(
    'title' => html($_POST['title']),
    'author' => html($_POST['author']),
    'description' => $_POST['description'],
    'url' => html($_POST['url']),
  );
  if ($id) {
    $db->update("videos",$post,"id = $id");
  } else {
    $db->insert("videos",$post);
  }
  header("Location: " . URL . "cms/videolist");
  exit();
}

$info = $db->record("SELECT * FROM videos WHERE id = $id");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Video | <?php echo SITENAME ?></title>
    <link rel="stylesheet" href="css/select2.min.css" />
    <link href="css/summernote.css" rel="stylesheet">
    <style type="text/css">
    .navbar-fixed-top{z-index:1}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Video</h1>

    <form method="post" class="form form-horizontal" enctype="multipart/form-data">
  
    <div class="form-group">
      <label class="col-sm-2 control-label">Title</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="title" value="<?php echo $info->title ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Author</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="author" value="<?php echo $info->author ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">URL</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="url" placeholder="Last bit of YouTube URL, e.g. 6Sf4aCFhUyQ" value="<?php echo $info->url ?>" />
      </div>
    </div>

    <p><strong>Description:</strong></p>

    <textarea name="description" class="hidden"><?php echo $info->description ?></textarea>

    <div id="summernote"><?php echo $info->description ?></div>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>

  </form>

<?php require_once 'include.footer.php'; ?>

<script src="js/editor.js"></script>

<script type="text/javascript">
$(function(){
  $('#summernote').summernote();
  $("form").submit(function() {
    $('textarea[name="description"]').html($('#summernote').summernote('code'));
  });
});
</script>


  </body>
</html>
