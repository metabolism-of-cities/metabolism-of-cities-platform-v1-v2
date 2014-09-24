<?php
require_once 'functions.php';
require_once 'functions.omat.php';
require_once 'eurostat.php';

if (PRODUCTION) {
  die("Run this locally and then import the data");
}

$remove = array("," => "");

// This script can be used to convert CSV values from Eurostat to OMAT projects
//
// Step one is to load all of the Eurostat values into an external file (e.g. eurostat.php),
// and to make an array there with the values, e.g.:
// $data[] = array('2000', 'Thousand tonnes', 'France', etc...
//
// Next, these values are loaded into a temporary MySQL table. This is done to make it easier
// to import this in batches, which is especially useful if this is a LOT of data (first import
// included 200,000+ of records...
// 
// To import into MySQL, make sure the $data array matches the order as defined below (or simply
// change the variables. Then run the script with $_GET import_into_mysql = true

if ($_GET['import_into_mysql']) {
  foreach ($data as $key => $value) {
    $id = $key + 1;
    $year = $value[0];
    $country = $value[1];
    $measure = $value[2];
    $type = $value[3];
    $material = $value[4];
    $amount = $value[5];
    $comments = $value[6];
    
    $amount = strtr($amount, $remove);
    if ($measure != "Thousand tonnes") {
      echo "Not a thousand tons!";
      die(var_dump($value));
    }

    $post = array(
      'id' => (int)$id,
      'year' => $year,
      'country' => mysql_clean($country),
      'type' => mysql_clean($type),
      'material' => mysql_clean($material),
      'value' => (float)$amount,
      'comments' => mysql_clean($comments),
    );
    $db->insert("import",$post);
    $counter++;
  }
}

// Step two is to indicate for all of the materials to which record they belong
// in the mfa_materials table. Please note that Eurostat reports ALL values, so say
// group 1.1 is Biomass and 1.1.1 is Carrots, then they will provide the value for both.
// However, in OMAT we only want 1.1.1. and the system will calculate 1.1. So we need to 
// only import the actual materials and not the headers. To figure out which one is a header,
// the subquery runs to check underlying categories. If subcategories = 0, then there are
// no subcategories for this particular record so then this is a material and not a header.

if ($_GET['mark_materials']) {
  $list = $db->query("SELECT 
  mfa_materials.*, 
  mfa_groups.name AS groupname,
  (SELECT COUNT(*) FROM mfa_materials m WHERE m.code LIKE CONCAT(mfa_materials.code, '.%')) AS subcategories 
  FROM mfa_materials
  JOIN mfa_groups ON mfa_materials.mfa_group = mfa_groups.id
  WHERE mfa_groups.dataset IS NULL
  ");
  $remove = array(
    ", raw and processed" => "",
    " and biomass products" => "",
    );
  foreach ($list as $row) {
    if ($row['name'] == "Timber, raw and processed") {
      $row['name'] = "Timber (industrial roundwood)";
    }
    if ($row['name'] == "Wood and wood products") {
      $row['name'] = "Wood";
    }
    if ($row['name'] == "Live animals other than in 1.4., and animal products") {
      $row['name'] = "Live animals other than in 1.4, and animal products";
    }
    if ($row['name'] == "Live animals other than in 1.4.") {
      $row['name'] = "Live animals other than in 1.4";
    }
    $row['name'] = strtr($row['name'], $remove);
    $should_import = $row['subcategories'] ? 0 : 1;
    $db->query("UPDATE import SET mfa_material = {$row['id']}, should_import = $should_import 
    WHERE material = '{$row['groupname']}' AND type = '{$row['name']}' AND mfa_material IS NULL");
  }
}



if ($_GET['country']) {
  $country = $_GET['country'];
  $check = $db->record("SELECT * FROM mfa_dataset WHERE name = '$country'");
  if (!count($check)) {
    $post = array(
      'name' => mysql_clean($country),
      'year_start' => 2000,
      'year_end' => 2012,
      'access' => 'public',
      'decimal_precision' => 0,
      'type' => 1,
      'banner_text' => 'This project was created using data made available by EUROSTAT. See: <a href="http://appsso.eurostat.ec.europa.eu/nui/show.do?query=BOOKMARK_DS-075779_QID_62115716_UID_-3F171EB0&layout=TIME,C,X,0;GEO,L,Y,0;UNIT,L,Z,0;MATERIAL,L,Z,1;INDIC_NV,L,Z,2;INDICATORS,C,Z,3;&zSelection=DS-075779INDIC_NV,DMC;DS-075779MATERIAL,TOTAL;DS-075779INDICATORS,OBS_FLAG;DS-075779UNIT,THS_T;&rankName1=MATERIAL_1_2_-1_2&rankName2=INDIC-NV_1_2_-1_2&rankName3=INDICATORS_1_2_-1_2&rankName4=UNIT_1_2_-1_2&rankName5=TIME_1_0_0_0&rankName6=GEO_1_2_0_1&sortC=ASC_-1_FIRST&rStp=&cStp=&rDCh=&cDCh=&rDM=true&cDM=true&footnes=false&empty=false&wai=false&time_mode=NONE&time_most_recent=false&lang=EN&cfo=%23%23%23%2C%23%23%23.%23%23%23">EUROSTAT dataset</a>.',
    );
    $db->insert("mfa_dataset",$post);
    $project = $db->insert_id;
  } else {
    $project = $check->id;
  }

  $list = $db->query("SELECT * FROM import WHERE country = '$country' AND year = 2000 AND import_status = 0");

}

?>
