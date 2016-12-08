<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';

$id = (int)$_GET['id'];
$sub_page = $id ? 1 : 2;

if ($_POST) {
  $post = array(
    'title' => html($_POST['title']),
    'date' => mysql_clean(format_date("Y-m-d", $_POST['date'])),
    'content' => $_POST['content'],
    'active' => (int)$_POST['active'],
  );
  if ($id) {
    $db->update("blog",$post,"id = $id");
  } else {
    $db->insert("blog",$post);
    $last = $db->record("SELECT id FROM blog ORDER BY id DESC LIMIT 1");
    $id = $last->id;
  }
  $db->query("DELETE FROM blog_links WHERE blog = $id");
  if (is_array($_POST['publications'])) {
    foreach ($_POST['publications'] as $key => $value) {
      $post = array(
        'blog' => $id,
        'paper' => (int)$value,
      );
      $db->insert("blog_links",$post);
    }
  }
  $db->query("DELETE FROM blog_authors_pivot WHERE blog = $id");
  if (is_array($_POST['authors'])) {
    foreach ($_POST['authors'] as $key => $value) {
      $post = array(
        'blog' => $id,
        'author' => (int)$value,
      );
      $db->insert("blog_authors_pivot",$post);
    }
  }
  header("Location: " . URL . "cms/bloglist");
  exit();
}

$authors = $db->query("SELECT id, name FROM blog_authors ORDER BY name");
$papers = $db->query("SELECT id, title FROM papers WHERE status = 'active' ORDER BY title");
$info = $db->record("SELECT * FROM blog WHERE id = $id");
$links = $db->query("SELECT * FROM blog_links WHERE blog = $id");
foreach ($links as $row) {
  $linked[$row['paper']] = true;
}
$links_authors = $db->query("SELECT * FROM blog_authors_pivot WHERE blog = $id");
foreach ($links_authors as $row) {
  $linked_authors[$row['author']] = true;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Blog Post | <?php echo SITENAME ?></title>
    <link rel="stylesheet" href="css/select2.min.css" />
    <link href="css/summernote.css" rel="stylesheet">
    <style type="text/css">
    .navbar-fixed-top{z-index:1}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Blog Post</h1>

  <form method="post" class="form form-horizontal">
  
    <div class="form-group">
      <label class="col-sm-2 control-label">Title</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="title" value="<?php echo $info->title ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Date</label>
      <div class="col-sm-10">
        <input class="form-control" type="date" name="date" value="<?php echo $info->date ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Author(s)</label>
      <div class="col-sm-10">
        <select name="authors[]" class="form-control select2" multiple>
          <?php foreach ($authors as $row) { ?>
            <option value="<?php echo $row['id'] ?>"<?php if ($linked_authors[$row['id']]) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <p><strong>Content:</strong></p>

    <textarea name="content" class="hidden"><?php echo $info->content ?></textarea>

    <div id="summernote"><?php echo $info->content ?></div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Publications</label>
      <div class="col-sm-10">
        <select name="publications[]" class="form-control select2" multiple>
          <?php foreach ($papers as $row) { ?>
            <option value="<?php echo $row['id'] ?>"<?php if ($linked[$row['id']]) { echo ' selected'; } ?>><?php echo substr($row['title'], 0, 100) ?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="active" value="1" <?php echo $info->active ? 'checked' : ''; ?> /> 
                        Published
                </label>
          </div>
        </div>
    </div>

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
    $('textarea[name="content"]').html($('#summernote').summernote('code'));
  });
});
</script>


  </body>
</html>
