<?php
require_once 'functions.php';

$id = (int)$_POST['id'];
$paper = (int)$_POST['paper'];

if ($_POST['action'] == "delete") {
  $db->query("DELETE FROM tags_papers WHERE tag = $id AND paper = $paper");
} elseif ($_POST['action'] == "add") {
  $post = array(
    'paper' => $paper,
    'tag' => $id,
  );
  $db->insert("tags_papers",$post);
}

$results['response'] = "OK";

echo json_encode($results);
?>
