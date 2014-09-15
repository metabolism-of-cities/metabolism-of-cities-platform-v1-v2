<?php
require_once 'functions.php';
if ($_GET['material']) {
  $material = (int)$_GET['material'];
  $getproject = $db->record("SELECT g.dataset, g.name, g.id AS groupid FROM mfa_materials m JOIN mfa_groups g ON m.mfa_group = g.id WHERE m.id = $material");
  $project = $getproject->dataset;
} elseif ($_GET['id']) {
  $id = (int)$_GET['id'];
  $getproject = $db->record("SELECT g.dataset, g.name, g.id AS groupid 
  FROM mfa_data d
  JOIN mfa_materials m ON d.material = m.id
  JOIN mfa_groups g ON m.mfa_group = g.id 
  WHERE d.id = $id");
  $project = $getproject->dataset;
  $projectinfo = $db->record("SELECT * FROM mfa_dataset WHERE id = $project");
}
require_once 'functions.omat.php';

$load_menu = 1;
$sub_page = 1;
$section = 6;
$page = 2;

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM mfa_data WHERE id = $id");

if ($_GET['material']) {
  $material = (int)$_GET['material'];
} else {
  $material = $info->material;
}

$materialinfo = $db->record("SELECT mfa_materials.*, mfa_groups.dataset 
FROM mfa_materials 
  JOIN mfa_groups ON mfa_materials.mfa_group = mfa_groups.id
WHERE mfa_materials.id = $material");

if ($_POST) {
  $remove_commas = array(',' => '');
  $_POST['data'] = strtr($_POST['data'], $remove_commas);
  $post = array(
    'material' => $material,
    'year' => (int)$_POST['year'],
    'data' => (float)$_POST['data'],
    'comments' => html($_POST['comments']),
    'source' => $_POST['source'] ? html($_POST['source']) : NULL,
    'source_link' => $_POST['source_link'] ? html($_POST['source_link']) : NULL,
    'source_id' => $_POST['source_id'] ? (int)$_POST['source_id'] : NULL,
    'scale' => $_POST['scale'] ? (int)$_POST['scale'] : NULL,
  );
  if ($id) {
    $db->update("mfa_data",$post,"id = $id");
  } else {
    $db->insert("mfa_data",$post);
    $id = $db->lastInsertId();
  }
  $db->query("DELETE FROM mfa_dqi WHERE data = $id");
  if (is_array($_POST['quality'])) {
    foreach ($_POST['quality'] as $key => $value) {
      $quality = (int)$value;
      if ($quality) {
        $post = array(
          'classification' => $quality,
          'data' => $id,
        );
        $db->insert("mfa_dqi",$post);
      }
    } 
  }
  header("Location: " . URL . "omat/data/$material/saved");
  exit();
}

$project = $materialinfo->dataset;
$projectinfo = $db->record("SELECT * FROM mfa_dataset WHERE id = {$materialinfo->dataset}");

if ($projectinfo->multiscale) {
  $scales = $db->query("SELECT * FROM mfa_scales WHERE dataset = {$materialinfo->dataset}");
  if (!count($scales)) {
    $warning = "Your project is set to include multiple scales, but you have not yet defined them. Please change 
    these settings <a href='omat/$project/maintenance-scales'>in your dashboard</a>";
  }
} 

if ($projectinfo->contact_management) {
  $source_management = true;
  $sources = $db->query("SELECT * FROM mfa_sources WHERE dataset = $project ORDER BY name");
  if (!count($sources)) {
    $warning = "Your project is set to track contacts and sources, but you have not yet entered any sources. 
    In order to properly link this data point to a source, be sure to add a
    <a href='omat/$project/sources'>source</a> first.";
  }
} 

if ($projectinfo->dqi) {
  $dqi = $db->query("SELECT dqi_classifications.*, dqi_sections.name AS section_name
  FROM dqi_classifications
    JOIN dqi_sections ON dqi_classifications.section = dqi_sections.id
  WHERE dqi_sections.dataset = {$materialinfo->dataset}
  ORDER BY dqi_sections.name, dqi_classifications.score");
  if (!count($dqi)) {
    $warning = "Your product is configured to use Data Quality Indicators, but you have not yet defined them. Be sure to define them!";
  }
  $dqi_active = $db->query("SELECT classification FROM mfa_dqi WHERE data = $id");
  foreach ($dqi_active as $row) {
    $active[$row['classification']] = true;
  }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $id ? "Edit" : "Add" ?> Data Point | <?php echo SITENAME ?></title>
    <style type="text/css">
    #dqi .btn{opacity:0.6}
    #dqi .active{opacity:1}
    a.reset{position:relative;left:10px;top:5px}
    </style>
    <script type="text/javascript">
    $(function(){
      $(".tooltip").tooltip({
        container: 'body' 
      });
      $(".btn").button();
      $(".reset").click(function(e){
        e.preventDefault();
        var section = $(this).data("section");
        $(".group-"+section+" input").attr('checked', false);
        $(".group-"+section+" label").removeClass("active");
      });
    });
    </script>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1><?php echo $id ? "Edit" : "Add" ?> Data Point</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/manage">Data</a></li>
    <li><a href="omat/datagroup/<?php echo $getproject->groupid ?>"><?php echo $getproject->name ?></a></li>
    <li><a href="omat/data/<?php echo $materialinfo->id ?>"><?php echo $materialinfo->name ?></a></li>
    <li class="active"><?php echo $id ? "Edit" : "Add" ?> Data Point</li>
  </ol>

  <?php if ($warning) { echo "<div class=\"alert alert-warning\">$warning</div>"; } ?>

  <form method="post" class="form form-horizontal">

    <div class="form-group">
      <label class="col-sm-2 control-label">Year</label>
      <div class="col-sm-3">
        <input class="form-control" type="number" name="year" value="<?php echo $info->year ?>" required />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Quantity</label>
      <div class="col-sm-3">
        <div class="input-group">
          <input class="form-control" type="text" name="data" value="<?php echo $id ? number_format($info->data, $projectinfo->decimal_precision) : ''; ?>" required />
          <span class="input-group-addon"><?php echo $projectinfo->measurement ?></span>
        </div>
      </div>
    </div>

    <?php if ($projectinfo->multiscale) { ?>

      <div class="form-group">
        <label class="col-sm-2 control-label">Scale</label>
        <div class="col-sm-10">
          <select name="scale" class="form-control" required>
            <option value=""></option>
            <?php foreach ($scales as $row) { ?>
              <option value="<?php echo $row['id'] ?>"<?php if ($row['id'] == $info->scale) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
            <?php } ?>
          </select>
        </div>
      </div>

    <?php } ?>

    <?php if ($source_management) { ?>

      <div class="form-group">
        <label class="col-sm-2 control-label">Source</label>
        <div class="col-sm-10">
          <select name="source_id" class="form-control" required>
            <option value=""></option>
            <?php foreach ($sources as $row) { ?>
              <option value="<?php echo $row['id'] ?>"<?php if ($row['id'] == $info->source_id) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
            <?php } ?>
          </select>
        </div>
      </div>

    <?php } else { ?>

      <div class="form-group">
        <label class="col-sm-2 control-label">Source Name</label>
        <div class="col-sm-10">
          <input class="form-control" type="text" name="source" value="<?php echo $info->source ?>" />
        </div>
      </div>
    
      <div class="form-group">
        <label class="col-sm-2 control-label">Source Link</label>
        <div class="col-sm-10">
          <input class="form-control" type="url" name="source_link" value="<?php echo $info->source_link ?>" />
        </div>
      </div>
  
    <?php } ?>

    <div class="form-group">
      <label class="col-sm-2 control-label">Comments</label>
      <div class="col-sm-10">
        <textarea class="form-control" name="comments"><?php echo br2nl($info->comments) ?></textarea>
      </div>
    </div>

    <?php if (count($dqi)) { ?>
      <fieldset id="dqi">
        <legend>Data Quality Indicators</legend>

        <?php $dqisection = false; foreach ($dqi as $row) { ?>
          <?php if ($row['section'] != $dqi_section) { ?>
          <?php if ($dqi_section) { ?>
          <a href="#reset" data-section="<?php echo $dqi_section ?>" class="reset" data-toggle="tooltip" title="Reset value"><i class="fa fa-ban"></i></a>
          </div>
          </div>
          </div><?php } ?>
          <div class="row">
            <div class="col-sm-3">
              <?php echo $row['section_name'] ?>
            </div>
            <div class="col-sm-9">
              <div class="btn-group group-<?php echo $row['section'] ?>" data-toggle="buttons">
          <?php } $dqi_section = $row['section']; ?>
          <label class="tooltip btn btn-default <?php echo $active[$row['id']] ? "active" : ''; ?>" data-toggle="tooltip" title="<?php echo $row['name'] ?>" data-placement="right">
            <input type="radio" id="q<?php echo $row['id'] ?>" name="quality[<?php echo $row['section'] ?>]" <?php echo $active[$row['id']] ? 'checked' : ''; ?> value="<?php echo $row['id'] ?>"> <?php echo $row['score'] ?>
          </label>
        <?php } ?>
          <a href="#reset" data-section="<?php echo $dqi_section ?>" class="reset" data-toggle="tooltip" title="Reset value"><i class="fa fa-ban"></i></a>
        </div>
        </div>
        </div>
        
      </fieldset>
    <?php } ?>

    <?php if (count($dqi)) { ?>
        <button type="submit" class="btn btn-primary">Save</button>
    <?php } else { ?>
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
    <?php } ?>

  </form>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
