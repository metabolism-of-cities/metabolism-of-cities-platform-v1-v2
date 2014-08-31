<?php
require_once 'functions.php';
require_once 'functions.omat.php';

$id = (int)$_GET['id'];

$file = $db->record("SELECT * FROM mfa_files WHERE id = $id AND dataset = $project");

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
