<?php
require_once '../vendor/autoload.php';
require_once '../functions.php';

use Abraham\TwitterOAuth\TwitterOAuth;

$info = $db->record("SELECT * FROM datavisualizations WHERE date <= CURDATE() ORDER BY date DESC LIMIT 1");

if (!$info->id) {
    mailadmins("AutoAlert: No automated tweet sent out because no dataviz was found for today. Let's make sure there is one for tomorrow!\n\nCheers,\nAutoBot");
    die();
}

if (LOCAL) {

  echo "We do not send tweets from the development environment";
  
} else {

  if ($info->paper) {

    $paperinfo = $db->record("SELECT * FROM papers WHERE id = " . $info->paper);

    $people = $db->query("SELECT people.* 
    FROM people_papers 
      JOIN people ON people_papers.people = people.id
    WHERE people_papers.paper = {$info->paper}");

    foreach ($people as $row) {

      if ($row['email']) {

        $id = $row['id'];

        $mailinfo = $db->record("SELECT * FROM mails WHERE id = 3");

        $paperlist = $db->query("SELECT 
          papers.*
        FROM people_papers
          JOIN papers ON people_papers.paper = papers.id
        WHERE people_papers.people = $id
        ORDER BY papers.year DESC");

        $count = 0;
        $papers = '';
        foreach ($paperlist as $subrow) {
          $count++;
          $papers .= $count . ". [" . URL . "publication/{$subrow['id']} " . html_entity_decode($subrow['title'], ENT_QUOTES) . "] ({$subrow['year']})\n";
        }

        $content = $mailinfo->content;

        $accessinfo = $db->record("SELECT * FROM people_access WHERE people = $id ORDER BY id DESC LIMIT 1");

        if (!$accessinfo->id) {
          $post = array(
            'people' => $id,
            'email' => mysql_clean($row['email']),
            'ip' => mysql_clean($_SERVER["REMOTE_ADDR"]),
            'details' => mysql_clean(getinfo()),
          );
          $db->insert("people_access",$post);
          $accessinfo = $db->record("SELECT * FROM people_access WHERE people = $id ORDER BY id DESC LIMIT 1");
        }

        $access_id = $accessinfo->id;
        $link = URL . "access/$access_id/" . encrypt("PROFILE $access_id");
        $link_data = URL . "access-data/$access_id/" . encrypt("PROFILE $access_id");
        $link_casestudies = URL . "preview/casestudies";

        $replace = array(
          'NAME' => $row['firstname'] . " " . $row['lastname'],
          'PUBLICATION_LIST' => $papers,
          'PUBLICATION_TOTAL' => $count,
          'UPLOAD_STUDIES' => "[" . $link_data . " Upload data from studies you have undertaken]",
          'REVIEW_DATA' => "[" . $link_casestudies . " Review existing data]",
          'DASHBOARD_LINK' => "[" . $link . " Open your personal dashboard]",
          'JOIN_STAKEHOLDERS' => "[" . URL . "page/stakeholders Join the Stakeholders Initiative]",
          'DATA_VIZ_LINK' => "[" . URL . "datavisualizations/{$info->id}-" . flatten($info->title) . " " . $info->title ."]",
          'PUBLICATION_LINK' => "[" . URL . "publication/{$paperinfo->id} " . $paperinfo->title ."]",
        );

        $content = strtr($content, $replace);

        require_once '../functions.mail.php';

        pearMail($row['email'], $mailinfo->subject, $content);
        $post = array(
          'people' => $row['id'],
          'mail' => 3,
          'address' => mysql_clean($row['email']),
          'content' => mysql_clean($content),
        );
        $db->insert("people_mails",$post);
        $print = "Mail was sent to {$row['email']}";
      
      }
    }
  }

  function getConnectionWithAccessToken($oauth_token, $oauth_token_secret) {
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
    return $connection;
  }
 
  $connection = getConnectionWithAccessToken($access_token, $access_token_secret);

  $file = "../media/dataviz/".$info->id.".jpg";
  $image = $connection->upload('media/upload', ['media' => $file]);

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
  Media: " . print_r($image, true) . "
  Response: " . print_r($status, true);

    echo $message;
    mailadmins($message, "Tweet failed", false, false, true);

  }

}

?>
