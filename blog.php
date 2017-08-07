<?php
$show_breadcrumbs = true;
require_once 'functions.php';

$section = 8;
$page = 1;

$id = (int)$_GET['id'];
if ($id) {
  $info = $db->record("SELECT * FROM content WHERE id = $id AND active = 1 AND type = 'blog'");

  $this_page = $info->title;

  if (!$info->id) {
    kill("Blog post not found", false);
  }
} else {
  $info = $db->record("SELECT * FROM content WHERE active = 1 AND type = 'blog' ORDER BY id DESC LIMIT 1");
  $id = $info->id;
  $blog_home = true;
}

if ($id) {

  $authors = $db->query("SELECT content_authors.* 
  FROM content_authors_pivot JOIN content_authors ON content_authors_pivot.author_id = content_authors.id
  WHERE content_authors_pivot.content_id = $id 
  ORDER BY content_authors.name");

  $papers = $db->query("SELECT papers.*
  FROM content_links 
  JOIN papers ON content_links.paper = papers.id
  WHERE content_links.content = $id ORDER BY papers.title");

}

$today = date("Y-m-d");
$recent = $db->query("SELECT id, title, date 
FROM content
WHERE date <= '$today' AND active = 1 AND type = 'blog' ORDER BY date DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->title ?> | <?php echo SITENAME ?></title>
    <style type="text/css">
    .side h4.author, .side h2.jumpdown {margin-top:40px}
    .col-md-9 img{max-width:100%}
    <?php if ($id == 4) { ?>
      .blogarticle img {padding:5px;margin:5px;border:1px solid #ccc}
    <?php } ?>
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

<div class="row">

  <div class="col-md-9 blogarticle">
    <h1><?php echo $info->title ?></h1>
    <p><em>Published on <?php echo format_date("M d, Y", $info->date) ?></em></p>
    <?php echo $info->content ?>
  </div>

  <div class="col-md-3 side">
    <h2><?php echo count($authors) > 1 ? "Authors" : "Author"; ?></h2>
      <?php foreach ($authors as $row) { ?>
        <h4 class="author">
          <i class="fa fa-user"></i>
          <?php echo $row['name'] ?>
        </h4>
        <?php if ($row['email']) { ?>
          <p><?php echo $row['email'] ?></p>
        <?php } ?>
        
      <?php } ?>

      <?php if (count($papers)) { ?>

      <h2 class="jumpdown">Publications</h2>

      <ul class="nav nav-list">
      <?php foreach ($papers as $row) { ?>
        <li>
          <a href="publication/<?php echo $row['id'] ?>"><?php echo $row['title'] ?></a>        
        </li>
      <?php } ?>
      </ul>

      <?php } ?>

      <?php if (count($recent)) { ?>

        <h2 class="jumpdown">Recent Blog Posts</h2>

          <div class="list-group">
            <?php foreach ($recent as $row) { ?>
              <a href="blog/<?php echo $row['id'] ?>-<?php echo flatten($row['title']) ?>" class="list-group-item <?php echo $row['id'] == $id ? 'active' : ''; ?>">
                <h4 class="list-group-item-heading"><?php echo $row['title'] ?></h4>
                <p class="list-group-item-text">Published on <?php echo format_date("M d, Y", $row['date']); ?></p>
              </a>
            <?php } ?>
          </div>

      <?php } ?>
        
  </div>
</div>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
