<?php
require_once '../functions.php';
$count = 0;

$file = "data.tsv";
$data = file_get_contents($file);
$explode = explode("\n", $data);
foreach ($explode as $value) {
  $sub = explode("\t", $value);
  $paper = $sub[1];
  $doi = $sub[0];
  $city = $sub[2];
  $area = $sub[3];
  $subarea = $sub[4];
  $year = $sub[5];
  $year_end = $sub[6];
  $indicator = $sub[7];
  $data_value = $sub[8];
  $unit = $sub[9];
  $type = $sub[10];
  $notes = $sub[11];
  $replace = array("\r" => "");
  $notes = strtr($notes, $replace);
  $count++;

  if ($area == 'Material') {
    $area = 'Materials';
  }
  if (!$subarea) {
    $subarea = "General";
  }
  $convert = array(
    'wastewaterter production' => 'Wastewater production',
    'others' => 'other',
    'toatl' => 'total',
    'buliding materials' => 'building materials',
  );
  if ($convert[$subarea]) {
    $subarea = $convert[$subarea];
  }
  if ($count > 1 && $area) {

    if (!$doi) {
      $doi = "No DOI was set";
    }
    $paper_id = $paper_titles[$paper];
    if (!$paper_id) {
    $check = $db->record("SELECT * FROM papers WHERE title = '".mysql_clean($paper)."' OR doi = '" . mysql_clean($doi)."'");
      if (!$check->id) {
        die("No paper was found. Searched for doi = $doi and title = <strong>$title</strong>, but no matches were found");
      } else {
        $paper_titles[$paper] = $check->id;
        $paper_id = $check->id;
      }
    }

    $case_id = $case_studies_id[$paper_id][$city];
    if (!$case_id) {
      $check = $db->record("SELECT * FROM case_studies WHERE paper = $paper_id AND name = '" . mysql_clean($city)."'");
      $case_id = $check->id;
      
      if (!$check->id) {
        $post = array(
          'name' => mysql_clean($city),
          'paper' => $paper_id,
        );
        $db->insert("case_studies",$post);
        $case_id = $db->lastInsertId();
        $case_studies_id[$paper_id][$city] = $case_id;
      }
    }

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
    $post = array(
      'case_study' => $case_id,
      'indicator' => $indicator_id,
      'year' => $year,
      'year_end' => $year_end ?: NULL,
      'unit' => $unit,
      'notes' => mysql_clean($notes),
      'value' => $data_value,
      'type' => $type == "total" ? "total" : "per_capita",
    );
    $db->insert("data",$post);
  }
}
