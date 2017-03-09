<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';

$id = (int)$_GET['id'];
$mail_id = 5;

if ($id) {
  $info = $db->record("SELECT * FROM people WHERE id = $id");
}

if (!$info->email) {
  die("Please set an e-mail address first!");
}

$mailinfo = $db->record("SELECT * FROM mails WHERE id = $mail_id");

$paperlist = $db->query("SELECT 
  papers.*
FROM people_papers
  JOIN papers ON people_papers.paper = papers.id
WHERE people_papers.people = $id
ORDER BY papers.year DESC");

$count = 0;
foreach ($paperlist as $row) {
  $count++;
  $papers .= $count . ". [" . URL . "publication/{$row['id']} " . html_entity_decode($row['title'], ENT_QUOTES) . "] ({$row['year']})\n";
}

$content = $mailinfo->content;

$accessinfo = $db->record("SELECT * FROM people_access WHERE people = $id ORDER BY id DESC LIMIT 1");

if (!$accessinfo->id) {
  $post = array(
    'people' => $id,
    'email' => mysql_clean($info->email),
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
$papers_total = $db->record("SELECT SQL_CACHE COUNT(*) AS total FROM papers WHERE status = 'active'");

$replace = array(
  'NAME' => $info->firstname . " " . $info->lastname,
  'PUBLICATION_LIST' => $papers,
  'PUBLICATION_TOTAL' => $count,
  'UPLOAD_STUDIES' => "[" . $link_data . " Upload data from studies you have undertaken]",
  'REVIEW_DATA' => "[" . $link_casestudies . " Review existing data]",
  'DASHBOARD_LINK' => "[" . $link . " Open your personal dashboard]",
  'JOIN_STAKEHOLDERS' => "[" . URL . "page/stakeholders Join the Stakeholders Initiative]",
  'GRAND_TOTAL_PUBLICATIONS' => $papers_total->total,
);

$content = strtr($content, $replace);

$userinfo = $db->record("SELECT user_email FROM users WHERE user_id = $user_id");

$your_mail = $userinfo->user_email;

if ($_POST) {
  require_once 'functions.mail.php';
  pearMail($your_mail, $mailinfo->subject, $content);
  $print = "Mail was sent to $your_mail";
}

if ($_GET['send']) {
  require_once 'functions.mail.php';
  pearMail($info->email, $mailinfo->subject, $content);
  $post = array(
    'people' => $info->id,
    'mail' => $mail_id,
    'address' => mysql_clean($info->email),
    'content' => mysql_clean($content),
    'sent_by' => $user_id,
  );
  $db->insert("people_mails",$post);
  $print = "Mail was sent to {$info->email}";
}

$content = bbcode($content);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo $mailinfo->subject ?></title>
    <style type="text/css">
      div{margin:10px;padding:10px;border:1px solid #ccc;width:600px}
      body{font-family: Arial, sans-serif;}
      a{color: #0088cc}
      a:hover {color: #005580;text-decoration: none;}
      table{width:600px}
      td,th{padding:0px}
      body,html,table{margin:0;padding:0}
      table {
        border-collapse: collapse;
        border-spacing: 0;
      }
      body,td,tr{font-size:12px}
      h1{font-size:15px}
    
    </style>
  </head>

  <body>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <div>

    <?php echo $content ?>

  </div>

  <form method="post" class="form form-horizontal">

    <div class="form-group">
        <button type="submit" class="btn btn-primary" name="mail" value="true">Mail to <?php echo $your_mail ?></button>
    </div>

  </form>

  <form method="get" class="form form-horizontal" action="<?php echo URL ?>/cms/mail/<?php echo $id ?>/send">

    <div class="form-group">
        <button type="submit" class="btn btn-primary" name="mail" value="true">Mail to <?php echo $info->email ?></button>
    </div>
  
  </form>

  </body>
</html>
