<?php
if ($_GET['public_login']) {
  $public_login = true;
}
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 3;
$sub_page = 2;

$id = (int)$_GET['id'];
$indicator = (int)$_GET['indicator'];

if ($_POST['name']) {
  $post = array(
    'name' => html($_POST['name']),
    'type' => (int)$_POST['type'],
    'dataset' => $project,
    'description' => html($_POST['description']),
  );
  if ($indicator) {
    $db->update("mfa_indicators",$post,"id = $indicator");
    $print = "Information was saved";
    unset($indicator);
  } else {
    $db->insert("mfa_indicators",$post);
    $print = "Indicator was added";
  }
}

$list = $db->query("SELECT i.*, t.name AS type_name,
  (SELECT COUNT(*) FROM mfa_indicators_formula
    JOIN mfa_groups ON mfa_indicators_formula.mfa_group = mfa_groups.id
  WHERE mfa_groups.dataset = $project AND indicator = i.id) AS formula
FROM mfa_indicators i
  JOIN mfa_indicators_types t ON i.type = t.id
WHERE i.dataset = $project OR i.dataset IS NULL
ORDER BY i.type, i.id");

$types = $db->query("SELECT * FROM mfa_indicators_types ORDER BY name");

if ($indicator) {
  $info = $db->record("SELECT * FROM mfa_indicators WHERE id = $indicator");
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Indicators | <?php echo SITENAME ?></title>
    <style type="text/css">
    h2{font-size:23px}
    .badge{z-index:100}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <a href="omat/<?php echo $project ?>/indicator-list" class="btn btn-primary pull-right">
    View all
  </a>

  <h1>Indicators</h1>

  <ol class="breadcrumb">
      <?php if ($public_login) { ?>
        <li><a href="omat/<?php echo $project ?>/projectinfo"><?php echo $check->name ?></a></li>
      <?php } else { ?>
        <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
      <?php } ?>
    <li class="active">Indicators</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <?php if (!$indicator) { ?>

    <div class="row">

    <?php $type = false; foreach ($list as $row) { ?>

      <?php if ($row['formula'] || !$public_login) { if ($row['type_name'] != $type) { ?>
      <?php if ($type) { ?></div></div><?php } ?>
      <div class="col-md-4">
        <h2><?php echo $row['type_name'] ?></h2>
      <div class="list-group">
      <?php } $type = $row['type_name']; ?>

        <a href="<?php echo $public_login ? 'omat-public' : 'omat'; ?>/<?php echo $project ?>/reports-indicator/<?php echo $row['id'] ?>" class="list-group-item">
          <h4 class="list-group-item-heading"><?php echo $row['name'] ?></h4>
          <p class="list-group-item-text"><?php echo truncate($row['description'],140) ?>
          <?php if ($row['formula']) { ?>
            <span class="badge pull-right"><i class="fa fa-bar-chart"></i></span>
          <?php } ?>
          </p>
        </a>

    <?php } } ?>

    </div>

    </div>

    </div>

    <p class="clear"><span class="badge"><i class="fa fa-bar-chart"></i></span> These indicators are automatically calculated for your dataset.</p>

  <?php } ?>

  <?php if (!$public_login) { ?>

  <h2><?php echo $indicator ? "Edit" : "Add"; ?> indicator</h2>

  <form method="post" class="form form-horizontal">

    <div class="form-group">
      <label class="col-sm-2 control-label">Name</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="name" value="<?php echo $info->name ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Type</label>
      <div class="col-sm-10">
        <select name="type" class="form-control">
          <?php foreach ($types as $row) { ?>
            <option value="<?php echo $row['id'] ?>"<?php if ($row['id'] == $info->name) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Description</label>
      <div class="col-sm-10">
        <textarea class="form-control" name="description"><?php echo $info->description ?></textarea>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Add</button>
      </div>
    </div>
  
  </form>

  <?php } ?>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
