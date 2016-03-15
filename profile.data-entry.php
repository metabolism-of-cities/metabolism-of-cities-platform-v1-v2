<?php
require_once 'functions.php';
require_once 'functions.profile.php';

$sub_page = 4;
$info = $profile_info;

if ($_POST['name']) {

  $post = array(
    'name' => html($_POST['name']),
    'paper' => (int)$_GET['paper'],
  );
  $db->insert("case_studies",$post);
  $id = $db->lastInsertId();
  header("Location: " . URL . "profile/{$profile_id}/case/$id");
  exit();
  
} elseif ($_POST['title']) {
  $post = array(
    'title' => html($_POST['title']),
    'author' => html($_POST['author']),
    'year' => (int)$_POST['year'],
    'link' => html($_POST['link']),
    'status' => mysql_clean('pending_data'),
    'date_added' => mysql_clean(date("Y-m-d H:i:s")),
  );
  $id = $db->insert("papers",$post);
  $id = $db->lastInsertId();

  $hash = encrypt($id . html($_POST['title'], false));

  $message = 
"Name: {$profile_info->firstname} {$profile_info->lastname}
E-mail: {$profile_info->access_email}
Date: " . date("r") . "
IP: " . $_SERVER["REMOTE_ADDR"] . "

-----------------------------------------
          PUBLICATION ADDED
-----------------------------------------
Title: " . mail_clean($_POST['title']) . "
Author: " . mail_clean($_POST['author']) . "
Journal: " . mail_clean($_POST['source']) . "
Paper ID: $id
Link: " . URL . "publication/$id
Review: " . URL . "publication.view.php?id=$id&hash=$hash

-----------------------------------------
                MESSAGE
-----------------------------------------

This publication was added in order to enter data.

-----------------------------------------
            TECH DETAILS
-----------------------------------------
" . getinfo();

  mailadmins($message, "New publication at the Metabolism of Cities website", $profile_info->access_email);
  $post = array(
    'paper' => $id,
    'people' => $people_id,
  );
  $db->insert("people_papers",$post);

  peoplelog("User added a new publication");

  header("Location: " . URL . "profile/{$profile_id}/data-entry/$id");
  exit();
}

$paper = (int)$_GET['paper'];

if ($paper) {
  $info = $db->record("SELECT 
    papers.*
  FROM people_papers
    JOIN papers ON people_papers.paper = papers.id
  WHERE people_papers.people = $people_id AND papers.id = $paper");
  if (!$info->id) {
    kill("No publications found", "critical");
  }
  $case_studies = $db->query("SELECT * FROM case_studies WHERE paper = $paper ORDER BY name");
} else {
  $papers = $db->query("SELECT 
    papers.*
  FROM people_papers
    JOIN papers ON people_papers.paper = papers.id
  WHERE people_papers.people = $people_id
  ORDER BY papers.year DESC");
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Data Entry | <?php echo SITENAME ?></title>
    <style type="text/css">
    .fa-info-circle{float:left;margin-right:10px;font-size:40px}
    .jumbotron{margin-bottom:50px}
    </style>
  </head>

  <body class="profile">

<?php require_once 'include.header.php'; ?>

  <h1>Data Entry</h1>

  <?php if ($paper) { ?>

    <p><strong>Publication: </strong><?php echo $info->title ?></p>

    <?php if (count($case_studies)) { ?>
      <p>Please select below the city/area your data applies to:</p>
      <ul>
      <?php foreach ($case_studies as $row) { ?>
        <li><a href="profile/<?php echo $profile_id ?>/case/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></li>
      <?php } ?>
      </ul>
      <h3>Add another city that is part of this study</h3>
    <?php } else { ?>
      <p>Please enter the name of the city/area your data applies to:</p>
    <?php } ?>

    <form method="post" class="form form-horizontal">

      <div class="form-group">
        <label class="col-sm-2 control-label">City name</label>
        <div class="col-sm-10">
          <input class="form-control" type="text" name="name" placeholder="E.g. New York City" />
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </div>
    
    </form>

  <?php } else { ?>    

  <div class="alert alert-info">
    Please select the publication your data comes from.
  </div>

  <ul class="nav nav-list nav-stacked">
  <?php foreach ($papers as $row) { ?>
    <li><a href="profile/<?php echo $profile_id ?>/data-entry/<?php echo $row['id'] ?>"><?php echo $row['title'] ?></a></li>
  <?php } ?>
  </ul>

  <h3>Add a new source publication</h3>

  <form method="post" class="form form-horizontal">

    <div class="form-group">
      <label class="col-sm-2 control-label">Title</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="title" value="<?php echo $info->title ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Year</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="year" value="<?php echo $info->year ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Journal/source</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="source" value="<?php echo $info->source ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Author(s)</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="author" value="<?php echo $info->author ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">URL</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="link" value="<?php echo $info->link ?>" />
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
  
  </form>


  <?php } ?>
  

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
