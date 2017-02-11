<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';

$id = (int)$_POST['id'];

if ($_POST['action'] == 'delete') {
  $db->query("DELETE FROM tags WHERE id = $id");
  $response['response'] = 'OK';
}

if ($_POST['action'] == "update") {
  $tag = html($_POST['name']);
  $check = $db->record("SELECT * FROM tags WHERE tag = '$tag' AND id != $id");
  if ($check->id) {
    $response['error'] = 'This tag already exists';
  } else {
    $post = array(
      'tag' => html($_POST['name']),
    );
    $db->update("tags",$post,"id = $id");
    $response['response'] = 'OK';
    $response['time'] = date("r");
  }
}

if ($_POST['action'] == "updateparent") {
  $post = array(
    'parent' => (int)$_POST['parent'],
  );
  $db->update("tags",$post,"id = $id");
  $response['response'] = 'OK';
  $response['time'] = date("r");
}

if ($_POST['action'] == "merge") {
  $old = (int)$_POST['oldtag'];
  $new = (int)$_POST['newtag'];
  $db->query("UPDATE tags_papers SET tag = $new WHERE tag = $old");
  $db->query("DELETE FROM tags WHERE id = $old");
  $info = $db->record("SELECT * FROM tags WHERE id = $new");
  $response['response'] = 'OK';
  $response['newtag'] = $info->tag;
}

if ($_POST['action'] == "synctags") {
  $tags = $_POST['tags'];
  $db->query("DELETE FROM tags_papers WHERE paper = $id");
  $post['paper'] = $id;
  $count = 0;
  if (is_array($tags)) {
  foreach ($tags as $tag) {
      $post['tag'] = $tag;
      $db->insert("tags_papers",$post);
      $count++;
    }
  }
  $response['response'] = 'OK';
  $response['count'] = $count;
  $response['time'] = date("r");
}

if (!$response['response']) {
  $response['response'] = 'error';
}

echo json_encode($response);
