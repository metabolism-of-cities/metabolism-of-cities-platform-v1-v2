<?php
require_once 'functions.php';
$disable_sidebar = true;
$public_login = true;
require_once 'functions.omat.php';
$section = 6;
$page = 4;

$id = (int)$_GET['id'];

$info = $db->record("SELECT mfa_dataset.*, research.title AS research_name, mfa_dataset_types.name AS type
FROM mfa_dataset 
  LEFT JOIN research ON mfa_dataset.research_project = research.id
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
    <style type="text/css">
      dd { max-width:200px;white-space:nowrap; overflow:hidden; text-overflow: ellipsis; }
      dd,dt{padding-bottom:5px}
      ul.flatlist{list-style:none;padding-left:0}
      ul.flatlist li{margin-bottom:3px;padding-left:0}
      a.btn-success{margin-bottom:5px}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>
  
  <h1><i class="fa fa-database"></i> OMAT Dataset: <?php echo $info->name ?></h1>

  <?php if ($info->banner_text) { ?>
    <div class="alert alert-info">
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

      <dt>Decimal precision</dt>
      <dd><?php echo $info->decimal_precision ?></dd>

      <dt>Standard measure</dt>
      <dd><?php echo $info->measurement ?></dd>

    </dl>

  </div>

  <div class="col-sm-6">
    <h2>Reports</h2>
    <ul class="flatlist">
      <li><a href="omat-public/<?php echo $id ?>/reports-indicators" class="btn btn-success"><i class="fa fa-bar-chart"></i> Indicators</a></li>
      <li><a href="omat-public/<?php echo $id ?>/reports-graphs" class="btn btn-success"><i class="fa fa-line-chart"></i> Graphs</a></li>
      <li><a href="omat-public/<?php echo $id ?>/reports-tables" class="btn btn-success"><i class="fa fa-table"></i> Data Tables</a></li>
    </ul>

  </div>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
