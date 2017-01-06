<?php
require_once 'functions.php';

$file = $_GET['file'] ? '/s/epr/docs/reports.xml' : '/s/epr/docs/papers.xml';

$xmlstring = file_get_contents($file);

$xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
$json = json_encode($xml);
$array = json_decode($json,TRUE);

$info = $array['records']['record'];

foreach ($info as $row) {

  $post['title'] = $row['titles']['title']['style'];
  if ($row['titles']['secondary-title']['style']) {
    $source = html($row['titles']['secondary-title']['style']);
  } else {
    $source = html($row['publisher']['style']);
  }
  $check = $db->record("SELECT * FROM sources WHERE name = '$source'");
  $source_id = $check->id;
  if (!$source_id) {
    $db->insert("sources",array('name' => $source));
    $last = $db->record("SELECT MAX(id) AS id FROM sources");
    $source_id = $last->id;
  }
  if ($row['notes']['style']) {
    $post['editor_comments'] = $row['notes']['style'];
  }
  $post['source'] = $source_id;
  $post['author'] = $row['contributors']['authors']['author']['style'];
  $post['pages'] = $row['pages']['style'];
  $post['year'] = $row['dates']['year']['style'];
  $post['abstract'] = $row['abstract']['style'];
  $post['doi'] = $row['electronic-resource-num']['style'];
  $post['issue'] = $row['number']['style'];
  $post['link'] = $row['urls']['related-urls']['url']['style'];
  $post['volume'] = $row['volume']['style'];

  $db->insert("papers",$post);
  $last = $db->record("SELECT MAX(id) AS id FROM papers");
  $id = $last->id;

  $authors = nameScraper($post['author']);
  if (is_array($authors)) {
    foreach ($authors as $author_id) {
      $post = array(
        'people' => $author_id,
        'paper' => $id,
      );
      $db->insert("people_papers",$post);
    }
  }

  $keywords = $row['keywords']['keyword'];
  if (is_array($keywords)) {
    foreach ($keywords as $key => $value) {
      if (isset($value['style'])) {
        $keyword = $value['style'];
      } else {
        $keyword = $value;
      }

      $rewrite = array(
        "waste electrical and electronic equipment (WEEE)" => "WEEE",
        "waste eletrical and electronic equipment (WEEE)" => "WEEE",
        "waste electric and electronic equipment (WEEE)" => "WEEE",
        "waste electronic and electrical equipment (WEEE)" => "WEEE",
        "waste electrical and electronicequipment (WEEE)" => "WEEE",
        "life cycle assessment (LCA)" => "life-cycle assessment (LCA)",
        "life cycle" => "life-cycle",
        "life-cycle assessment" => "life-cycle assessment (LCA)",
        "Dutch" => "Netherlands",
      );

      if ($rewrite[$keyword]) {
        $keyword = $rewrite[$keyword];
      }

      $phrases = array(
        "WEEE" => 3,
        "end-of" => 4,
        "end of" => 4,
        "environment" => 6,
        "life cycle" => 5,
        "life-cycle" => 5,
      );
      
      $countries = array(
        'Alberta' => 2,
        'Asia' => 2,
        'Australia' => 2,
        'Belgium' => 2,
        'Brazil' => 2,
        'British Columbia' => 2,
        'California' => 2,
        'Canada' => 2,
        'Chile' => 2,
        'China' => 2,
        'Dutch' => 2,
        'East Asia' => 2,
        'Europe' => 2,
        'European Union' => 2,
        'Finland' => 2,
        'France' => 2,
        'Germany' => 2,
        'Global issues' => 2,
        'Hawaii' => 2,
        'India' => 2,
        'Japan' => 2,
        'Korea' => 2,
        'Maine' => 2,
        'Malaysia' => 2,
        'Mexico' => 2,
        'Minnesota' => 2,
        'Netherlands' => 2,
        'New York' => 2,
        'North America' => 2,
        'OECD' => 2,
        'Ontario' => 2,
        'Philippines' => 2,
        'Portugal' => 2,
        'Rhode Island' => 2,
        'Slovakia' => 2,
        'South Africa' => 2,
        'South Korea' => 2,
        'Spain' => 2,
        'Sri Lanka' => 2,
        'Sweden' => 2,
        'Taiwan' => 2,
        'Thailand' => 2,
        'United Kingdom' => 2,
        'United Nations' => 2,
        'United States' => 2,
      );

      $parent = 1;

      foreach ($phrases as $key => $value) {
        $position = strpos($keyword, $key);
        if ($position > -1) {
          $parent = $value;
        }
      }

      if ($countries[$keyword]) {
        $parent = 2;
      }

      $check = $db->record("SELECT * FROM tags WHERE tag = '" . addslashes($keyword) . "'");
      if ($check->id) {
        $tag = $check->id;
      } else {
        $db->insert("tags",array('tag' => $keyword, 'parent' => $parent));
        $last = $db->record("SELECT MAX(id) AS id FROM tags");
        $tag = $last->id;
      }
      $post = array(
        'tag' => $tag,
        'paper' => $id,
      );
      $db->insert("tags_papers",$post);
    }
  }

}
?>
