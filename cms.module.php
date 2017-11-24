<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 21;

$mooc = 1;
$mooc_info = $db->record("SELECT * FROM mooc WHERE id = $mooc");

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM mooc_modules WHERE id = $id");

if ($_POST) {
  $post = array(
    'title' => html($_POST['title']),
    'instructions' => $_POST['content'],
  );
  if ($id) {
    $db->update("mooc_modules",$post,"id = $id");
  } else {
    $db->insert("mooc_modules",$post);
    $id = $db->insert_id;
  }
  header("Location: ".URL."cms.modules.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->title ?: 'Add Module' ?> | <?php echo SITENAME ?></title>
    <style type="text/css">
    .align-right{text-align:right}
    </style>
  </head>

  <body class="notranslate">

<?php require_once 'include.header.php'; ?>

  <h1><?php echo $info->title ?: 'Add Module' ?></h1>

  <ol class="breadcrumb">
    <li class="active"><a href="cms.moocs.php">MOOCs</a></li>
    <li><a href="cms.modules.php?id=<?php echo $mooc_info->name ?>"><?php echo $mooc_info->name ?></a></li>
    <li><?php echo $info->title ?></li>
  </ol>

  <?php if ($info->id) { ?>
  <p class="align-right"><a class="btn btn-primary" href="mooc/<?php echo $info->id ?>">View this module online</a></p>
  <?php } ?>

  <form method="post" class="form form-horizontal">
  
    <div class="form-group">
      <label class="col-sm-2 control-label">Title</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="title" value="<?php echo $info->title ?>" />
      </div>
    </div>

    <p><strong>Description:</strong></p>

    <textarea name="content" id="content"><?php echo $info->instructions ?></textarea>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>

  </form>

<?php require_once 'include.footer.php'; ?>
<?php require_once 'include.editor.php'; ?>

<script type="text/javascript">
$(function(){
  $("#content").change(function(){
    console.log ("Content changed");
  });
});
</script>

  </body>
</html>
