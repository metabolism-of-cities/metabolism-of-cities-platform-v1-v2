<?php
$skip_login = true;
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 3;
$page = 1;

$id = (int)$_GET['id'];

$info = $db->record("SELECT * FROM research WHERE id = $id AND deleted_on IS NULL");

if (!$info->id) {
  kill("Record not found");
}

if ($_GET['delete'] && defined("ADMIN")) {
  $db->query("UPDATE research SET deleted_on = NOW() WHERE id = $id");
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->title ?> | <?php echo _('Research details'); ?> | <?php echo SITENAME ?></title>
    <style type="text/css">
    dd{margin-bottom:20px}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

<?php if ($_GET['delete']) { ?>
  <div class="alert alert-danger">This record has been deleted.</div>
<?php } ?>

<h1><?php echo $info->title ?></h1>

<p><?php echo $info->description ?></p>

<dl class="horizontal">
  <dt>Researcher(s)</dt>
  <dd><?php echo $info->researcher ?></dd>

  <dt>Supervisor(s) / Coordinator(s)</dt>
  <dd><?php echo $info->supervisor ?></dd>

  <dt>Institution</dt>
  <dd><?php echo $info->institution ?></dd>

  <dt>Status</dt>
  <dd><?php echo $info->status ?></dd>

  <dt>Completion target date</dt>
  <dd><?php echo $info->target_finishing_date ?></dd>

  <dt>More information</dt>
  <dd><?php echo $info->email ?></dd>

</dl>

<?php if (defined("ADMIN")) { ?>
  <p>
    <a href="research.php?id=<?php echo $id ?>&amp;delete=true" onclick="javascript:return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
    <a href="omat/create/<?php echo $id ?>" class="btn btn-primary">Create an online dataset</a>
  </p>
<?php } ?>

<p><a href="research/list" class="btn btn-primary">&laquo; Back to the list</a></p>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
