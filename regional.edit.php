<?php
require_once 'functions.php';
$section = 5;
$page = 99;

if (PRODUCTION) {
  die();
}

$id = (int)$_GET['id'];
$new = (int)$_GET['new'];

if ($new) {
  $info = $db->record("SELECT * FROM papers WHERE id = $new");
  $post = array(
    'paper' => $new,
    'name' => $info->title,
  );
  $db->insert("regional",$post);
  $id = $db->lastInsertId();
  
  header("Location: " . URL . "regional.edit.php?id=$id");
  exit();
}

if ($_POST) {
  $post = array(
    'name' => html($_POST['name']),
  );
  $db->update("regional",$post,"id = $id");
  header("Location: " . URL . "page/regional/saved");
  exit();
}

$info = $db->record("SELECT * FROM regional WHERE id = $id");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->name ?> | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Edit Regional MFA Study Deails</h1>

  <form method="post" class="form form-horizontal">

    <div class="form-group">
      <label class="col-sm-2 control-label">Name</label>
      <div class="col-sm-10">
        <input class="form-control" type="name" name="name" value="<?php echo $info->name ?>" />
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
  
  </form>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>