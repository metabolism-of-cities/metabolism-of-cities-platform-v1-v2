<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 2;
$sub_page = 1;

$section = 6;
$load_menu = 1;
$sub_page = 3;

$id = (int)$_GET['id'];
$project = (int)$_GET['project'];

if ($_GET['finished']) {
  $db->query("UPDATE mfa_activities_log SET end = NOW() WHERE id = $id LIMIT 1");
  $print = "This activity was finished";
}

$info = $db->record("SELECT l.* 
FROM mfa_activities_log l
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
    'start' => mysql_clean(format_date("Y-m-d H:i:s", $_POST['start'])),
    'end' => mysql_clean(format_date("Y-m-d H:i:s", $_POST['end'])),
    'activity' => (int)$_POST['type'],
  );
  $db->update("mfa_activities_log",$post,"id = $id");
  header("Location: " . URL . "omat/$project/viewactivity/$id/saved");
  exit();
}

$types = $db->query("SELECT * FROM mfa_activities WHERE dataset = $project ORDER BY name");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Activity # <?php echo $id ?> | <?php echo $info->source ? $sourceinfo-> name : $contactinfo->name ?> | <?php echo SITENAME ?></title>
  </head>

  <body class="omat">

<?php require_once 'include.header.php'; ?>

  <h1>Edit Activity</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <?php if ($info->source) { ?>
      <li><a href="omat/<?php echo $project ?>/sources">Sources</a></li>
      <li><a href="omat/<?php echo $project ?>/viewsource/<?php echo $info->source ?>"><?php echo $sourceinfo->name ?></a></li>
    <?php } else { ?>
      <li><a href="omat/<?php echo $project ?>/contacts">Sources</a></li>
      <li><a href="omat/<?php echo $project ?>/viewcontact/<?php echo $info->contact ?>"><?php echo $contactinfo->name ?></a></li>
    <?php } ?>
      <li><a href="omat/<?php echo $project ?>/viewactivity/<?php echo $id ?>">Activity #<?php echo $id ?></a></li>
      <li class="active">Edit Activity</li>
  </ol>

  <form method="post" class="form-horizontal">

    <div class="form-group">
      <label class="col-sm-2 control-label">Type</label>
      <div class="col-sm-10">
        <select name="type" class="form-control">
          <?php foreach ($types as $row) { ?>
            <option value="<?php echo $row['id'] ?>"<?php if ($row['id'] == $info->activity) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Start</label>
      <div class="col-sm-10">
        <input class="form-control" type="datetime" name="start" value="<?php echo $info->start ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">End</label>
      <div class="col-sm-10">
        <input class="form-control" type="datetime" name="end" value="<?php echo $info->end ?>" />
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
