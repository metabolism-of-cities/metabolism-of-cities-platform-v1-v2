<?php
require_once 'functions.php';
$disable_sidebar = true;
require_once 'functions.omat.php';
$section = 6;
$page = 4;

$id = (int)$_GET['id'];

$info = $db->record("SELECT mfa_dataset.*, research.title AS research_name, mfa_dataset_types.name AS type
FROM mfa_dataset 
  LEFT JOIN research ON mfa_dataset.research_project = research.id
  JOIN mfa_dataset_types ON mfa_dataset.type = mfa_dataset_types.id
WHERE mfa_dataset.id = $id");

$data = $db->query("SELECT 
  COUNT(*) AS total
FROM mfa_materials
  JOIN mfa_groups ON mfa_materials.mfa_group = mfa_groups.id
WHERE mfa_groups.dataset = $id");

$groups = $db->record("SELECT 
  COUNT(*) AS total
FROM mfa_groups WHERE mfa_groups.dataset = $id");

$log = $db->query("SELECT mfa_data.date, mfa_data.year, mfa_materials.name, mfa_data.material
  FROM mfa_data 
    JOIN mfa_materials ON mfa_data.material = mfa_materials.id
    JOIN mfa_groups ON mfa_materials.mfa_group = mfa_groups.id
WHERE mfa_groups.dataset = $id
ORDER BY mfa_data.date DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>OMAT Dashboard | <?php echo SITENAME ?></title>
    <style type="text/css">
      dd { max-width:200px;white-space:nowrap; overflow:hidden; text-overflow: ellipsis; }
      dd,dt{padding-bottom:5px}
      ul.flatlist{list-style:none;padding-left:0}
      ul.flatlist li{margin-bottom:3px;padding-left:0}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>
  
  <div class="navbar hide">
    <div class="navbar-inner">
      <ul class="nav">
        <li class="active"><a href="omat/dashboard/<?php echo $id ?>"><?php echo $info->name ?>: Dashboard</a></li>
      </ul>
    </div>
  </div>

  <h1><i class="fa fa-database"></i> OMAT Dataset: <?php echo $info->name ?></h1>

  <div class="alert alert-info">
    From this dashboard you can manage your dataset. 
  </div>

  <div class="col-sm-6">
    <h2>Your Data</h2>
    <?php if ($data->total || $groups->total) { ?>
      <p>You have 
        <strong><?php echo $groups->total ?></strong> data <?php echo $groups->total == 1 ? "group" : "groups" ?>, with a total of
      <strong><?php echo $data->total ?></strong> material <?php echo $data->total == 1 ? "classification" : "classifications" ?> in your dataset.</p>
      <p><a href="omat/<?php echo $id ?>/manage" class="btn btn-success btn-lg">
      <i class="fa fa-pencil"></i>
      Manage your data</a></p>
      <p>
        <?php if ($info->contact_management) { ?>
          <a href="omat/<?php echo $id ?>/contacts" class="btn btn-success"><i class="fa fa-user"></i> Manage Contacts</a>
          <a href="omat/<?php echo $id ?>/sources" class="btn btn-success"><i class="fa fa-link"></i> Manage Sources</a>
          <a href="omat/<?php echo $id ?>/files" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Files</a>
        <?php } ?>
      </p>
    <?php } else { ?>
      <p>You do not yet have any data in your dataset.</p>
      <ul class="flatlist">
        <li><a href="omat/<?php echo $id ?>/load-eurostat" class="btn btn-primary">Load EUROSTAT data groups</a></li>
        <li><a href="omat/datagroup-entry/dataset-<?php echo $id ?>" class="btn btn-primary">Load an empty skeleton</a></li>
      </ul>
      <p>Choose the EUROSTAT data groups if you want to load the data groups as specified in the 
      EUROSTAT framework. The groups are loaded from the <a href="publication/164">2013 Compilation Guide</a>.
      </p>
      <p>If you want to use other data groups, load an empty skeleton and you can specify the data groups that
      you want to manage within your dataset.</p>
    <?php } ?>

    <?php if (count($log)) { ?>
    <h2>Log</h2>
    <table class="table table-striped">
      <tr>
        <th>Date</th>
        <th>Material</th>
        <th>Action</th>
      </tr>
      <?php foreach ($log as $row) { ?>
      <tr>
        <td><?php echo format_date("M d, Y", $row['date']) ?></td>
        <td><a href="omat/data/<?php echo $row['material'] ?>"><?php echo $row['name'] ?></a></td>
        <td>Value for <?php echo $row['year'] ?> added.</td>
      </tr>
      <?php } ?>
    </table>
    <p><a href="omat/<?php echo $project ?>/log" class="btn btn-primary"><i class="fa fa-align-justify"></i> View full log</a></p>
    <?php } ?>

    <?php if (count($omat_menu[2]['menu'])) { ?>
    <h2>Maintenance</h2>
    <ul class="nav nav-pills nav-stacked">
      <?php foreach ($omat_menu[2]['menu'] as $value) { ?> 
        <li><a href="<?php echo $value['url'] ?>"><i class="fa fa-<?php echo $value['icon'] ?>"></i> <?php echo $value['label'] ?></a></li>
      <?php } ?>
    </ul>
    <?php } ?>

  </div>

  <div class="col-sm-6">
    <h2>Reports</h2>
    <p>
      <a href="omat/<?php echo $id ?>/reports-dataoverview" class="btn btn-success"><i class="fa fa-list"></i> Data Overview</a>
      <a href="omat/<?php echo $id ?>/reports-indicators" class="btn btn-success"><i class="fa fa-bar-chart"></i> Indicators</a>
    </p>


    <h2>Your Settings</h2>
    <dl class="dl-horizontal">
      <dt>Dataset name</dt> 
      <dd><?php echo $info->name ?></dd>

      <dt>Type</dt>
      <dd><?php echo $info->type ?></dd>

      <dt>Access</dt>
      <dd><?php echo $info->access ?></dd>

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
    <p><a href="omat/edit/<?php echo $id ?>" class="btn btn-primary"><i class="fa fa-cog"></i> Change settings</a></p>

    <h2>Other Options</h2>
     <p>
        <a href="omat.dataset.php?id=<?php echo $id ?>&amp;reset=true" onclick="javascript:return confirm('Are you sure? All your data will be removed!')" class="btn btn-danger"><i class="fa fa-refresh"></i> Reset dataset</a></li>
      <p><a href="omat.dataset.php?id=<?php echo $id ?>&amp;delete=true" onclick="javascript:return confirm('Are you sure?')" class="btn btn-danger" />
    <i class="fa fa-trash-o fa-lg"></i>
    Delete this dataset</a></p>
  </div>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
