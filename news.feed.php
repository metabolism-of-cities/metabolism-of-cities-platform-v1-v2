<?php
$cron = true;
require_once __DIR__.'/functions.php';
$url = "http://onlinelibrary.wiley.com/rss/journal/10.1111/(ISSN)1530-9290";
$xml = file_get_contents($url);
$xml = new SimpleXMLElement($xml);

$message = "The following news articles were imported into the EPR site:\n\n";

$replace = array(
  "\'" => "'",
  '\"' => '"',
);

foreach ($xml->channel->item as $key => $row) {
  $dc = $row->children('http://purl.org/dc/elements/1.1/');
  $link = (string)$row->link;
  $title = strtr(trim((string)$row->title), $replace);
  $content = strtr((string)$row->description, $replace);
  $date = (string)$row->pubDate;
  
  $check = $db->record("SELECT * FROM content WHERE external_link = '$link' AND type = 'news'");
  if (!$check->id && $title) {
    $post = array(
      'title' => $title,
      'date' => mysql_clean(format_date("Y-m-d", $date)),
      'content' => $content,
      'active' => 1,
      'type' => mysql_clean('news'),
      'slug' => flatten($title),
      'external_link' => mysql_clean($link),
    );
    $db->insert("content",$post);
    $id = $db->insert_id;

    $message .= "*{$title}*\n{$date}\n[$link {$link}]\nAbstract:\n{$content}\n\n";
    $send_mail = true;
  }
}
echo $message;
