<?php
$show_breadcrumbs = true;
$skip_third_level = true; // Hide empty third level from breadcrumbs
$skip_login = true;
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 5;
$page = 2;

if ($_POST) {
  if ($_POST['search'] == "area" && $_POST['area']) {
    $area = (int)$_POST['area'];
    $subarea = (int)$_POST['subarea'];
    if ($subarea) {
      header("Location: " . URL . "data/subareas/$subarea");
      exit();
    } else {
      header("Location: " . URL . "data/areas/$area");
      exit();
    }
  } elseif ($_POST['search'] == "city" && $_POST['city']) {
      $city = (int)$_POST['city'];
      header("Location: " . URL . "casestudy/$city");
      exit();
  }
}

$sql = false;

if ($_GET['message'] == 'saved') {
  $print = "Information has been saved";
}

$options = array(
  "overview" => "Data Overview",
  "indicators" => "Indicators",
  "filters" => "Filter Data",
  "download" => "Download Data",
  //"map" => "Map",
);

if (!$_GET['message']) {
  $page = "overview";
  $_GET['message'] = 'overview';
}

if ($_GET['message'] == "downloadnow") {
  $_GET['message'] = 'download';
  $warning = "We are currently working on the DOWNLOAD functionality. Please check back within 1-2 days (Jun 19-20, 2017)";
}

$this_page = $options[$_GET['message']];

if ($options[$_GET['message']]) {
  $page = $_GET['message'];
}

if ($_GET['deleted']) {
  $print = "Case study was deleted";
}

if ($page == "overview") {
$list = $db->query("SELECT papers.*, case_studies.* 
FROM case_studies 
  JOIN papers
  ON case_studies.paper = papers.id
  $sql 
ORDER BY case_studies.name, papers.year
");
} elseif ($page == "indicators") {
  $list = $db->query("SELECT 
  i.*, a.name AS area, s.name AS subarea,
  s.id AS subarea_id,
  a.id AS area_id,
  (SELECT COUNT(*) FROM data WHERE indicator = i.id) AS total
  FROM data_indicators i
  JOIN data_subareas s ON i.subarea = s.id
  JOIN data_areas a ON s.area = a.id
  ORDER BY a.name, s.name, i.name
  ");
} elseif ($page == "filters") {
  $subareas = $db->query("SELECT * FROM data_subareas ORDER BY area, name");
  $areas = $db->query("SELECT * FROM data_areas ORDER BY name");
  $papers = $db->query("SELECT DISTINCT papers.id, papers.title 
  FROM case_studies 
    JOIN papers
    ON case_studies.paper = papers.id
  ORDER BY case_studies.name, papers.year
  ");
  $cities = $db->query("SELECT DISTINCT case_studies.name, case_studies.id
  FROM case_studies 
  ORDER BY name
  ");
}


$count = $db->record("SELECT COUNT(*) AS total FROM case_studies");
$count_indicators = $db->record("SELECT COUNT(*) AS total FROM data_indicators");
$total_count = $db->record("SELECT COUNT(*) AS total FROM data");
$count_per_study = $db->query("SELECT COUNT(*) AS total, case_study 
FROM data 
GROUP BY case_study
");

foreach ($count_per_study as $row) {
  $study_count[$row['case_study']] = $row['total'];
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Material Flow Analysis Case Studies | <?php echo SITENAME ?></title>
    <style type="text/css">
    .table.ellipsis{border-top:0}
    .table > tbody > tr > th {border-top:0}
    .optionlist{max-width:500px}
    hgroup h2{font-size:29px;margin:0}
    hgroup h3{font-size:15px;margin:0}
    .explanation{border-bottom:2px dotted #999}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Global Urban Metabolism Data</h1>

    
<div class="row">
  <div class="col-sm-6 col-md-4">
    <div class="thumbnail alert alert-info">
      <div class="caption">
        <hgroup>
        <h2><?php echo $count->total ?></h2>
        <h3>Case Studies</h3>
        </hgroup>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-md-4">
    <div class="thumbnail alert alert-warning">
      <div class="caption">
      <hgroup>
        <h2><?php echo $count_indicators->total ?></h2>
        <h3>Total Indicators</h3>
      </hgroup>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-md-4">
    <div class="thumbnail alert alert-success">
      <div class="caption">
      <hgroup>
        <h2><?php echo number_format($total_count->total,0) ?></h2>
        <h3>Data Points</h3>
      </hgroup>
      </div>
    </div>
  </div>
</div>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <ul class="nav nav-tabs">
    <?php foreach ($options as $key => $value) { ?>
      <li class="nav-item<?php if ($page == $key) { echo ' active'; } ?>"><a class="nav-link" href="page/casestudies/<?php echo $key ?>"><?php echo $value ?></a></li>
    <?php } ?>
  </ul>

  <?php if ($page == "overview") { ?>

  <table class="table table-striped ellipsis">
    <tr>
      <th class="large">City</th>
      <th class="small">
      <span class="explanation" data-toggle="tooltip" data-placement="bottom"  title="This displays the total number of indicators that have been extracted from this study, so far">
        Quantity
        <i class="fa fa-question-circle"></i>
      </span>
      </th>
      <th class="small">Published</th>
      <th class="large">Publication</th>
      <th class="large">Authors</th>
      <th class="small hide">Information</th>
    </tr>
  <?php foreach ($list as $row) { ?>
    <tr>
      <td><a href="casestudy/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
      <td><a class="badge badge-info" href="casestudy/<?php echo $row['id'] ?>"><?php echo (int)$study_count[$row['id']] ?></a></td>
      <td><?php echo $row['year'] ?></td>
      <td><a href="publication/<?php echo $row['paper'] ?>"><?php echo $row['title'] ?></a></td>
      <td><?php echo $row['author'] ?></td>
      <td class="hide">
        <a href="analysis/<?php echo $row['id'] ?>/2"><i class="fa fa-comments-o"></i></a>
        <a href="analysis/<?php echo $row['id'] ?>/1"><i class="fa fa-bar-chart-o"></i></a>
        <a href="analysis/<?php echo $row['id'] ?>/3"><i class="fa fa-user"></i></a>
      </td>
    </tr>
  <?php } ?>
  </table>

  <?php } ?>

  <?php if ($page == "indicators") { ?>

  <table class="table table-striped ellipsis">
    <tr>
      <th class="small">Area</th>
      <th class="small">Sub-area</th>
      <th class="large">Indicator</th>
      <th class="small">
      <span class="explanation" data-toggle="tooltip" data-placement="bottom"  title="This displays the total number of indicators that have been extracted from this study, so far">
        Quantity
        <i class="fa fa-question-circle"></i>
      </span>
      </th>
    </tr>
  <?php foreach ($list as $row) { ?>
    <tr>
      <td><a href="data/areas/<?php echo $row['area_id'] ?>"><?php echo $row['area'] ?></a></td>
      <td><a href="data/subareas/<?php echo $row['subarea_id'] ?>"><?php echo $row['subarea'] ?></a></td>
      <td><a href="data/indicators/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
      <td><a class="badge badge-info" href="data/indicators/<?php echo $row['id'] ?>"><?php echo (int)$row['total'] ?></a></td>
    </tr>
  <?php } ?>
  </table>

  <?php } elseif ($page == "filters") { ?>


    <h2>Configure your filters below</h2>
    <form method="post" class="form form-horizontal">
    
      <fieldset>
        <legend>Filter by area</legend>

        <div class="form-group">
          <label class="col-sm-2 control-label">Area</label>
          <div class="col-sm-10">
            <select name="area" class="form-control">
                <option value=""></option>
                <?php foreach ($areas as $row) { ?>
                <option value="<?php echo $row['id'] ?>"<?php if ($row['id'] == $info->area) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        
        <div class="form-group" id="subareas">
          <label class="col-sm-2 control-label">Sub-area</label>
          <div class="col-sm-10">
            <select name="subarea" class="form-control">
                <option value="" class="all">All</option>
                <?php foreach ($subareas as $row) { ?>
                <option class="area-<?php echo $row['area'] ?>" value="<?php echo $row['id'] ?>"<?php if ($row['id'] == $info->area) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
              <?php } ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary" name="search" value="area">Search</button>
          </div>
        </div>
        
      </fieldset>

      <fieldset>
        <legend>Filter by city</legend>

        <div class="form-group">
          <label class="col-sm-2 control-label">City</label>
          <div class="col-sm-10">
            <select name="city" class="form-control">
                <option value=""></option>
                <?php foreach ($cities as $row) { ?>
                <option value="<?php echo $row['id'] ?>"<?php if ($row['name'] == $info->city) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary" name="search" value="city">Search</button>
          </div>
        </div>
        
      </fieldset>


      <fieldset class="hide">
        <legend>Filter by publication</legend>

        <div class="form-group">
          <label class="col-sm-2 control-label">Publication</label>
          <div class="col-sm-10">
            <select name="paper" class="form-control">
                <option value=""></option>
                <?php foreach ($papers as $row) { ?>
                <option value="<?php echo $row['id'] ?>"<?php if ($row['title'] == $info->title) { echo ' selected'; } ?>><?php echo $row['title'] ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
               
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary" name="search" value="paper">Search</button>
          </div>
        </div>
 
      </fieldset>

    </form>

  <?php } elseif ($page == "download") { ?>


    <div style="padding:10px">
    <h1>Data Download</h1>
    <?php if ($warning) { echo "<div class=\"alert alert-warning\">$warning</div>"; } ?>
    <p>
      On this page you can download the data in our database. This data is available in CSV format and can 
      be downloaded by the click of a button. If you use the data, make sure to reference the original publication
      (listed in the data overview).
    </p>

    <p>
      <a href="data/download" class="btn btn-primary btn-lg"><i class="fa fa-download"></i> Download data</a>
    </p>

    </div>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

<script type="text/javascript">
$(function(){
    $('[data-toggle="tooltip"]').tooltip({
      container: 'body'
    });

    $("#subareas").hide();
    $("select[name='area']").change(function(){
      var id = $(this).val();
      $("#subareas").show();
      $("#subareas option").hide();
      $("#subareas .all").show();
      $("#subareas .area-"+id).show();
      console.log("showing .area-"+id);
    });
});
</script>

  </body>
</html>
