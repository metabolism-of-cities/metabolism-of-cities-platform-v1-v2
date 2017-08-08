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

if ($_GET['year']) {
  $year = (int)$_GET['year'];
  $sql .= " AND papers.year = $year";
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

if ($_GET['tag']) {
  $_GET['search'][] = $_GET['tag'];
  unset($_GET['tag']);
}

if (is_array($_GET['search'])) {
  $this_page = "Filter publications";
  foreach ($_GET['search'] as $key => $value) {
    $tag = (int)$value;
    if ($tag == 213) {
      $tag = 1045;
      $alias = true;
    }
    if ($tag) {
      $in_tags .= $tag.",";
      $info = $db->record("SELECT * FROM tags WHERE id = $tag");
      $filters .= $info->tag . " or ";
    } else {
      $info = $db->record("SELECT * FROM tags WHERE tag = '".html($value)."'");
    }
    if ($info->id) {
      $sql .= " AND EXISTS (SELECT * FROM tags_papers WHERE tags_papers.paper = papers.id AND tag IN ($info->id))";
      $tags_selected[$info->id] = true;
    } else {
      // Freely typed, search through abstract and title
      $sql .= " AND (abstract LIKE '%" . mysql_clean($value, "wildcard") . "%' OR title LIKE '%" . mysql_clean($value, "wildcard") . "%')";
      $value = htmlentities($value);
      $additional_keywords[$value] = $value;
    }
  }
  $filters = substr($filters, 0, -4) . '<br />'; // Take out ' or '
  $in_tags = substr($in_tags, 0, -1); // Take out extra comma
  //$sql .= " AND EXISTS (SELECT * FROM tags_papers WHERE tags_papers.paper = papers.id AND tag IN ($in_tags))";
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
  $list = $db->query("SELECT papers.* 
  FROM papers 
  WHERE status = 'active' $sql 
  ORDER BY year DESC, title");
}

$years = $db->query("SELECT COUNT(*) AS total, year FROM papers WHERE status = 'active' $sql GROUP BY year ORDER BY year DESC");
$types = $db->query("SELECT COUNT(*) AS total, type FROM papers WHERE status = 'active' $sql GROUP BY type ORDER BY total DESC, type");
$tags = $db->query("SELECT tags.id, tags.tag, tags_papers.paper
FROM tags_papers
  JOIN tags ON tags_papers.tag = tags.id
  JOIN papers ON tags_papers.paper = papers.id
WHERE papers.status = 'active' $sql
");

foreach ($tags as $row) { 
  $tag_hits[$row['id']]++;
  $tag_names[$row['id']] = $row['tag'];
  $tag_papers[$row['paper']][$row['id']] = true;
}

$all_tags = $db->query("SELECT * FROM tags ORDER BY tag");

// Create a list of the most common tags
if (count($tag_hits)) {
  arsort($tag_hits);
}

// Temporary fix until we unify queries
$get_types = $db->query("SELECT * FROM paper_types");
foreach ($get_types as $row) {
  $type[$row['id']] = $row['name'];
}

$gps_tagged = ID == 2 ? 2 : 4;

$results_page = true;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $title ?>Publications | <?php echo SITENAME ?></title>
    <link rel="stylesheet" href="css/select2.min.css" />
    <style type="text/css">
    .resultbox .row {
      padding:10px;
    }
    .resultbox h4 {
      padding:10px 10px 0 10px;
    }
    .nav-section-menu.nav a.show-all.nav-link {
      background:#6b6b6b;
      color:#f4f4f4;
    }
    .nav-section-menu.nav .nav-link {
      font-size:14px;
    }
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
    .hide {
      display:none;
    }
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

<?php require_once 'include.search.php'; ?>

<?php if (!count($_GET) && !count($_POST) && !$_GET['all']) { $hide_results = true; } ?>

<?php if (!count($list) && !$hide_results) { ?>
  <div class="alert alert-warning">No records were found.</div>
<?php } ?>

<?php if (count($list)) { ?>

<div class="row filters">

<script type="text/javascript">
$(function(){
  
$(".filters .filter").click(function(e){
  e.preventDefault();
  var type = $(this).data("type");
  var id = $(this).data("id");
  var hide = ".recordbox:not(."+type+"-"+id+")";
  var show = ".recordbox."+type+"-"+id;
  $(show).show('fast');
  $(hide).hide('fast');
  $(this).closest("ul").find("li a").removeClass('active');
  $(this).addClass("active");
});
});
</script>

  <?php if (!$hide_results) { ?>

  <div class="col-3 ">
    <ul class="nav nav-section-menu nav-sidebar">
      <li class="nav-header">Filter by Year</li>
      <?php $hidden_items = false; $i = 0; foreach ($years as $row) { ?>
        <?php
          $i++;
          if ($i > 5) {
            $class = "hide hide-year";
            $hidden_items = true;
          }
        ?>
        <li class="<?php echo $class ?>">
          <a href="#" class="nav-link filter" data-id="<?php echo $row['year'] ?>" data-type="year"><?php echo $row['year'] ?> 
            <span class="badge badge-default"><?php echo $row['total'] ?></span>
            <i class="fa fa-angle-right"></i>
          </a>
        </li>
      <?php } ?>
      <?php if ($hidden_items) { ?>
      <li>
        <a href="#" class="show-all nav-link" data-type="year">Show all 
          <i class="fa fa-angle-down"></i>
        </a>
      </li>
      <?php } ?>
    </ul>
    <ul class="nav nav-section-menu nav-sidebar">
      <li class="nav-header">Filter by Type</li>
      <?php $hidden_items = false; $i = 0; foreach ($types as $row) { ?>
        <?php
          $i++;
          $class = "reg";
          if ($i > 5) {
            $class = "hide hide-type";
            $hidden_items = true;
          }
        ?>
        <li class="<?php echo $class ?>">
          <a href="#" class="nav-link filter" data-id="<?php echo $row['type'] ?>" data-type="type"><?php echo $type[$row['type']] ?> 
              <span class="badge badge-default"><?php echo $row['total'] ?></span>
            <i class="fa fa-angle-right"></i>
          </a>
        </li>
      <?php } ?>
      <?php if ($hidden_items) { ?>
      <li>
        <a href="#" class="show-all nav-link" data-type="type">Show all 
          <i class="fa fa-angle-down"></i>
        </a>
      </li>
      <?php } ?>
    </ul>
    <ul class="nav nav-section-menu nav-sidebar">
      <li class="nav-header">Filter by Tag</li>
      <?php $hidden_items = true; $i = 0; foreach ($tag_hits as $row['tag'] => $row['total']) { ?>
        <?php
          $i++;
          $class = "reg";
          if ($i > 10) {
            $class = "hide hide-tag";
          }
          if ($i < 30) {
        ?>
        <li class="<?php echo $class ?>">
          <a href="#" class="nav-link filter" data-id="<?php echo $row['tag'] ?>" data-type="tag"><?php echo $tag_names[$row['tag']] ?> 
              <span class="badge badge-default"><?php echo $row['total'] ?></span>
            <i class="fa fa-angle-right"></i>
          </a>
        </li>
      <?php } } ?>
      <?php if ($hidden_items) { ?>
      <li>
        <a href="#" class="show-all nav-link" data-type="tag">Show all 
          <i class="fa fa-angle-down"></i>
        </a>
      </li>
      <?php } ?>
    </ul>
  </div>

  <?php } ?>

  <div class="col-<?php echo $hide_results ? 12 : 9 ?> main records">

    <?php if ($hide_results) { ?>

      <div class="alert alert-default hide">
        Enter a search phrase in the box above, or 
        use the filters on the left to browse through our database. 
        Alternatively, click the button below to view our entire list.
      <br /><br />
      <a href="publications/all" class="btn btn-primary"><i class="fa fa-list"></i> View All</a>
      </div>

      <div class="" style="height:200px;"></div>

    <?php } else { ?>

  <div class="resultbox">
    <i class="fa fa-info-circle"></i>
    <strong><?php echo count($list) ?></strong>
    <?php echo count($list) > 1 ? 'publications' : 'publication' ?> found.
  </div>

      <?php foreach ($list as $row) { ?>

      <div class="recordbox year-<?php echo $row['year'] ?> type-<?php echo $row['type'] ?> <?php foreach ($tag_papers[$row['id']] as $key => $value) { echo "tag-$key "; } ?>">
        <h3><a href="publication/<?php echo $row['id'] ?>"><?php echo $row['title'] ?></a></h3>
        <div class="row">
          <div class="col-2">
            <span class="type">
              <?php echo $type[$row['type']] ?>
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

  <?php } ?>

  </div>


</div>

<?php } ?>

<?php require_once 'include.footer.php'; ?>
  </body>
</html>
