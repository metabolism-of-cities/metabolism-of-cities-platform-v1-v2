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
    <script type="text/javascript" src="js/tablesorter.js"></script>
    <script type="text/javascript" src="js/tablesorter.widgets.js"></script>
    <link rel="stylesheet" href="css/sorter.bootstrap.css" />
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

<h1><?php echo ID == 2 ? "EPR" : "Urban Metabolism"; ?> Publications</h1>

<?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

<?php if (defined("ADMIN") && $info->tag) { ?>

  <form method="post" class="form form-horizontal">
  
    <div class="form-group">
      <label class="col-sm-2 control-label">Tag</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="tag" value="<?php echo $info->tag ?>" />
      </div>
    </div>

    <div class="form-group <?php echo $info->parent == $gps_tagged ? "regular" : "hide"; ?>">
      <label class="col-sm-2 control-label">GPS coordinates</label>
      <div class="col-sm-7">
        <input class="form-control" type="text" name="gps" value="<?php echo $info->gps ?>" placeholder="Enter LONG, LAT - e.g. 2.3488, 48.8534 for Paris" />
      </div>
      <div class="col-sm-2">
<a class="btn btn-info" target="_blank" href="http://itouchmap.com/latlong.html">Get the GPS coordinates here</a>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Description</label>
      <div class="col-sm-10">
        <textarea class="form-control" name="description"><?php echo br2nl($info->description) ?></textarea>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Group</label>
      <div class="col-sm-10">
        <select name="parent" class="form-control">
          <?php foreach ($parent_tag_list as $row) { ?>
            <option value="<?php echo $row['id'] ?>"<?php if ($row['id'] == $info->parent) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
          <?php } ?>
        </select>
        <input type="hidden" name="update_tag" value="<?php echo $tag ?>" />
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>

  </form>

<?php } ?>

<p>
  This section provides a collection of papers related to Material Flow Analysis. These papers have been 
  found in a variety of journals, as well as in collections from EUROSTAT, universities, and research 
  institutes. If you are looking for a specific topic, then consider using our <a href="publications/search">search engine</a>
  which allows you to quickly filter through the database. 
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
<?php } else { ?>

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

  <table class="table table-striped">
    <thead>
    <tr>
      <th>Title</th>
      <th width="330">Author(s)</th>
      <th width="80">Year</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list as $row) { ?>
      <tr>
        <td><a href="publication/<?php echo $row['id'] ?>"><?php echo $row['title'] ?></a></td>
        <td><?php echo $row['author'] ?></td>
        <td><?php echo $row['year'] ?></td>
      </tr>
    <?php } ?>
    </tbody>
  </table>

  <div class="alert alert-info">
    <a href="publications.export.php" class="btn btn-default">
      <i class="fa fa-table"></i>
      Download
    </a>
    You can download the full publications database including publication title, author(s), year, journal, tags, etc. as a <strong>CSV file</strong>.
  </div>


<?php } ?>

<?php require_once 'include.footer.php'; ?>

<script type="text/javascript">
$(function(){

	// NOTE: $.tablesorter.theme.bootstrap is ALREADY INCLUDED in the jquery.tablesorter.widgets.js
	// file; it is included here to show how you can modify the default classes
	$.tablesorter.themes.bootstrap = {
		// these classes are added to the table. To see other table classes available,
		// look here: http://getbootstrap.com/css/#tables
		table        : 'table table-bordered table-striped',
		caption      : 'caption',
		// header class names
		header       : 'bootstrap-header', // give the header a gradient background (theme.bootstrap_2.css)
		sortNone     : '',
		sortAsc      : '',
		sortDesc     : '',
		active       : '', // applied when column is sorted
		hover        : '', // custom css required - a defined bootstrap style may not override other classes
		// icon class names
		icons        : '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
		iconSortNone : 'bootstrap-icon-unsorted', // class name added to icon when column is not sorted
		iconSortAsc  : 'glyphicon glyphicon-chevron-up', // class name added to icon when column has ascending sort
		iconSortDesc : 'glyphicon glyphicon-chevron-down', // class name added to icon when column has descending sort
		filterRow    : '', // filter row class; use widgetOptions.filter_cssFilter for the input/select element
		footerRow    : '',
		footerCells  : '',
		even         : '', // even row zebra striping
		odd          : ''  // odd row zebra striping
	};

	// call the tablesorter plugin and apply the uitheme widget
	$("table").tablesorter({
		// this will apply the bootstrap theme if "uitheme" widget is included
		// the widgetOptions.uitheme is no longer required to be set
		theme : "bootstrap",

		widthFixed: true,

		headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

		// widget code contained in the jquery.tablesorter.widgets.js file
		// use the zebra stripe widget if you plan on hiding any rows (filter widget)
		widgets : [ "uitheme", "filter", "zebra" ],

		widgetOptions : {
			// using the default zebra striping class name, so it actually isn't included in the theme variable above
			// this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
			zebra : ["even", "odd"],

			// reset filters button
			filter_reset : ".reset",

			// extra css class name (string or array) added to the filter element (input or select)
			filter_cssFilter: "form-control",

			// set the uitheme widget to use the bootstrap theme class names
			// this is no longer required, if theme is set
			// ,uitheme : "bootstrap"

		}
	})

});
</script>

  </body>
</html>
