<?php
require_once '../vendor/autoload.php';
require_once '../functions.php';

use Abraham\TwitterOAuth\TwitterOAuth;

function getConnectionWithAccessToken($oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
  return $connection;
}
 
$connection = getConnectionWithAccessToken($access_token, $access_token_secret);

$info = $db->record("SELECT * FROM datavisualizations WHERE date <= CURDATE() ORDER BY date DESC LIMIT 1");
$tweet = "Today's data visualization: " . $info->title;

if (strlen($tweet) > 110) {
  $tweet = substr($tweet, 0, 107) . "...";
}

$tweet .= " - " . URL . "datavisualizations/" . $info->id . "-" . flatten($info->title);

$status = $connection->post("statuses/update", ["status" => $tweet]);

if ($connection->getLastHttpCode() == 200) {
    // Tweet posted succesfully
  echo "Tweet was posted";
} else {
  $message = "Automated tweet failed. Details below:

Tweet: $tweet
Response: " . print_r($status, true);

  echo $message;
  mailadmins($message, "Tweet failed", false, false, true);

}

?>
