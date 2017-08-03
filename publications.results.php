<?php
$skip_login = true;
$show_breadcrumbs = true;
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 4;
$page = 4;

if ($_POST['source']) {
  $_GET['source'] = $_POST['source'];
}

if ($_POST['searchphrase']) {
  $explode = explode(" ", $_POST['searchphrase']);
  foreach ($explode as $key => $value) {
    if ($_POST['title'] && $_POST['abstract']) {
      $sql .= " AND (abstract LIKE '%" . mysql_clean($value, "wildcard") . "%' OR title LIKE '%" . mysql_clean($value, "wildcard") . "%')";
    } elseif ($_POST['title']) {
      $sql .= " AND (title LIKE '%" . mysql_clean($value, "wildcard") . "%')";
    } elseif ($_POST['abstract']) {
      $sql .= " AND (abstract LIKE '%" . mysql_clean($value, "wildcard") . "%')";
    }
  }
  $filters = "<strong>Search phrase:</strong> " . html($_POST['searchphrase'], false);
}

if ($_GET['source']) {
  $source = (int)$_GET['source'];
  $this_page = "Filter publications";
  $info = $db->record("SELECT * FROM sources WHERE id = $source");
  $filters .= "<strong>Source</strong>: " . $info->name . "<br />";
  $sql .= " AND papers.source = $source";
}

if ($_POST['start']) {
  $start = (int)$_POST['start'];
  $sql .= " AND papers.year >= $start";
  $filters .= "<strong>Year</strong>: ";
  $filters .= $_POST['end'] ? "$start - " . (int)$_POST['end'] : "during or after $start";
  $filters .= "<br />";
}
if ($_POST['end']) {
  $end = (int)$_POST['end'];
  $sql .= " AND papers.year <= $end";
  $filters = !$start ? "<strong>Year</strong>: during or before $end<br />" : '';
}

if (is_array($_POST['tags'])) {
  $this_page = "Filter publications";
  foreach ($_POST['tags'] as $key => $value) {
    $id = (int)$key;
    $parent = $db->record("SELECT name FROM tags_parents WHERE id = $id");
    $filters .= '<strong>'.$parent->name.'</strong>: ';
    $in_tags = false;
    foreach ($value as $subvalue) {
      $tag = (int)$subvalue;
      $in_tags .= $tag.",";
      $info = $db->record("SELECT * FROM tags WHERE id = $tag");
      $filters .= $info->tag . " or ";
    }
    $filters = substr($filters, 0, -4) . '<br />'; // Take out ' or '
    $in_tags = substr($in_tags, 0, -1); // Take out extra comma
    $sql .= " AND EXISTS (SELECT * FROM tags_papers WHERE tags_papers.paper = papers.id AND tag IN ($in_tags))";
  }
}

if ($_POST['update_tag'] && defined("ADMIN")) {
  $post = array(
    'tag' => html($_POST['tag']),
    'gps' => html($_POST['gps']),
    'parent' => (int)$_POST['parent'],
    'description' => $_POST['description'],
  );
  $update_tag = (int)$_POST['update_tag'];
  $db->update("tags",$post,"id = $update_tag");
  $print = "Information was saved";
}

if ($_GET['tag']) {
  $tag = (int)$_GET['tag'];
  $this_page = "Filter publications";

  if (defined("ADMIN")) {
    $parent_tag_list = $db->query("SELECT * FROM tags_parents ORDER BY name");
  }

  $list = $db->query("SELECT papers.* 
  FROM 
    tags_papers 
  JOIN
    papers ON tags_papers.paper = papers.id
  WHERE tags_papers.tag = $tag AND papers.status = 'active' ORDER BY year DESC, title");
  $info = $db->record("SELECT * FROM tags WHERE id = $tag");
  $filters = "<strong>Tag</strong>: " . $info->tag;
  $title = $info->tag . " | ";
} elseif ($_GET['keyword']) {
  $keyword = (int)$_GET['keyword'];
  $this_page = "Filter publications";
  $list = $db->query("SELECT papers.* 
  FROM 
    keywords_papers 
  JOIN
    papers ON keywords_papers.paper = papers.id
  WHERE keywords_papers.keyword = $keyword AND papers.status = 'active' ORDER BY year DESC, title");
  $info = $db->record("SELECT * FROM keywords WHERE id = $keyword");
  $filters = "<strong>Keyword</strong>: " . $info->keyword;
  $title = $info->keyword . " | ";
} else {
  $list = $db->query("SELECT * FROM papers WHERE status = 'active' $sql ORDER BY year DESC, title");
}

$gps_tagged = ID == 2 ? 2 : 4;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $title ?>Publications | <?php echo SITENAME ?></title>
    <style type="text/css">
    .recordbox h3 {
      font-size:18px;
    }
    .recordbox {
      border-bottom:1px solid #999;
      padding:20px 0;
    }
    .recordbox .type {
      opacity:0.7;
    }
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

<h1><?php echo ID == 2 ? "EPR" : "Urban Metabolism"; ?> Publications</h1>

<?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

<p>
Explanatory notes about how to use the database go here. 
</p>

<p>
  Have you written or can you recommend a publication that should be added? <a href="publications/add">Add it here!</a>
</p>

<?php if ($filters) { ?>
  <div class="resultbox">
  <h4>
    <i class="glyphicon glyphicon-filter"></i>
    Filter(s): 
  </h4>
  <p><?php echo $filters ?></p>
  <p>
    <a href="publications/search">New database search</a> |
    <a href="publications/list">View all publications</a>
  </p>
  </div>
<?php } elseif (ID == 1) { ?>

  <div class="alert alert-info">
    <a href="publications.export.php" class="btn btn-default">
      <i class="fa fa-table"></i>
      Download
    </a>
    You can download the full publications database including publication title, author(s), year, journal, tags, etc. as a <strong>CSV file</strong>.
  </div>

<?php } ?>

<div class="resultbox">
  <i class="fa fa-info-circle"></i>
  <strong><?php echo count($list) ?></strong>
  <?php echo count($list) > 1 ? 'publications' : 'publication' ?> found.
</div>

<?php if (count($list)) { ?>

<div class="row">
<div class="col-3 ">
  <ul class="nav nav-section-menu nav-sidebar">
              <li>
        <a href="omat/documentation/1" class="nav-link">2020 (22)</span>
          <i class="fa fa-angle-right"></i>
        </a>
      </li>
              <li>
        <a href="omat/documentation/2" class="nav-link">Basic MFA</span>
          <i class="fa fa-angle-right"></i>
        </a>
      </li>
              <li>
        <a href="omat/documentation/3" class="nav-link">Advanced options</span>
          <i class="fa fa-angle-right"></i>
        </a>
      </li>
              <li>
        <a href="omat/documentation/4" class="nav-link">Collecting data</span>
          <i class="fa fa-angle-right"></i>
        </a>
      </li>
          </ul>
</div>

<div class="col-9  main">

<?php foreach ($list as $row) { ?>

<div class="recordbox">
  <h3><a href="publication/<?php echo $row['id'] ?>"><?php echo $row['title'] ?></a></h3>
  <div class="row">
    <div class="col-2">
    <span class="type">
      Journal Article
      </span>
      <br />
      <?php echo $row['year'] ?>
    </div>
    <div class="col-10">
      <?php echo $row['author'] ?>
    </div>
  </div>
</div>
<?php } ?>

</div>
</div>

<?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
