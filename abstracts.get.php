<?php
require_once 'functions.php';
$section = 2;
$page = 2;

$list = $db->query("SELECT * FROM papers WHERE abstract_full = '' ORDER BY year");


foreach ($list as $row) {
  $link = 'http://dx.doi.org/'.$row['doi'];
  $data = file_get_contents($link);
  $post = array(
    'abstract_full' => mysql_clean($data),
  );
  $db->update("papers",$post,"id = {$row['id']}");
  sleep(rand(2,10));
}
?>
