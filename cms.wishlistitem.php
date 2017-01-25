<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';

$id = (int)$_GET['id'];
$sub_page = 16;

if ($_POST) {
  $post = array(
    'last_update' => mysql_clean(date("Y-m-d H:i:s")),
    'description' => $_POST['description'],
    'public' => (int)$_POST['public'],
    'type' => (int)$_POST['type'],
    'assigned_to' => (int)$_POST['assigned_to'] ?: NULL,
  );

  if ($_POST['parent_item']) {
    $post['parent_item'] = (int)$_POST['parent_item'];
  }

  if ($id) {
    $db->update("wishlist",$post,"id = $id");
  } else {
    $db->insert("wishlist",$post);
  }
  header("Location: " . URL . "cms/wishlist/saved");
  exit();
}

$info = $db->record("SELECT * FROM wishlist WHERE id = $id");
$types = $db->query("SELECT * FROM wishlist_types ORDER BY name");
$users = $db->query("SELECT 
users.* 
FROM users_admin a 
JOIN users ON a.user = users.user_id
ORDER BY user_name");

if ($_GET['parent']) {
  $parent = (int)$_GET['parent'];
  $info = $db->query("SELECT type FROM wishlist WHERE id = $parent");
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>To Do item | <?php echo SITENAME ?></title>
    <link href="css/summernote.css" rel="stylesheet">
    <style type="text/css">
    .navbar-fixed-top{z-index:1}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>To Do Item</h1>

  <form method="post" class="form form-horizontal">
  
    <div class="form-group">
      <label class="col-sm-2 control-label">Type</label>
      <div class="col-sm-10">
        <select name="type" class="form-control">
          <?php foreach ($types as $row) { ?>
            <option value="<?php echo $row['id'] ?>"<?php if ($info->type == $row['id']) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Assigned to</label>
      <div class="col-sm-10">
        <select name="assigned_to" class="form-control ">
          <option value=""></option>
          <?php foreach ($users as $row) { ?>
            <option value="<?php echo $row['user_id'] ?>"<?php if ($info->assigned_to == $row['user_id']) { echo ' selected'; } ?>><?php echo $row['user_name'] ?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <p><strong>Content:</strong></p>

    <textarea name="description" class="hidden"><?php echo $info->content ?></textarea>

    <div id="summernote"><?php echo $info->description ?></div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="public" value="1" <?php echo $info->public ? 'checked' : ''; ?> /> 
                        Public
                </label>
          </div>
        </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Save</button>
        <input type="hidden" name="parent_item" value="<?php echo $parent ?>" />
      </div>
    </div>

  </form>

<?php require_once 'include.footer.php'; ?>

<script src="//cdn.jsdelivr.net/webshim/1.15.8/extras/modernizr-custom.js"></script>
<script src="//cdn.jsdelivr.net/webshim/1.15.8/polyfiller.js"></script>
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
  $('#summernote').summernote();
  $("form").submit(function() {
    $('textarea[name="description"]').html($('#summernote').summernote('code'));
  });
});
</script>


  </body>
</html>
