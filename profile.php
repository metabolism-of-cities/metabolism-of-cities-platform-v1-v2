<?php
$skip_login = true;
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 4;
$page = 4;
$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM people WHERE id = $id AND active IS TRUE");
if (!$info->id) {
  kill("Person not found", false);
}

$papers = $db->query("SELECT 
  papers.*
FROM people_papers
  JOIN papers ON people_papers.paper = papers.id
WHERE people_papers.people = $id AND papers.status = 'active'
ORDER BY papers.year DESC");

$tags = $db->query("SELECT  
  tags.id, tags.tag, tags_parents.name AS parent
FROM people_papers
  JOIN tags_papers ON people_papers.paper = tags_papers.paper
  JOIN tags ON tags_papers.tag = tags.id
  JOIN tags_parents ON tags.parent = tags_parents.id
WHERE people_papers.people = $id AND
  tags_parents.id IN (3,4,5,6,7)
ORDER BY tags_parents.parent_order, tags_parents.name DESC");

foreach ($tags as $row) {
  $tag[$row['parent']][$row['id']] = $row['tag'];
  $tag_parent_list[$row['parent']] = true;
  $tag_counter[$row['id']]++;
}

if (defined("ADMIN")) { 
  if ($_POST['merge']) {
    $merge = (int)$_POST['merge'];
    if ($merge == $id) {
      die("You can not merge this profile with this same profile");
    }
    $db->query("UPDATE people_papers SET people = $merge WHERE people = $id");
    $db->query("UPDATE people SET active = 0 WHERE id = $id");
    header("Location: " . URL . "people/$merge-profile");
    exit();
  }
  $authors = $db->query("SELECT * FROM people WHERE active IS TRUE ORDER BY lastname");
}

if ($_POST['fax']) {

  // We call this field 'fax' to make sure bots don't enter a valid address and will
  // thus not trigger any e-mail to be sent.

  $mail = trim($_POST['fax']);

  if (!check_mail($mail)) {
    die("You did not enter a valid e-mail address. Please try again.");
  }

  $check = $db->record("SELECT * FROM people_access WHERE people = $id AND active IS TRUE LIMIT 1");

  if ($check->id) {

    $error = "The access link has already been supplied. If you lost this link, please e-mail us at " . EMAIL;

  } else {

    require_once 'functions.mail.php';

    $post = array(
      'people' => $id,
      'email' => mysql_clean($mail),
      'ip' => mysql_clean($_SERVER["REMOTE_ADDR"]),
      'details' => mysql_clean(getinfo()),
    );
    $db->insert("people_access",$post);

    $accessinfo = $db->record("SELECT * FROM people_access WHERE people = $id ORDER BY id DESC LIMIT 1");
    $access_id = $accessinfo->id;

    $link = URL . "access/$access_id/" . encrypt("PROFILE $access_id");

    $message = 

"Dear {$info->firstname} {$info->lastname},

Thanks for requesting a link to edit your profile on the Metabolism of Cities website. We hereby send you a link that you can use to edit your profile and submit information to our site. This is a unique link that will give you instant access without having to log in. Keep the link safe. If you lose it, or if you want to request a new link, please feel free to e-mail us at info@metabolismofcities.org. For security purposes the website will not process an automated request for a new link after you have opened this link. 

*Access link:*

[$link Open your profile]

We highly value input from our visitors and would like to encourage you to let us know how you think our website can be improved. You can also join our group of volunteers and help out adding new content, creating new functionalities or helping out analyzing data. Please do not hesitate to get in touch if you want to learn more. 

Best regards,

The Metabolism of Cities Team
info@metabolismofcities.org";

    pearMail($mail, "Link to access Metabolism of Cities", $message);

    $print = "We have e-mailed the access link to {$mail}";
  }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->firstname ?> <?php echo $info->lastname ?> | <?php echo SITENAME ?></title>
    <style type="text/css">
    .tags{list-style:none;padding:0}
    .tags li{margin:6px 0px}
    #getaccess{display:none}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1><?php echo $info->firstname ?> <?php echo $info->lastname ?></h1>

  <?php if ($error) { echo "<div class=\"alert alert-danger\">$error</div>"; } ?>
  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <dl class="dl dl-horizontal">
    <?php if ($info->affiliation) { ?>
      <dt>Affiliation</dt>
      <dd><?php echo $info->affiliation ?></dd>
    <?php } ?>
    <?php if ($info->city) { ?>
      <dt>City</dt>
      <dd><?php echo $info->city ?></dd>
    <?php } ?>
    <?php if ($info->country) { ?>
      <dt>Country</dt>
      <dd><?php echo $info->country ?></dd>
    <?php } ?>

    <?php if ($info->email && $info->email_public) { ?>
      <dt>E-mail</dt>
      <dd><?php echo $info->email ?></dd>
    <?php } ?>
    <?php if ($info->profile) { ?>
      <dt>Profile</dt>
      <dd><?php echo $info->profile ?></dd>
    <?php } ?>
    <?php if ($info->research_interests) { ?>
      <dt>Research Interests</dt>
      <dd><?php echo $info->research_interests ?></dd>
    <?php } ?>
    <?php if ($info->url) { ?>
      <dt>URL</dt>
      <dd><a href="<?php echo $info->url ?>"><?php echo $info->url ?></a></dd>
    <?php } ?>
  </dl>

<h2>Publications related to (urban) metabolism by <?php echo $info->firstname ?> <?php echo $info->lastname ?></h2>

<div class="alert alert-info">
  <strong><?php echo count($papers) ?></strong> publication(s) found.
</div>

<table class="table table-striped">
  <tr>
    <th>Title</th>
    <th>Author(s)</th>
    <th>Year</th>
  </tr>
<?php foreach ($papers as $row) { ?>
  <tr>
    <td><a href="publication/<?php echo $row['id'] ?>"><?php echo $row['title'] ?></a></td>
    <td><?php echo authorlist($row['id']) ?></td>
    <td><?php echo $row['year'] ?></td>
  </tr>
<?php } ?>
</table>

<?php if ($tag_parent_list) { ?>

  <h2>Common themes</h2>

  <p>
    All publications listed here have been tagged by our team. This enables us to review the 
    tags that most commonly appear for this author. Please find the list, generated automatically, below. 
  </p>

  <?php foreach ($tag_parent_list as $parent => $value) { ?>
    <h3><?php echo $parent ?></h3>
    <ul class="tags">
    <?php foreach ($tag[$parent] as $key => $value) { ?>
      <li><a class="btn btn-primary" href="tags/<?php echo $key ?>/<?php echo flatten($value) ?>"><?php echo $value ?></a>
        <span class="badge"><?php echo $tag_counter[$key] ?></span>
      </li>
    <?php } ?>
    </ul>

  <?php } ?>

<?php } ?>

<p>
Publications are continuously added by a community of volunteers. Author names
reported by journals and search engines do not always follow a standard format,
which means that errors can slip through the cracks. You can help expand our
list of publications by <a href="publications/add">adding missing
publications</a>. If you spot a mistake please <a href="page/contact">contact us</a>.
</p>
<p>
Are you <strong><?php echo $info->firstname ?> <?php echo $info->lastname ?></strong>? You can
edit your profile and add or edit your own publications by <a href="" id="access">clicking here</a>.
</p>

<div id="getaccess">

  <h3>Edit your profile</h3>

  <div class="alert alert-info">
    If you are <strong><?php echo $info->firstname ?> <?php echo $info->lastname ?></strong>
    then you can edit your profile. To do so, please enter your e-mail address. We will send 
    you a link to edit your profile. You can only request this link once. If you have lost your
    profile editing link, <a href="page/contact">please contact us</a>.
  </div>

  <form method="post" class="form form-horizontal">

    <div class="form-group">
      <label class="col-sm-2 control-label">E-mail</label>
      <div class="col-sm-10">
        <input class="form-control" type="email" name="fax" />
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Request access</button>
      </div>
    </div>

  </form>

</div>

<p>
  <a href="people" class="btn btn-info">View all people</a>
</p>

<?php if (defined("ADMIN")) { ?>

  <h3>Admin options</h3>

  <p>Merge this person with another profile.</p>

  <div class="alert alert-info">
    <strong>Note</strong>: 
    this current profile will cease to exist. All publications currently associated with
    this profile will be transferred to the profile you select from the list.
  </div>

  <form method="post" class="form form-horizontal">
    <div class="form-group">
      <label class="col-sm-2 control-label">Merge with</label>
      <div class="col-sm-10">
        <select name="merge" class="form-control">
          <?php foreach ($authors as $row) { ?>
            <option value="<?php echo $row['id'] ?>"<?php if ($row['id'] == $id) { echo ' selected'; } ?>>
              <?php echo $row['lastname'] ?>, <?php echo $row['firstname'] ?>
              [<?php echo $row['id'] ?>]
              <?php if ($id == $row['id']) { ?>
              -- CURRENT AUTHOR
              <?php } ?>
            </option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Merge</button>
      </div>
    </div>
  </form>

<?php } ?>

<?php require_once 'include.footer.php'; ?>

<script type="text/javascript">
$(function(){
  $("#access").click(function(e){
    e.preventDefault();
    $("#getaccess").show('fast');
  });

});
</script>

  </body>
</html>
