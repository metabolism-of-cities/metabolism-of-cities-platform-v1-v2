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
  tags.tag, papers.title, papers.author, papers.id, tags.id AS tag_id, papers.year
FROM tags_papers 
  LEFT JOIN tags ON tags_papers.tag = tags.id
  LEFT JOIN papers ON tags_papers.paper = papers.id
WHERE tags.parent = 4 AND papers.status = 'active'");

$csv[] = array("Publication(s)", "City", "Author(s)", "URL", "Year", "Quantity");

foreach ($list as $row) {
  // First we do a first loop to group all the same city studies
  // in one array, so we can see if a city has 1 study or > 1 studies
  $city = strtr($row['tag'], $remove);
  $cities[$city][] = array(
    'title' => html_entity_decode($row['title'], ENT_QUOTES), 
    'author' => $row['author'], 
    'link' => URL . "publication/" . $row['id'], 
    'tag' => $row['tag_id'],
    'year' => $row['year'],
  );
}

foreach ($cities as $city => $studies) {

  if (count($studies) > 1) {
    // There are multiple studies, so we will have to group them onto one line. This is 
    // done because CartoDB does not (easily) support multiple dots in one place. 
    // See: https://gis.stackexchange.com/questions/91983
    $csv[$city][0] = "<ul>";
    $csv[$city][1] = $city;

    // The author field will remain empty
    $csv[$city][2] = false;

    foreach ($studies as $row) {

      // Instead of featuring one article title, the title field will contain a list with all publication titles
      $csv[$city][0] .= "<li><a href='{$row['link']}'>{$row['title']}</a> ({$row['year']})</li>";

      // The link will point to the overview with all studies from this city
      $csv[$city][3] = URL . "tags/{$row['tag']}/" . flatten($city);
    }

    $csv[$city][4] = false; // Don't set a year as a separate field
    $csv[$city][5] = count($studies);
    $csv[$city][0] .= "</ul>";

  } else {
    $csv[$city] = array(
      $studies[0]['title'],
      $city,
      $studies[0]['author'],
      $studies[0]['link'],
      $studies[0]['year'],
      1, // The quantity of studies for this city
    );
  }

}

outputCSV($csv);

?>
