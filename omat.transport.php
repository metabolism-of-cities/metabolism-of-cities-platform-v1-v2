<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 3;

$id = (int)$_GET['id'];
$project = (int)$_GET['project'];


$info = $db->record("SELECT l.*, t.*, t.id AS transportation_id
FROM mfa_activities_log l
  LEFT JOIN mfa_transportation t ON l.id = t.activity
  JOIN mfa_activities a ON l.activity = a.id
WHERE l.id = $id AND a.dataset = $project");

if ($info->contact) {
  $contactinfo = $db->record("SELECT * FROM mfa_contacts WHERE id = {$info->contact}");
  $sub_page = 2;
} else {
  $sourceinfo = $db->record("SELECT * FROM mfa_sources WHERE id = {$info->source}");
  $sub_page = 3;
}

if (!count($info)) {
  die("Activity not found");
}

if ($_POST) {
  $post = array(
    'transportation_mode' => (int)$_POST['transportation_mode'],
    'from_destination' => html($_POST['from']),
    'to_destination' => html($_POST['to']),
    'distance' => (float)$_POST['distance'],
    'cost' => (float)$_POST['cost'],
    'activity' => (int)$id,
    'notes' => html($_POST['notes']),
  );
  if ($info->transportation_id) {
    $db->update("mfa_transportation",$post,"id = " . $info->transportation_id);
  } else {
    $db->insert("mfa_transportation",$post);
  }
  header("Location: " . URL . "omat/$project/viewactivity/$id");
  exit();
}

if ($_GET['delete']) {
  $db->query("DELETE FROM mfa_transportation WHERE activity = $id");
  header("Location: " . URL . "omat/$project/viewactivity/$id");
  exit();
}

if ($_GET['saved']) {
  $print = "Changes have been saved";
}

$modes = $db->query("SELECT * FROM mfa_transportation_modes ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Transport details activity # <?php echo $id ?> | <?php echo $info->source ? $sourceinfo-> name : $contactinfo->name ?> | <?php echo SITENAME ?></title>
    <style type="text/css">
    a.right{float:right;margin-left:5px}
    </style>
  </head>

  <body class="omat">

<?php require_once 'include.header.php'; ?>

  <a href="omat/<?php echo $project ?>/transport/<?php echo $id ?>/delete" class="btn btn-danger right" onclick="javascript:return confirm('Are you sure?')">Delete</a>

  <h1>Transportation Details</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <?php if ($info->source) { ?>
      <li><a href="omat/<?php echo $project ?>/sources">Sources</a></li>
      <li><a href="omat/<?php echo $project ?>/viewsource/<?php echo $info->source ?>"><?php echo $sourceinfo->name ?></a></li>
    <?php } else { ?>
      <li><a href="omat/<?php echo $project ?>/contacts">Contacts</a></li>
      <li><a href="omat/<?php echo $project ?>/viewcontact/<?php echo $info->contact ?>"><?php echo $contactinfo->name ?></a></li>
    <?php } ?>
    <li><a href="omat/<?php echo $project ?>/viewactivity/<?php echo $id ?>">Activity #<?php echo $id ?></a></li>
    <li class="active">Transportation details</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <form method="post" class="form form-horizontal">
    
    <div class="form-group">
      <label class="col-sm-2 control-label">Transportation mode</label>
      <div class="col-sm-10">
        <select name="transportation_mode" class="form-control" required>
          <option value=""></option>
          <?php foreach ($modes as $row) { ?>
            <option value="<?php echo $row['id'] ?>"<?php if ($row['id'] == $info->transportation_mode) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">From</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="from" value="<?php echo $info->from_destination ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">To</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="to" value="<?php echo $info->to_destination ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Distance</label>
      <div class="col-sm-10">
        <input class="form-control" type="number" step="any" name="distance" value="<?php echo $info->distance ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Cost</label>
      <div class="col-sm-10">
        <input class="form-control" type="number" step="any" name="cost" value="<?php echo $info->cost ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Notes</label>
      <div class="col-sm-10">
        <textarea class="form-control" name="notes"><?php echo br2nl($info->notes) ?></textarea>
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
