<?php
require_once 'functions.php';

if ($_GET['material']) {
  $material = (int)$_GET['material'];
  $getproject = $db->record("SELECT g.dataset, g.name, g.id AS groupid FROM mfa_materials m JOIN mfa_groups g ON m.mfa_group = g.id WHERE m.id = $material");
  $project = $getproject->dataset;
  $projectinfo = $db->record("SELECT * FROM mfa_dataset WHERE id = $project");
  $years = range($projectinfo->year_start, $projectinfo->year_end);
} elseif ($_GET['id']) {
  $id = (int)$_GET['id'];
  $getproject = $db->record("SELECT g.dataset, g.name, g.id AS groupid 
  FROM mfa_data d
  JOIN mfa_materials m ON d.material = m.id
  JOIN mfa_groups g ON m.mfa_group = g.id 
  WHERE d.id = $id");
  $project = $getproject->dataset;
  if (!$project) {
    die("Data point not found");
  }
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
  // Only if this project uses multiple scales as proxies do we want
  // a multiplier factor that is different from 1.
  if (!$projectinfo->multiscale || !$projectinfo->multiscale_as_proxy) {
    $_POST['multiplier'] = 1;
  }
  if (is_array($_POST['data'])) {
    $remove_commas = array(',' => '');
    foreach ($_POST['data'] as $year => $data) {
      if (strlen($data) > 0) { 
        $data = strtr($data, $remove_commas);
        $post = array(
          'material' => $material,
          'year' => (int)$year,
          'data' => (float)$data,
          'comments' => html($_POST['comments']),
          'source' => $_POST['source'] ? html($_POST['source']) : NULL,
          'source_link' => $_POST['source_link'] ? html($_POST['source_link']) : NULL,
          'source_id' => $_POST['source_id'] ? (int)$_POST['source_id'] : NULL,
          'scale' => $_POST['scale'] ? (int)$_POST['scale'] : NULL,
          'multiplier' => $_POST['multiplier'] == "own" ? (float)$_POST['own_multiplier'] : (float)$_POST['multiplier'],
        );
        $db->insert("mfa_data",$post);
        $id = $db->lastInsertId();

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
      }
    }
  } else {
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
      'multiplier' => $_POST['multiplier'] == "own" ? (float)$_POST['own_multiplier'] : (float)$_POST['multiplier'],
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
  }
  header("Location: " . URL . "omat/data/$material/saved");
  exit();
}

$project = $materialinfo->dataset;
$projectinfo = $db->record("SELECT * FROM mfa_dataset WHERE id = {$materialinfo->dataset}");

if ($projectinfo->multiscale) {
  $scales = $db->query("SELECT * FROM mfa_scales WHERE dataset = {$materialinfo->dataset}");
  if (!count($scales)) {
    $error = "Your project is set to include multiple scales, but you have not yet defined them. Please change 
    these settings <a href='omat/$project/maintenance-scales'>in your dashboard</a>";
  }
} 

if ($projectinfo->contact_management) {
  $source_management = true;
  $sources = $db->query("SELECT * FROM mfa_sources WHERE dataset = $project ORDER BY name");
  if (!count($sources)) {
    $error = "Your project is set to track contacts and sources, but you have not yet entered any sources. 
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
    input.small{width:100px}
     .multicolumn {
        -webkit-column-count: 3; /* Chrome, Safari, Opera */
        -moz-column-count: 3; /* Firefox */
        column-count: 3;
    } 
    .well h2{margin-top:0;margin-bottom:10px;font-size:1.5em}
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
      <?php if ($projectinfo->multiscale) { ?>
        $("select[name='scale']").change(function(){
          if ($(this).val() != "") {
            var multiplier = $(this).find(':selected').data("multiplier");
            $("#standard_multiplier").html("Use the standard multiplier of " + multiplier);
            $("#standard_multiplier").attr("value", multiplier);
          } else {
            $("#standard_multiplier").html("");
            $("#standard_multiplier").attr("value", "");
          }
        });
        $("select[name='scale']").change();
        <?php if ($projectinfo->multiscale_as_proxy) { ?>
          $("select[name='multiplier']").change(function(){
            if ($(this).val() == "own") {
              $("#multiplier_box").show('fast');
            } else {
              $("#multiplier_box").hide('fast');
            }
          });
          $("select[name='multiplier']").change();
        <?php } ?>
      <?php } ?>
    });
    </script>
  </head>

  <body class="omat">

<?php require_once 'include.header.php'; ?>

  <h1><?php echo $id ? "Edit" : "Add" ?> Data Point</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/manage">Data</a></li>
    <li><a href="omat/datagroup/<?php echo $getproject->groupid ?>"><?php echo $getproject->name ?></a></li>
    <li><a href="omat/data/<?php echo $materialinfo->id ?>"><?php echo $materialinfo->name ?></a></li>
    <li class="active"><?php echo $id ? "Edit" : "Add" ?> Data Point</li>
  </ol>

  <?php if ($error) { echo "<div class=\"alert alert-danger\">$error</div>"; } else { ?>

  <?php if ($warning) { echo "<div class=\"alert alert-warning\">$warning</div>"; } ?>

  <form method="post" class="form form-horizontal">

  <?php if ($_GET['multiple-entry']) { ?>

  <div class="well">

    <h2>Enter multiple values</h2>

    <div class="multicolumn">

    <?php foreach ($years as $year) { ?>

      <div class="form-group">
        <label class="col-sm-2 control-label"><?php echo $year ?></label>
        <div class="col-sm-10">
          <input class="form-control small" type="text" name="data[<?php echo $year ?>]"  />
        </div>
      </div>

    <?php } ?>

    </div>

    </div>

  <?php } else { ?>

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

    <?php } ?>

    <?php if ($projectinfo->multiscale) { ?>

      <div class="form-group">
        <label class="col-sm-2 control-label">Scale</label>
        <div class="col-sm-10">
          <select name="scale" class="form-control" required>
            <option value=""></option>
            <?php foreach ($scales as $row) { ?>
              <option data-multiplier="<?php echo $row['standard_multiplier'] ?>" value="<?php echo $row['id'] ?>"<?php if ($row['id'] == $info->scale) { echo ' selected'; $expected_multiplier = $row['standard_multiplier']; } ?>><?php echo $row['name'] ?></option>
            <?php } ?>
          </select>
        </div>
      </div>

      <?php if ($projectinfo->multiscale_as_proxy) { ?>
        <div class="form-group">
          <label class="col-sm-2 control-label">Multiplier</label>
          <div class="col-sm-10">
            <select name="multiplier" class="form-control" required>
              <option value="standard" id="standard_multiplier"></option>
              <option value="own"<?php if ($info->multiplier && $info->multiplier != $expected_multiplier) { echo ' selected'; } ?>>
                Set a different multiplier for this material
              </option>
            </select>
          </div>
        </div>

        <div class="form-group" id="multiplier_box">
          <label class="col-sm-2 control-label">Define multiplier</label>
          <div class="col-sm-10">
            <input class="form-control" type="number" step="any" name="own_multiplier" value="<?php echo $info->multiplier ?>" />
          </div>
        </div>

      <?php } ?>

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

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
