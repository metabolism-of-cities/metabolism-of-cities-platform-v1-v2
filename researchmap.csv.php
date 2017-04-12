<?php
require_once 'functions.php';

/*

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=publications.csv");
header("Pragma: no-cache");
header("Expires: 0");
*/
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

$gps_tagged = ID == 2 ? 2 : 4;

$list = $db->query("SELECT 
  tags.tag, tags.id AS tag_id, tags.gps,
  research.title, research.researcher AS author, research.id, research.status
FROM tags_research 
  LEFT JOIN tags ON tags_research.tag = tags.id
  LEFT JOIN research ON tags_research.research = research.id
WHERE tags.parent = $gps_tagged AND research.deleted_on IS NULL
ORDER BY research.id DESC
");

$csv[] = array("City", "Author", "Research", "URL", "Tag ID", "Lat", "Long", "Quantity");

foreach ($list as $row) {
  // First we do a first loop to group all the same city studies
  // in one array, so we can see if a city has 1 study or > 1 studies
  $city = strtr($row['tag'], $remove);
  $cities[$city][] = array(
    'title' => html_entity_decode($row['title'], ENT_QUOTES), 
    'author' => $row['author'], 
    'link' => URL . "research/" . $row['id'], 
    'tag' => $row['tag_id'],
    'status' => $row['status'],
    'gps' => $row['gps'],
  );
}

foreach ($cities as $city => $studies) {

  // There are multiple studies, so we will have to group them onto one line. This is 
  // done because CartoDB does not (easily) support multiple dots in one place. 
  // See: https://gis.stackexchange.com/questions/91983
  $csv[$city][1] = $city;

  // At first we showed the author, but this string gets too long and with multiple publications
  // it's not practical. So leave it empty for now. 

  $csv[$city][2] = $row['author'];

  foreach ($studies as $row) {

    $explode = explode(",", $row['gps']);
    $lat = $explode[1];
    $long = $explode[0];

    // Instead of featuring one article title, the title field will contain a list with all publication titles
    $csv[$city][0] .= "· <a target='_parent' href='{$row['link']}'>{$row['title']}</a> ({$row['status']})<br />";

    // The link will point to the overview with all studies from this city
    $csv[$city][3] = URL . "tags/{$row['tag']}/" . flatten($city);
    $csv[$city][4] = $row['tag'];
    $csv[$city][5] = $lat;
    $csv[$city][6] = $long;
  }

  $csv[$city][7] = count($studies);

}

outputCSV($csv);

?>
