<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../functions.php';

use Abraham\TwitterOAuth\TwitterOAuth;

function getConnectionWithAccessToken($oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
  return $connection;
}
 

function tweet($tweet, $url) {

  global $access_token, $access_token_secret;

  $connection = getConnectionWithAccessToken($access_token, $access_token_secret);

  if (strlen($tweet) > 115) {
    $tweet = substr($tweet, 0, 112) . "...";
  }

  $tweet .= " - ". $url;

  if (LOCAL) {
    return false;
  }
  $status = $connection->post("statuses/update", ["status" => $tweet]);

  if ($connection->getLastHttpCode() == 200) {
      // Tweet posted succesfully
    return "Tweet was posted";
  } else {
    $message = "The tweet failed. See more details below:

Tweet: $tweet
Response: " . print_r($status, true);

    return nl2br($message);
    mailadmins($message, "Tweet failed", false, false, true);

  }

}
?>
