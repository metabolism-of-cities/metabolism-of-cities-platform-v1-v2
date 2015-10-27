<?php
if ($_GET['public_login'] || $_GET['type'] == 'omat-public') {
  $public_login = true;
}
require_once 'functions.php';
require_once 'functions.omat.php';

$id = (int)$_GET['id'];

$file = $db->record("SELECT * FROM mfa_files WHERE id = $id AND dataset = $project");

if ($public_login) {
  // For public login, we want to confirm that this file is indeed part of a
  // source that is listed as a data source. 
  $info = $db->query("SELECT access FROM mfa_dataset WHERE id = $project");
  if ($info->access == 'private') {
    die("File not found");
  }
  $source = $file->source;
  $check_data = $db->query("SELECT * FROM mfa_data WHERE source_id = $source");
  if (!count($check_data)) {
    die("File not found");
  }
}

if ($file->original_name) {

  $path = UPLOAD_PATH . "$project.{$file->source}.$id";
  header('Content-Type: ' . $file->type . '; charset=UTF-8');
  header('Content-Disposition: attachment; filename="'.$file->original_name.'"');
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Content-Length: ' . filesize($path));
  readfile($path);
} else {
  die("File not found");
}

?>
