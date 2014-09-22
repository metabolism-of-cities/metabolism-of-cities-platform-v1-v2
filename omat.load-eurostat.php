<?php
require_once 'functions.php';
$id = (int)$_GET['id'];
$list = $db->query("SELECT * FROM mfa_groups WHERE dataset IS NULL");

$added = false;
// Load all datagroups
foreach ($list as $row) {
  $post = array(
    'section' => mysql_clean($row['section']),
    'name' => mysql_clean($row['name']),
    'dataset' => $id,
  );
  $db->insert("mfa_groups",$post);
  $group = $db->lastInsertId();
  $db->query("INSERT INTO mfa_materials 
    (mfa_group, code, name)
  SELECT $group, code, name
    FROM mfa_materials WHERE mfa_group = {$row['id']}");

  $indicators = $db->query("SELECT * FROM mfa_indicators_formula WHERE mfa_group = {$row['id']}");
  foreach ($indicators as $subrow) {
    $db->query("INSERT INTO mfa_indicators_formula 
      (indicator, type, mfa_group)
    SELECT indicator, type, $group
      FROM mfa_indicators_formula WHERE id = {$subrow['id']}");
  }
}

header("Location: " . URL . "omat/$id/manage/loaded");
exit();

?>
