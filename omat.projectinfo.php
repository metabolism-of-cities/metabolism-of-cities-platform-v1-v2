<?php
require_once 'functions.php';
$disable_sidebar = true;
$public_login = true;
require_once 'functions.omat.php';
$section = 6;
$page = 4;

if ($_GET['data']) {
  setcookie("restricted_project_view", "true", time()+60*60*24*7, "/");
  $restricted_view = true;
}

if ($_GET['all']) {
  setcookie("restricted_project_view", false, time()-1, "/");
  $restricted_view = false;
}

$id = (int)$_GET['id'];

$info = $db->record("SELECT mfa_dataset.*, research.title AS research_name, mfa_dataset_types.name AS type, 
  papers.title AS paper_title
FROM mfa_dataset 
  LEFT JOIN research ON mfa_dataset.research_project = research.id
  LEFT JOIN papers ON mfa_dataset.source_paper = papers.id
  JOIN mfa_dataset_types ON mfa_dataset.type = mfa_dataset_types.id
WHERE mfa_dataset.id = $id");

if ($info->access == "private") {
  kill("No access");
} 

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->name ?> | <?php echo SITENAME ?></title>
    <?php if ($info->access == 'link_only') { ?>
      <meta name="robots" content="noindex" />      
    <?php } ?>
    <style type="text/css">
      dd { max-width:200px;white-space:nowrap; overflow:hidden; text-overflow: ellipsis; }
      dd,dt{padding-bottom:5px}
      ul.flatlist{list-style:none;padding-left:0}
      ul.flatlist li{margin-bottom:3px;padding-left:0}
      a.btn-success{margin-bottom:5px}
      div.well{width:auto;display:inline-block}
    </style>
  </head>

  <body class="omat">

<?php require_once 'include.header.php'; ?>
  
  <h1><i class="fa fa-database"></i> OMAT Dataset: <?php echo $info->name ?></h1>

  <?php if ($info->banner_text) { ?>
    <div class="alert alert-info info-bar">
      <i class="fa fa-info-circle"></i>
      <?php echo $info->banner_text ?>
    </div>
  <?php } ?>

  <div class="col-sm-6">
    <h2>Project Information</h2>
    <dl class="dl-horizontal">
      <dt>Dataset name</dt> 
      <dd><?php echo $info->name ?></dd>

      <dt>Type</dt>
      <dd><?php echo $info->type ?></dd>

      <?php if ($info->research_project) { ?>
        <dt>Project</dt>
        <dd><a href="research/<?php echo $info->research_project ?>"><?php echo $info->research_name ?></a></dd>
      <?php } ?>

      <dt>Period</dt>
      <dd><?php echo $info->year_start ?> <?php echo $info->year_end > $info->year_start ? " - " . $info->year_end : ''; ?></dd>

      <dt>Standard measure</dt>
      <dd><?php echo $info->measurement ?></dd>

    </dl>

  </div>

  <div class="col-sm-6">
    <h2>Reports</h2>
    <ul class="flatlist">
      <?php if (!$restricted_view) { ?>
        <li><a href="omat-public/<?php echo $id ?>/reports-indicators" class="btn btn-success"><i class="fa fa-bar-chart"></i> Indicators</a></li>
        <li><a href="omat-public/<?php echo $id ?>/reports-graphs" class="btn btn-success"><i class="fa fa-line-chart"></i> Graphs</a></li>
      <?php } ?>
      <li><a href="omat-public/<?php echo $id ?>/reports-tables" class="btn btn-success"><i class="fa fa-table"></i> Data Tables</a></li>
      <?php if ($info->resource_management) { ?>
      <li><a href="omat-public/<?php echo $id ?>/reports-sources" class="btn btn-success"><i class="fa fa-arrow-circle-down"></i> Data Sources</a></li>
      <?php } ?>
    </ul>
    <?php if ($info->source_paper) { ?>
      <div class="well">
        <i class="fa fa-info-circle"></i>
        Source publication: <br />
        <a href="publication/<?php echo $info->source_paper ?>">
          <?php echo $info->paper_title ?>
        </a>
      </div>
    <?php } ?>

  </div>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
