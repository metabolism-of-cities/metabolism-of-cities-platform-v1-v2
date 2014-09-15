<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 3;

$id = (int)$_GET['id'];
$project = (int)$_GET['project'];

if ($_GET['finished']) {
  $db->query("UPDATE mfa_activities_log SET end = NOW() WHERE id = $id LIMIT 1");
  $print = "This activity was finished";
}

$info = $db->record("SELECT l.*, a.name AS type FROM 
mfa_activities_log l
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

if ($_GET['delete']) {
  $db->query("DELETE FROM mfa_activities_log WHERE id = $id");
  if ($info->contact) {
    header("Location: " . URL . "omat/$project/viewcontact/{$info->contact}/activity-deleted");
    exit();
  } else {
    header("Location: " . URL . "omat/$project/viewsource/{$info->source}/activity-deleted");
    exit();
  }
}

if ($_GET['saved']) {
  $print = "Changes have been saved";
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Activity # <?php echo $id ?> | <?php echo $info->source ? $sourceinfo-> name : $contactinfo->name ?> | <?php echo SITENAME ?></title>
    <style type="text/css">
    a.right{float:right;margin-left:5px}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <a href="omat/<?php echo $project ?>/activity/<?php echo $info->id ?>" class="btn btn-primary right">Edit</a>
  <a href="omat/<?php echo $project ?>/viewactivity/<?php echo $info->id ?>/delete" class="btn btn-danger right" onclick="javascript:return confirm('Are you sure?')">Delete</a>

  <h1>Activity Details</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <?php if ($info->source) { ?>
      <li><a href="omat/<?php echo $project ?>/sources">Sources</a></li>
      <li><a href="omat/<?php echo $project ?>/viewsource/<?php echo $info->source ?>"><?php echo $sourceinfo->name ?></a></li>
    <?php } else { ?>
      <li><a href="omat/<?php echo $project ?>/contacts">Contacts</a></li>
      <li><a href="omat/<?php echo $project ?>/viewcontact/<?php echo $info->contact ?>"><?php echo $contactinfo->name ?></a></li>
    <?php } ?>
    <li class="active">Activity #<?php echo $id ?></li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <dl class="dl-horizontal">

    <dt>Activity ID</dt>
    <dd>#<?php echo $id ?></dd>

    <?php if ($info->contact) { ?>
      <dt>Contact</dt>
      <dd><a href="omat/<?php echo $project ?>/viewcontact/<?php echo $info->contact ?>"><?php echo $contactinfo->name ?></a></dd>
    <?php } elseif ($info->source) { ?>
      <dt>Source</dt>
      <dd><a href="omat/<?php echo $project ?>/viewsource/<?php echo $info->source ?>"><?php echo $sourceinfo->name ?></a></dd>
    <?php } ?>

    <dt>Type</dt>
    <dd><?php echo $info->type ?></dd>

    <?php if ($info->end) { ?>
      <dt>Start</dt>
      <dd><?php echo format_date("r", $info->start) ?></dd>
      <dt>End</dt>
      <dd><?php echo format_date("r", $info->end) ?></dd>
      <dt>Total time</dt>
      <dd><?php echo $info->time ?> minutes</dd>
    <?php } else { 
      $start = strtotime($info->start);
      $now = time();
      $seconds = $now-$start;
      $minutes = round($seconds/60);
    ?>

    <dt>Start</dt>
    <dd><?php echo $minutes ?> minutes ago</dd>

    <?php } ?>
    
  </dl>

  <?php if (!$info->end) { ?>
    <p><a href="omat/<?php echo $project ?>/viewactivity/<?php echo $id ?>/finished" class="btn btn-success btn-lg">
    <i class="fa fa-check"></i> 
    Activity was finished</a></p>
  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
