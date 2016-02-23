<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';

$id = (int)$_GET['id'];
$sub_page = $id ? 3 : 4;

if ($_POST) {
  $post = array(
    'name' => html($_POST['name']),
    'profile' => $_POST['profile'],
    'url' => html($_POST['url']),
    'email' => html($_POST['email']),
  );
  if ($id) {
    $db->update("blog_authors",$post,"id = $id");
  } else {
    $db->insert("blog_authors",$post);
    $id = $db->insert_id;
  }
  header("Location: " . URL . "cms/blogauthorlist");
  exit();
}

if ($id) {
  $info = $db->record("SELECT * FROM blog_authors WHERE id = $id");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Blog Author | <?php echo SITENAME ?></title>
    <link href="css/summernote.css" rel="stylesheet">
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Blog Author</h1>

  <form method="post" class="form form-horizontal" enctype="multipart/form-data">
  
    <div class="form-group">
      <label class="col-sm-2 control-label">Name</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="name" value="<?php echo $info->name ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">E-mail</label>
      <div class="col-sm-10">
        <input class="form-control" type="email" name="email" value="<?php echo $info->email ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Website</label>
      <div class="col-sm-10">
        <input class="form-control" type="url" name="url" value="<?php echo $info->url ?>" />
      </div>
    </div>

    <p><strong>Profile:</strong></p>

    <textarea name="profile" class="hidden"><?php echo $info->profile ?></textarea>

    <div id="summernote"><?php echo $info->profile ?></div>

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
    $('textarea[name="profile"]').html($('#summernote').summernote('code'));
  });
});
</script>

  </body>
</html>
