<?php
$skip_login = true;
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 5;
$page = 99;

if ($_GET['preview']) {
  setcookie("preview", "true", time()+60*60*24*7, "/");
}

$sql = false;
if ((int)$_GET['message'] > 0) {
  $type = (int)$_GET['message'];
  $sql = "WHERE EXISTS (SELECT * FROM tags_papers WHERE tags_papers.paper = papers.id AND tags_papers.tag = $type)";
}

$list = $db->query("SELECT papers.*, case_studies.* 
FROM case_studies 
  JOIN papers
  ON case_studies.paper = papers.id
  $sql 
ORDER BY papers.year, case_studies.name
");

if ($_GET['message'] == 'saved') {
  $print = "Information has been saved";
}

$studies = array(
  87 => "Ecological Footprint",
  85 => "EW MFA",
  54 => "LCA",
  65 => "PIOT",
  86 => "SFA",
);

$options = $db->query("SELECT a.*, t.name AS type_name, t.id AS type,
  (SELECT COUNT(*) FROM analysis WHERE analysis.analysis_option = a.id) AS total
FROM analysis_options_types t
  LEFT JOIN analysis_options a ON a.type = t.id
ORDER BY t.id, a.name");

if ($_GET['deleted']) {
  $print = "Case study was deleted";
}

$count = $db->record("SELECT COUNT(*) AS total FROM case_studies");
$count_indicators = $db->record("SELECT COUNT(*) AS total FROM analysis");
$count_studies = $db->query("SELECT DISTINCT case_study FROM analysis");
$count_per_study = $db->query("SELECT COUNT(*) AS total, case_study 
FROM analysis 
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
        <h3>Total Case Studies</h3>
        </hgroup>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-md-4">
    <div class="thumbnail alert alert-warning">
      <div class="caption">
      <hgroup>
        <h2><?php echo count($count_studies) ?></h2>
        <h3>Case Studies Processed</h3>
      </hgroup>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-md-4">
    <div class="thumbnail alert alert-success">
      <div class="caption">
      <hgroup>
        <h2><?php echo $count_indicators->total ?></h2>
        <h3>Total Indicators</h3>
      </hgroup>
      </div>
    </div>
  </div>
</div>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <p>
     At the open source Metabolism of Cities website we want to create a database with
     urban metabolism data and indicators. That is, we plan to examine a variety of
     research studies that have calculated particular values (material
     extraction, emissions, construction material use, imports, exports, etc.)
     for an urban/provincial region. 
     The metabolism indicators will also take into account energy, water, air
     pollution as well as urban characteristics indicators. 

     By creating one large masterlist of these
     values it is much easier for other researchers to see what values are
     out there and to compare their own data to other studies.
     We aim to do this at different spatial scales as well: region, city,
     municipalities, ... With this big masterlist it will therefore also become
     possible to identify indicators for resource use and pollution emission.
     </p>
     
     <p>
     Our final goal is to
     provide everyone in the community with the following tools:
   </p>

     <ul>
      <li>A centralized overview that makes it easy to explore a variety of studies with one single click</li>
      <li>Maps you can navigate to see where studies have been done and what the outcome was</li>
      <li>A consistently formatted database with research data, which can be viewed online or downloaded in CSV format</li>
      <li>Data visualizations that present the dataset in an attractive, easy-to-understand way</li>
      <li>Perhaps our own interesting insights after we have mined all this data! </li>
     </ul>

   <p>
     We need help identifying suitable studies, extracting relevant numbers,
     and presenting the data! We can use help in many forms, and would hereby
     like to invite anyone to join us. We don't work with strict deadlines and
     you can contribute as much or as little as you are able to. We welcome all
     support and hope to slowly but steadily assemble a global team of urban
     metabolism enthusiasts! 
   </p>

    <p>Specific tasks we need you help with include:</p>
    
    <ul>
      <li>Identification of useful research projects and other studies</li>
      <li>Extraction of relevant data and entry into our system</li>
      <li>Assistance with visualization of data</li>
      <li>Programming work to integrate support of all aforementioned functionality on our website</li>
      <li>Any other relevant task you can think of that enhances our project</li>
    </ul>

    <p>
      Are you interested? 
      <a href="page/contact">Get in touch!</a>
    </p>

    <h2>Initial list with relevant studies</h2>

  <div class="alert alert-info">
    <strong><?php echo count($list) ?></strong> studies found.
  </div>

  <ul class="nav nav-tabs">
    <li<?php if (!$type) { echo ' class="active"'; } ?>><a href="page/casestudies">All</a></li>
    <?php foreach ($studies as $key => $value) { ?>
      <li<?php if ($type == $key) { echo ' class="active"'; } ?>><a href="page/casestudies/<?php echo $key ?>"><?php echo $value ?></a></li>
    <?php } ?>
  </ul>

  <?php if ($list) { ?>

  <table class="table table-striped ellipsis">
    <tr>
      <th class="large">Region</th>
      <th class="small">Year</th>
      <th class="small">
      <span class="explanation" data-toggle="tooltip" data-placement="bottom"  title="This displays the total number of indicators that have been extracted from this study, so far">
        Quantity
        <i class="fa fa-question-circle"></i>
      </span>
      </th>
      <th class="large">Paper</th>
      <th class="large">Authors</th>
      <th class="small hide">Information</th>
    </tr>
  <?php foreach ($list as $row) { ?>
    <tr>
      <td><a href="casestudy/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
      <td><?php echo $row['year'] ?></td>
      <td><span class="badge badge-info"><?php echo (int)$study_count[$row['id']] ?></span></td>
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

  <?php if (defined("ADMIN")) { ?>
  <h2 id="meta">Meta Information</h2>

  <p><strong><?php echo $count_indicators->total ?></strong> records found in total</p>

  <?php if ($_GET['added']) { ?>

    <div class="alert alert-success">
      Information was saved
    </div>

  <?php } ?>

  <div class="optionlist">

  <?php $type = false; foreach ($options as $row) { ?>

    <?php if ($row['type_name'] != $type ) { ?>
      <?php if ($type) { ?>
        <p>
          <a href="admin.indicators.php?id=<?php echo $prevtype ?: $row['type']; ?>" class="btn btn-warning">
            <i class="fa fa-plus"></i>
            Add Type
          </a>
        </p>
      <?php } ?>

      <h3><?php echo $row['type_name'] ?></h3>
      <?php if ($type) { ?></ul>
      <?php } ?>
      <ul class="nav nav-list">
    <?php } $type = $row['type_name']; ?>

    <li>
      <a href="regional/options/<?php echo $row['id'] ?>/<?php echo flatten($row['name']) ?>">
        <?php echo $row['name'] ?>
        <span class="badge pull-right"><?php echo $row['total'] ?></span>
      </a>
    </li>

  <?php $prevtype = $row['type']; } ?>

  </ul>

  <p>
    <a href="admin.indicators.php?id=<?php echo $prevtype ?>" class="btn btn-warning">
      <i class="fa fa-plus"></i>
      Add Type
    </a>
  </p>

  </div>

  <h3>Manage Groups</h3>

  <p>
    <a href="admin.indicator.php" class="btn btn-warning">
      <i class="fa fa-plus"></i>
        Add Group
    </a>
  </p>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

<script type="text/javascript">
$(function(){
    $('[data-toggle="tooltip"]').tooltip({
      container: 'body'
    });
});
</script>

  </body>
</html>
