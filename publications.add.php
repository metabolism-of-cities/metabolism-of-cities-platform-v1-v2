<?php
require_once 'functions.php';
$section = 4;
$page = 3;

$journals = $db->query("SELECT * FROM sources ORDER BY name");

if ($_POST) {
  if ($_POST['source'] == "unlisted") {
    $post = array(
      'name' => html($_POST['newsource']),
    );
    $db->insert("sources",$post);
    $_POST['source'] = $db->lastInsertId();
  }

  $post = array(
    'title' => html($_POST['title']),
    'author' => html($_POST['author']),
    'volume' => (int)$_POST['volume'],
    'issue' => (int)$_POST['issue'],
    'pages' => html($_POST['pages']),
    'length' => html($_POST['length']),
    'year' => (int)$_POST['year'],
    'doi' => html($_POST['doi']),
    'abstract' => html($_POST['abstract']),
    'link' => html($_POST['link']),
    'source' => (int)$_POST['source'],
    'status' => mysql_clean('pending'),
  );
  $id = $db->insert("papers",$post);
  $id = $db->lastInsertId();

  $hash = encrypt($id . html($_POST['title'], false));

  $message = 
"Name: " . mail_clean($_POST['yourname']) . "
E-mail: " . mail_clean($_POST['youremail']) . "
Date: " . date("r") . "
IP: " . $_SERVER["REMOTE_ADDR"] . "

-----------------------------------------
          PUBLICATION ADDED
-----------------------------------------
Title: " . mail_clean($_POST['title']) . "
Author: " . mail_clean($_POST['author']) . "
Paper ID: $id
Link: " . URL . "publication/$id
Review: " . URL . "publication.view.php?id=$id&hash=$hash

-----------------------------------------
                MESSAGE
-----------------------------------------
" . mail_clean($_POST['comments'], "box") . "


-----------------------------------------
            TECH DETAILS
-----------------------------------------
" . getinfo();

  mailadmins($message, "New publication at MFA Tools", $_POST['youremail']);


}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Add Publication | <?php echo SITENAME ?></title>
    <style type="text/css">
    textarea.form-control[name='abstract']{height:300px}
    .newsource{display:none}
    </style>
    <script type="text/javascript">
    $(function(){
      $("input[name='doi']").keydown(function(){
        if ($(this).val() == "") {
          $(".publink").slideDown('slow');
        } else {
          $(".publink").slideUp('slow');
        }
      });
      $("select[name='source']").change(function(){
        if ($(this).val() == "unlisted") {
          $(".newsource").show('fast');
        } else {
          $(".newsource").hide('fast');
        }
      });
    });
    </script>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

<h1>Add Publication</h1>

<p>Do you know of a missing publication? Please add the details here so people
can find this! The publication should be related to Material Flow Analysis.</p>

<?php if ($id) { ?>

  <div class="alert alert-success">

  <p>Thanks, the paper has been submitted! This entry will be reviewed and most likely soon be added to the database.
  You will receive an e-mail confirmation when this paper has been added.</p>

  <p>
    <a href="publications/add" class="btn btn-primary btn-large">Add another publication</a>
    <a href="./" class="btn btn-primary btn-large">Back to the homepage</a>
  </p>

  </div>

<?php } else { ?>

<form method="post" class="form form-horizontal">

  <div class="form-group">
    <label class="col-sm-2 control-label">Title</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="title" value="<?php echo $info->title ?>" required />
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label">Author(s)</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="author" value="<?php echo $info->author ?>" required />
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label">Journal / source / publisher</label>
    <div class="col-sm-10">
      <select name="source" class="form-control" required>
        <option value=""></option>
      <?php foreach ($journals as $row) { ?>
        <option value="<?php echo $row['id'] ?>"<?php if ($row['id'] == $info->source) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
      <?php } ?>
        <option value="unlisted">UNLISTED - add new option</option>
      </select>
    </div>
  </div>

  <div class="form-group newsource">
    <label class="col-sm-2 control-label">Other source</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="newsource" />
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label">Year of publication</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="year" value="<?php echo $info->year ?>" />
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label">DOI / ISBN</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="doi" value="<?php echo $info->doi ?>" placeholder="If provided, no link is necessary" />
    </div>
  </div>

  <div class="form-group publink">
    <label class="col-sm-2 control-label">Link for more information</label>
    <div class="col-sm-10">
      <input class="form-control" type="url" name="link" value="<?php echo $info->link ?>" placeholder="http://..." />
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label">Abstract</label>
    <div class="col-sm-10">
      <textarea class="form-control" name="abstract"><?php echo $info->abstract ?></textarea>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label">Your name</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="yourname" value="<?php echo $info->yourname ?>" />
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label">Your e-mail</label>
    <div class="col-sm-10">
      <input class="form-control" type="email" name="youremail" value="<?php echo $info->youremail ?>" />
    </div>
  </div>

  <fieldset>
    <legend>Optional Information</legend>
    
    <div class="form-group">
      <label class="col-sm-2 control-label">Volume</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="volume" value="<?php echo $info->volume ?>" placeholder="E.g. 15" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Issue</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="issue" value="<?php echo $info->issue ?>" placeholder="E.g. 4" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Pages</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="pages" value="<?php echo $info->pages ?>" placeholder="E.g. 150-173" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Comments</label>
      <div class="col-sm-10">
        <textarea class="form-control" name="comments"><?php echo $info->comments ?></textarea>
      </div>
    </div>

  </fieldset>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">Add</button>
    </div>
  </div>

</form>

<?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
