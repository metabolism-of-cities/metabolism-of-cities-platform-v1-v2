<?php
require_once 'functions.php';

$id = (int)$_GET['id'];
$file = $db->record("SELECT * FROM mooc_media WHERE id = $id");

if (!$file->id) {
  die("File not found");
}

$path = "media/files/mooc-{$file->id}.{$file->file_extension}";
header('Content-Type: ' . $file->type . '; charset=UTF-8');
header('Content-Disposition: attachment; filename="'.$file->title.'.'.$file->file_extension.'"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Content-Length: ' . filesize($path));
readfile($path);

?>
