<?php
$project = 12;
require_once 'functions.php';
require_once 'functions.omat.php';

if (PRODUCTION) {
  die("Run this locally and then import the data");
}

$remove = array("," => "");
$data = file_get_contents('iceland.csv');
$row = 1;

$match = array(
  'A' => 92,
  'B' => 93,
  'D' => 94,
);

if (($handle = fopen("iceland.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
    $row++;
    $title = false;
    $year = 1957;
    for ($c=0; $c < $num; $c++) {
      $information = $data[$c];
      $year++;
      if (!$title) {
        $title = $information;
        $letter = substr($title, 0, 1);
        $space_position = strpos($title, " ");
        $code = substr($title, 2, $space_position-3);
        $name = substr($title, 1+$space_position);
        $group = $match[$letter];
        $post = array(
          'mfa_group' => $group,
          'name' => mysql_clean($name),
          'code' => mysql_clean($code),
        );
        $db->insert("mfa_materials",$post);
        $id = $db->lastInsertId();
      } else {
        if ($year >= 1960) {
          $information = strtr($information, $remove);
          $value = (float)$information;
          $post = array(
            'material' => $id,
            'year' => $year,
            'data' => $value,
          );
          $db->insert("mfa_data",$post);
        }
      }
    }
  }
  fclose($handle);
}

?>
