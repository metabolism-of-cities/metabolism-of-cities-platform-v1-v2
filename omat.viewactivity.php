<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 3;

$id = (int)$_GET['id'];
$project = (int)$_GET['project'];

if ($_GET['finished']) {
  $db->query("UPDATE mfa_activities_log SET end = NOW() WHERE id = $id AND end IS NULL LIMIT 1");
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

$transport = $db->record("SELECT t.*, m.name AS mode 
FROM mfa_transportation t
  JOIN mfa_transportation_modes m ON t.transportation_mode = m.id
WHERE activity = $id");
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

  <p>
    <a href="omat/<?php echo $project ?>/transport/<?php echo $id ?>" class="btn btn-default pull-right">
      <i class="fa fa-bicycle"></i>
      Transportation details
    </a>
  </p>

  <?php if ($transport->id) { ?>
    <h1>Transportation details</h1>

    <dl class="dl-horizontal">
      <dt>Mode</dt>
      <dd><?php echo $transport->mode ?></dd>

      <?php if ($transport->from_destination && $transport->to_destination) { ?>
        <dt>Route</dt>
        <dd><?php echo $transport->from_destination ?> <em>to</em> <?php echo $transport->to_destination ?></dd>
      <?php } ?>

      <?php if ($transport->cost > 0) { ?>
        <dt>Cost</dt>
        <dd>R<?php echo number_format($transport->cost,2) ?></dd>
      <?php } ?>

      <?php if ($transport->distance > 0) { ?>
        <dt>Distance</dt>
        <dd><?php echo number_format($transport->distance,2) ?> km</dd>

        <?php if ($info->time) { ?>

          <dt>Average speed</dt>
          <dd><?php echo number_format($transport->distance/$info->time*60,1) ?> km/h</dd>

        <?php } ?>

      <?php } ?>

    </dl>

    <?php if ($transport->from_destination && $transport->to_destination) { ?>
    
      <iframe width="900" height="600" frameborder="0" scrolling="no" marginheight="0"
      marginwidth="0" src="http://maps.google.com/maps?saddr=<?php echo urlencode($transport->from_destination) ?>&amp;daddr=<?php echo urlencode($transport->to_destination); ?>&amp;hl=en&amp;ie=UTF8&amp;output=embed"></iframe>

   <?php } ?>

   <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
