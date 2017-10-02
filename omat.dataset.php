<?php
require_once 'functions.php';
$section = 6;

$id = (int)$_GET['id'];

$sql = LOCAL ? '' : "AND mfa_dataset.access != 'private'";

$info = $db->record("SELECT mfa_dataset.*, research.title AS research_name
FROM mfa_dataset 
LEFT JOIN research ON mfa_dataset.research_project = research.id
WHERE mfa_dataset.id = $id $sql");

if (!count($info)) {
  kill("No MFA dataset found", "norecord");
}

$data = $db->record("SELECT 
  COUNT(*) AS total
FROM mfa_materials
  JOIN mfa_groups ON mfa_materials.mfa_group = mfa_groups.id
WHERE mfa_groups.dataset = $id");

$db->query("DELETE FROM mfa_groups WHERE dataset = $id");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->name ?> | <?php echo SITENAME ?></title>
  </head>

  <body class="omat">

<?php require_once 'include.header.php'; ?>

  <h1>OMAT Dataset: <?php echo $info->name ?></h1>

  <p>Dataset name: <strong><?php echo $info->name ?></strong></p>

  <p>Your dataset has been reset. <a href="omat/<?php echo $id ?>/dashboard">Back to your dashboard.</a></p>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
