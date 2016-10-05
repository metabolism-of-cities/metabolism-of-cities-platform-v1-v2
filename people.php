<?php
require_once 'functions.php';
$section = 4;
$page = 8;

$list = $db->query("SELECT 
  people.*,
  (SELECT COUNT(*) FROM people_papers WHERE people.id = people_papers.people) AS publications
FROM people 
WHERE active IS TRUE
ORDER BY lastname");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Authors | <?php echo SITENAME ?></title>
    <script type="text/javascript" src="js/tablesorter.js"></script>
    <script type="text/javascript" src="js/tablesorter.widgets.js"></script>
    <link rel="stylesheet" href="css/sorter.bootstrap.css" />
    </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Authors</h1>

  <p>
    This section lists authors who do (or have done) research into the metabolism of cities.
    This list is associated with our database of publications and allows you to review 
    the publications by each of the authors in this list. Each author can edit her/his 
    profile by requesting an access link via e-mail (use the link at the bottom of your
    personal profile). For questions, please don't hesitate to <a href="info/contact">contact us</a>.
  </p>

  <div class="alert alert-info">
    There are a total of <strong><?php echo count($list) ?></strong> authors in the database.
  </div>

  <table class="table table-striped">
    <thead>
    <tr>
      <th>First name</th>
      <th>Last name</th>
      <th>Publications</th>
      <th>Profile</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list as $row) { ?>
    <tr>
      <td><?php echo $row['firstname'] ?></td>
      <td><?php echo $row['lastname'] ?></td>
      <td><?php echo $row['publications'] ?></td>
      <td><a href="people/<?php echo $row['id'] ?>-<?php echo flatten($row['firstname']. " ".$row['lastname']) ?>">View profile</a></td>
    </tr>
  <?php } ?>
    </tbody>
  </table>

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
