<?php
$skip_login = true;
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 4;
$page = 3;

$journals = $db->query("SELECT * FROM sources ORDER BY name");

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM papers WHERE id = $id");

$hash = $_GET['hash'];
$gethash = encrypt($info->id . $info->title);

if (defined("ADMIN") || ($gethash == $_GET['hash'])) {
  // Access granted
} else {
  die("Page not found");
}

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
    'title_native' => html($_POST['title_native']),
    'author' => html($_POST['author']),
    'volume' => (int)$_POST['volume'],
    'issue' => (int)$_POST['issue'],
    'pages' => html($_POST['pages']),
    'length' => html($_POST['length']),
    'year' => (int)$_POST['year'],
    'doi' => html($_POST['doi']),
    'abstract' => html($_POST['abstract']),
    'abstract_native' => html($_POST['abstract_native']),
    'language' => html($_POST['language']),
    'link' => html($_POST['link']),
    'source' => (int)$_POST['source'],
    'open_access' => is_numeric($_POST['open_access']) ? (int)$_POST['open_access'] : NULL,
    'abstract_status' => mysql_clean($_POST['abstract_status']),
    'editor_comments' => html($_POST['editor_comments']),
  );
  $db->update("papers",$post,"id = $id");
  $hash = encrypt($id . html($_POST['title'], false));

  if ($_GET['profile']) {
    peoplelog("User made a change to an existing publication");
  }

}

$abstract_status = array('pending','author_approved','journal_approved','open_access','not_approved','toc_only');

if ($_GET['profile']) {
  require_once 'functions.profile.php';
  $sub_page = 2;
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Edit Publication | <?php echo SITENAME ?></title>
    <style type="text/css">
    textarea.form-control[name='abstract']{height:300px}
    textarea.form-control[name='abstract_native']{height:300px}
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
      $("select[name='language']").change(function(){
        if ($(this).val() == "English") {
          $(".foreignlanguage").hide();
        } else {
          $(".foreignlanguage").show();
        }
      });
      $("select[name='language']").change();
    });
    </script>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

<h1>Edit Publication</h1>

<?php if ($_POST) { ?>

  <div class="alert alert-success">

  <p>Information was saved.</p>

  <?php if ($_GET['profile']) { ?>

    <p>
      <a class="btn btn-primary" href="publication.edit.php?id=<?php echo $id ?>&amp;hash=<?php echo $hash ?>&amp;profile=true">Re-edit</a>
      <a class="btn btn-primary" href="publication.view.php?id=<?php echo $id ?>&amp;test_mode=1">View publication</a>
      <a href="profile/<?php echo $profile_id ?>/publication" class="btn btn-primary btn-large">Add another publication</a>
    </p>

  <?php } else { ?>

    <p>
      <a class="btn btn-primary" href="publication.edit.php?id=<?php echo $id ?>&amp;hash=<?php echo $hash ?>">Re-edit</a>
      <a class="btn btn-primary" href="publication.view.php?id=<?php echo $id ?>&amp;hash=<?php echo $hash ?>">Edit tags</a>
      <a class="btn btn-primary" href="publication.view.php?id=<?php echo $id ?>&amp;test_mode=1">View as user</a>
    </p>

    <p>
      <a href="publications/add" class="btn btn-primary btn-large">Add another publication</a>
    </p>


  <?php } ?>

  </div>

<?php } else { ?>

<form method="post" class="form form-horizontal">

  <div class="form-group">
    <label class="col-sm-2 control-label">Language</label>
    <div class="col-sm-10">
      <select name="language" class="form-control">
        <?php foreach ($languages as $value) { ?>
          <option value="<?php echo $value ?>"<?php if ($info->language == $value) { echo ' selected'; } ?>><?php echo $value ?></option>
        <?php } ?>
      </select>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label">Title (English)</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="title" value="<?php echo $info->title ?>" required />
    </div>
  </div>

  <div class="form-group foreignlanguage">
    <label class="col-sm-2 control-label">Title (original)</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="title_native" value="<?php echo $info->title_native ?>" />
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
    <label class="col-sm-2 control-label">Abstract (English)</label>
    <div class="col-sm-10">
      <textarea class="form-control" name="abstract"><?php echo br2nl($info->abstract) ?></textarea>
    </div>
  </div>

  <div class="form-group foreignlanguage">
    <label class="col-sm-2 control-label">Abstract (original)</label>
    <div class="col-sm-10">
      <textarea class="form-control" name="abstract_native"><?php echo br2nl($info->abstract_native) ?></textarea>
    </div>
  </div>

  <div class="form-group hide">
    <label class="col-sm-2 control-label">Abstract status</label>
    <div class="col-sm-10">
      <select name="abstract_status" class="form-control">
      <option value=""></option>
        <?php foreach ($abstract_status as $key => $value) { ?>
          <option value="<?php echo $value ?>"<?php if ($info->abstract_status == $value) { echo ' selected'; } ?>><?php echo $value ?></option>
        <?php } ?>
      </select>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label">Open Access</label>
    <div class="col-sm-10">
      <select name="open_access" class="form-control">
        <option value=""<?php if ($info->open_access == NULL) { echo ' selected'; } ?>>Unknown</option>
        <option value="1"<?php if ($info->open_access == 1) { echo ' selected'; } ?>>Yes</option>
        <option value="0"<?php if ($info->open_access === "0") { echo ' selected'; } ?>>No</option>
      </select>
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
      <label class="col-sm-2 control-label">Editor Comments</label>
      <div class="col-sm-10">
        <textarea class="form-control" name="editor_comments"><?php echo br2nl($info->editor_comments) ?></textarea>
      </div>
    </div>

  </fieldset>

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
