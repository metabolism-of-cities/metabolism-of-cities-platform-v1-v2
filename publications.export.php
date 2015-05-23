<?php
require_once 'functions.php';

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=publications.csv");
header("Pragma: no-cache");
header("Expires: 0");

function outputCSV($data) {
  $output = fopen("php://output", "w");
  foreach ($data as $row) {
    fputcsv($output, $row);
  }
  fclose($output);
}

$list = $db->query("SELECT papers.*, sources.name AS journal
FROM papers 
JOIN sources ON papers.source = sources.id
WHERE papers.status = 'active' ORDER BY papers.title");

$tags = $db->query("SELECT tags.tag, tags_papers.paper 
FROM tags_papers
JOIN tags ON tags_papers.tag = tags.id");

foreach ($tags as $row) {
  $taglist[$row['paper']] .= $row['tag'] . ", ";
}

$csv[] = array("Title", "Author(s)", "Journal/source", "Volume", "Issue", "Pages", "Year", "DOI/ISBN", "Link", "Open Access", "Abstract", "Tags", "URL");

foreach ($list as $row) {

  $tags = $taglist[$row['id']] ? substr($taglist[$row['id']], 0, -2) : "";
  $openaccess = $row['open_access'] ? "yes" : "";
  $csv[] = array(
    html_entity_decode($row['title']),
    html_entity_decode($row['author']),
    html_entity_decode($row['journal']),
    $row['volume'],
    $row['issue'],
    html_entity_decode($row['pages']),
    $row['year'],
    $row['doi'],
    $row['link'],
    $openaccess,
    strip_tags(html_entity_decode(br2nl($row['abstract']))),
    $tags,
    URL . "publication/" . $row['id'],
  );

}

outputCSV($csv);

?>


