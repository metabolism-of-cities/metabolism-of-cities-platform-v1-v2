<?php
require_once 'functions.php';

$list = $db->query("SELECT * FROM papers WHERE status = 'active' ORDER BY id");

foreach ($list as $row) {
  $names = nameScraper($row['author']);
  if (is_array($names)) {
    foreach ($names as $id) {
      $post = array(
        'people' => $id,
        'paper' => $row['id'],
      );
      $db->insert("people_papers",$post);
    }
  }
}
