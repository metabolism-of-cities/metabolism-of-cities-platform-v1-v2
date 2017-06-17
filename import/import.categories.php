<?php
require_once '../functions.php';
$count = 0;

$file = "categories";
$data = file_get_contents($file);
$explode = explode("\n", $data);
foreach ($explode as $value) {
  $sub = explode("\t", $value);
  $area = $sub[0];
  $subarea = $sub[1];
  $indicator = $sub[2];
  $count++;
  if ($area == 'Material') {
    $area = 'Materials';
  }
  if (!$subarea) {
    $subarea = "General";
  }
  $convert = array(
    'wastewaterter production' => 'Wastewater production',
  );
  if ($convert[$subarea]) {
    $subarea = $convert[$subarea];
  }
  if ($count > 1 && $area) {
    $check = $db->record("SELECT * FROM data_areas WHERE name = '".mysql_clean($area)."'");
    if ($check->id) {
      $area_id = $check->id;
    } else {
      $post = array(
        'name' => mysql_clean($area),
      );
      $db->insert("data_areas",$post);
      $area_id = $db->lastInsertId();
    }
    $check = $db->record("SELECT * FROM data_subareas WHERE name = '".mysql_clean($subarea)."' AND area = $area_id");
    if ($check->id) {
      $subarea_id = $check->id;
    } else {
      $post = array(
        'name' => mysql_clean($subarea),
        'area' => $area_id,
      );
      $db->insert("data_subareas",$post);
      $subarea_id = $db->lastInsertId();
    }
    $check = $db->record("SELECT * FROM data_indicators WHERE name = '".mysql_clean($indicator)."' AND subarea = $subarea_id");
    if ($check->id) {
      $indicator_id = $check->id;
    } else {
      $post = array(
        'name' => mysql_clean($indicator),
        'subarea' => $subarea_id,
      );
      $db->insert("data_indicators",$post);
      $indicator_id = $db->lastInsertId();
    }
  }
}
