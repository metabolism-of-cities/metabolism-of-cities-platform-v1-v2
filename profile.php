<?php
require_once 'functions.php';
$section = 2;
$page = 6;
$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM people WHERE id = $id");
if (!$info->id) {
  kill("Person not found", "critical");
}

$papers = $db->query("SELECT 
  papers.*
FROM people_papers
  JOIN papers ON people_papers.paper = papers.id
WHERE people_papers.people = $id
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
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->firstname ?> <?php echo $info->lastname ?> | <?php echo SITENAME ?></title>
    <style type="text/css">
    .tags{list-style:none;padding:0}
    .tags li{margin:6px 0px}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1><?php echo $info->firstname ?> <?php echo $info->lastname ?></h1>

  <dl class="dl dl-horizontal">
    <?php if ($info->affiliation) { ?>
      <dt>Affiliation</dt>
      <dd><?php echo $info->affiliation ?></dd>
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

<h2>Common themes</h2>

<p>
  All publications listed here have been tagged by our team. This enables us to review the 
  tags that most commonly appear for this author. Please find the list, generated automatically, below. 
</p>

<?php foreach ($tag_parent_list as $parent => $value) { ?>
  <h3><?php echo $parent ?></h3>
  <ul class="tags">
  <?php foreach ($tag[$parent] as $key => $value) { ?>
    <li><a class="btn btn-primary" href="http://"><?php echo $value ?></a>
      <span class="badge"><?php echo $tag_counter[$key] ?></span>
    </li>
  <?php } ?>
  </ul>

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
edit your profile and add or edit your own publications by clicking here.
</p>

<p>
  <a href="people" class="btn btn-info">View all people</a>
</p>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
