<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 2;
$sub_page = 7;

$id = (int)$_GET['id'];
$project = $id; 

if ($_GET['mass']) {
  $type = "mass";
  $score = (int)$_GET['mass'];
  $label = "1,000-2,000 tons";
} else {
  $type = "value";
  $score = (int)$_GET['value'];
  $label = 'US$ 1,000m-2,000m';
}

$info = $db->record("SELECT * FROM mfa_industries_labels WHERE dataset = $id AND type = '$type' AND score = $score");

if ($_POST) {
  $post = array(
    'type' => mysql_clean($type),
    'dataset' => $project,
    'label' => html($_POST['name']),
    'score' => $score,
  );
  if ($info->id) {
    $db->update("mfa_industries_labels",$post,"id = {$info->id}");
  } else {
    $db->insert("mfa_industries_labels",$post);
  }
  header("Location: " . URL . "omat/$project/maintenance-industrysizes/saved");
  exit();
}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Industry Size | <?php echo $info->name ?> | OMAT | <?php echo SITENAME ?></title>
    <style type="text/css">
    .col-sm-10 span{position:relative;top:5px}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Industry Size Configuration</h1>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/maintenance-industrysizes">Industry Size Configuration</a></li>
    <li class="active"><?php echo $id ? "Edit" : "Add" ?> Industry Size</li>
  </ol>

  <form method="post" class="form form-horizontal">

    <div class="form-group">
      <label class="col-sm-2 control-label">Score</label>
      <div class="col-sm-10">
        <span>
          <?php echo $score ?>
        </span>
      </div>
    </div>
  
    <div class="form-group">
      <label class="col-sm-2 control-label">Label</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="name" value="<?php echo $info->label ?>" placeholder="For instance: <?php echo $label ?>" />
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
