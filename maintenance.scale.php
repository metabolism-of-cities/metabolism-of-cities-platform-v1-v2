<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 2;
$sub_page = 5;

$id = (int)$_GET['id'];
$project = (int)$_GET['project'];

$info = $db->record("SELECT * FROM mfa_scales WHERE id = $id");

if ($id) {
  if ($info->dataset != $project) {
    die("No access");
  }
}
$projectinfo = $db->record("SELECT * FROM mfa_dataset WHERE id = $project");

if ($_POST) {
  $post = array(
    'name' => html($_POST['name']),
    'standard_multiplier' => $_POST['standard_multiplier'] ? (float)$_POST['standard_multiplier'] : 1,
    'dataset' => $project,
  );
  if ($id) {
    $db->update("mfa_scales",$post,"id = $id");
  } else {
    $db->insert("mfa_scales",$post);
    $id = $db->lastInsertId();
  }
  header("Location: " . URL . "omat/$project/maintenance-scales/saved");
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Scales | <?php echo $info->name ?> | OMAT | <?php echo SITENAME ?></title>
    <style type="text/css">
    .alert-info h3{margin-top:0}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Types of Scales</h1>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/maintenance-scales">Types of Scales</a></li>
    <li class="active"><?php echo $id ? "Edit" : "Add" ?> Type of Scales</li>
  </ol>

  <form method="post" class="form form-horizontal">

    <?php if ($projectinfo->multiscale_as_proxy) { ?>
      <div class="alert alert-info">
        <h3>Standard multiplier</h3>
        <p>
          The standard multiplier allows you to define a standard value with which 
          the data on this scale should be multiplied to get the value for your 
          designated scale. For instance, if you study a city and this is the 
          province, and the city earns 67% of the provincial GDP (if GDP is your
          parameter), then you should fill out 0.67 here. 
        </p>
        <p>
          Please note: you will always have the chance to set a different multiplier
          for specific data points, so this is only the standard value that is to 
          be used if no individual multiplier is entered.
        </p>
      </div>
    <?php } ?>
  
    <div class="form-group">
      <label class="col-sm-2 control-label">Name</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="name" value="<?php echo $info->name ?>" />
      </div>
    </div>

    <?php if ($projectinfo->multiscale_as_proxy) { ?>
      <div class="form-group">
        <label class="col-sm-2 control-label">Standard multiplier</label>
        <div class="col-sm-10">
          <input class="form-control" type="text" name="standard_multiplier" value="<?php echo $info->standard_multiplier ?>" />
        </div>
      </div>
    <?php } ?>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>

  </form>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
