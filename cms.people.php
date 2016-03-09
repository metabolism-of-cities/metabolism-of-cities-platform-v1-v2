<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';

$id = (int)$_GET['id'];
$sub_page = $id ? 7 : 8;

if ($_POST) {
  $post = array(
    'firstname' => html($_POST['firstname']),
    'lastname' => html($_POST['lastname']),
    'url' => html($_POST['url']),
    'email' => html($_POST['email']),
    'affiliation' => html($_POST['affiliation']),
    'city' => html($_POST['city']),
    'country' => html($_POST['country']),
  );
  if ($id) {
    $db->update("people",$post,"id = $id");
    $print = "Information was saved";
  } else {
    $db->insert("people",$post);
    $id = $db->insert_id;
    header("Location: " . URL . "cms/peoplelist");
    exit();
  }
}

if ($id) {
  $info = $db->record("SELECT * FROM people WHERE id = $id");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Contact | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Contact</h1>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <form method="post" class="form form-horizontal" enctype="multipart/form-data">
  
    <div class="form-group">
      <label class="col-sm-2 control-label">First Name</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="firstname" value="<?php echo $info->firstname ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Last Name</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="lastname" value="<?php echo $info->lastname ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">E-mail</label>
      <div class="col-sm-10">
        <input class="form-control" type="email" name="email" value="<?php echo $info->email ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Affiliation</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="affiliation" value="<?php echo $info->affiliation ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">City</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="city" value="<?php echo $info->city ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Country</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="country" value="<?php echo $info->country ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Website</label>
      <div class="col-sm-10">
        <input class="form-control" type="url" name="url" value="<?php echo $info->url ?>" />
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
