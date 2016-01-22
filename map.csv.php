<?php
require_once 'functions.php';

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=publications.csv");
header("Pragma: no-cache");
header("Expires: 0");

// Some "cities" have ": City" after their name to distinguish them
// from the tag that refers to the country. This is the case with island-states 
// that are basically one big city. We need to take out the ": City" bit for
// this feed
$remove = array(": City" => "");

function outputCSV($data) {
  $output = fopen("php://output", "w");
  foreach ($data as $row) {
    fputcsv($output, $row);
  }
  fclose($output);
}

$list = $db->query("SELECT 
  tags.tag, papers.title, papers.author, papers.id
FROM tags_papers 
  LEFT JOIN tags ON tags_papers.tag = tags.id
  LEFT JOIN papers ON tags_papers.paper = papers.id
WHERE tags.parent = 4 AND papers.status = 'active'");

$csv[] = array("Publication", "City", "Author(s)", "URL");

foreach ($list as $row) {
  $csv[] = array(
    $row['title'],
    strtr($row['tag'], $remove), 
    $row['author'], 
    URL . "publication/" . $row['id']
  );
}

outputCSV($csv);

?>
