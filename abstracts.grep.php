<?php
require_once 'functions.php';

$list = $db->query("SELECT * FROM papers WHERE abstract = ''");


foreach ($list as $row) {
  $abstract = $row['abstract_full'];

  $pattern = '/id="abstractKeywords1">.+?<\/ul>/';
  preg_match ($pattern, $abstract, $keywords, PREG_OFFSET_CAPTURE);
  $keywords = $keywords[0][0];
  $keywords = substr($keywords, strlen('id="abstractKeywords1">'));
  $keywords = substr($keywords, 0, -5);

  $pattern = '/<h3>Summary<\/h3>.+?<div xmlns/';
  preg_match ($pattern, $abstract, $abs, PREG_OFFSET_CAPTURE);

  $abs = $abs[0][0];
  $abs = strip_tags($abs, '<p>');
  $abs = substr($abs, strlen('Summary'));

  $post = array(
    'keywords' => mysql_clean($keywords),
    'abstract' => mysql_clean($abs),
  );
  $db->update("papers",$post,"id = {$row['id']}");
}
?>
