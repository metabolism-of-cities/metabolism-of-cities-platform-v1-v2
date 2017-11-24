<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';

$media = (int)$_POST['media'];

$check = $db->record("SELECT * FROM mooc_progress WHERE media = $media AND user = $user_id LIMIT 1");

$post = array(
  'user' => $user_id,
  'media' => $media,
);

if (!$check->id) {
  $db->insert("mooc_progress",$post);
}

$response['response'] = 'OK';
echo json_encode($response);
