<?php
require_once 'functions.php';

// set all to journal article
$db->query("UPDATE papers SET type = 16");

$array = array(
105 => 5, // book
181 => 6, // book chapter
94 => 27, // report
131 => 29, // thesis
176 => 29, // thesis
);

foreach ($array as $id => $new) {
  $list = $db->query("SELECT * FROM tags_papers WHERE tag = $id");
  foreach ($list as $row) {
    $db->query("UPDATE papers SET type = $new WHERE id = {$row['paper']}");
  }
}

