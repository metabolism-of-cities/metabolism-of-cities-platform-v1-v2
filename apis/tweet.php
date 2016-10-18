<?php
require_once '../vendor/autoload.php';
require_once '../functions.php';

use Abraham\TwitterOAuth\TwitterOAuth;

function getConnectionWithAccessToken($oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
  return $connection;
}
 
$connection = getConnectionWithAccessToken($access_token, $access_token_secret);
$file = "../media/dataviz/".$info->id.".jpg";
$image = $connection->upload('media/upload', ['media' => $file]);

$info = $db->record("SELECT * FROM datavisualizations WHERE date <= CURDATE() ORDER BY date DESC LIMIT 1");

if (!$info->id) {
    mailadmins("AutoAlert: No automated tweet sent out because no dataviz was found for today. Let's make sure there is one for tomorrow!\nCheers,\nAutoBot");
    die();
}

$tweet = "Today's data visualization: " . $info->title;

if (strlen($tweet) > 85) {
  $tweet = substr($tweet, 0, 82) . "...";
}

$tweet .= " - " . URL . "datavisualizations/" . $info->id . "-" . flatten($info->title);

$parameters = [
    'status' => $tweet,
    'media_ids' => implode(',', [$image->media_id_string])
];
$status = $connection->post("statuses/update", $parameters);

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
