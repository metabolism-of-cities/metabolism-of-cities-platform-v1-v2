<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 3;
$sub_page = 2;

$id = (int)$_GET['id'];
$dataset = $db->record("SELECT * FROM mfa_dataset WHERE id = $project");

$info = $db->record("SELECT i.*, t.name AS type_name, papers.title
FROM mfa_indicators i
  JOIN mfa_indicators_types t ON i.type = t.id 
  LEFT JOIN papers ON i.more_information = papers.id  
WHERE i.id = $id AND (dataset = $project OR dataset IS NULL)");

if ($_POST) {
  $post = array(
    'type' => mysql_clean($_POST['type']),
    'indicator' => $id,
    'mfa_group' => (int)$_POST['mfa_group'],
  );
  $db->insert("mfa_indicators_formula",$post);
  $print = "Formula was changed";
}

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("DELETE FROM mfa_indicators_formula WHERE id = $delete LIMIT 1");
  $print = "";
}

$formula = $db->query("SELECT f.*, mfa_groups.section, mfa_groups.name
FROM mfa_indicators_formula f
  JOIN mfa_groups ON f.mfa_group = mfa_groups.id
WHERE indicator = $id AND mfa_groups.dataset = $project ORDER BY f.id");

$groups = $db->query("SELECT * FROM mfa_groups WHERE dataset = $project ORDER BY name");

$all_addition = true;
foreach ($formula as $row) {
  $sql_group .= $row['mfa_group'] . ",";
  if ($row['type'] == "subtract") {
    $all_addition = false;
  }
}

$types = array('subtract', 'add');

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Define Formula | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>
    <?php echo $info->name ?>
    <?php if ($info->abbreviation) { ?>(<?php echo $info->abbreviation ?>)<?php } ?>  
  </h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/reports-indicators">Indicators</a></li>
    <li><a href="omat/<?php echo $project ?>/reports-indicator/<?php echo $id ?>"><?php echo $info->name ?></a></li>
    <li class="active">Define formula</li>
  </ol>

  <?php if (!count($formula)) { ?>
    <div class="alert alert-warning">
      <p>No formula set yet! Define the formula using the fields below</p>
    </div>
  <?php } ?>

  <h2>Change Formula</h2>

  <form method="post" class="form form-horizontal">
   <div class="form-group">
     <label class="col-sm-2 control-label">Type</label>
     <div class="col-sm-10">
       <select name="type" class="form-control">
         <?php foreach ($types as $value) { ?>
           <option value="<?php echo $value ?>"<?php if ($value == $info->type) { echo ' selected'; } ?>><?php echo $value ?></option>
         <?php } ?>
       </select>
     </div>
   </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Group</label>
      <div class="col-sm-10">
        <select name="mfa_group" class="form-control">
          <?php foreach ($groups as $row) { ?>
            <option value="<?php echo $row['id'] ?>"<?php if ($row['id'] == $info->mfa_gruop) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Add to the formula</button>
      </div>
    </div>
  </form>

  <?php if (count($formula)) { ?>

  <div class="well">
    <h2>Formula</h2>

    <table class="table table-striped">
    <?php foreach ($formula as $row) { ?>
      <tr>
        <td><?php echo $row['type'] == "add" ? $plus : "-" ?></td>
        <td><a href="omat/<?php echo $project ?>/datagroup/<?php echo $row['mfa_group'] ?>"><?php echo $row['name'] ?></a></td>
        <td><a href="reports.indicator-formula.php?project=<?php echo $project ?>&amp;id=<?php echo $id ?>&amp;delete=<?php echo $row['id'] ?>" class="btn btn-danger">Remove from formula</a></td>
      </tr>
    <?php 
    // We don't define the plus sign earlier so that it does not show up for the first element, which looks weird
    $plus = "+"; } ?>
    <tr>
      <th>=</th>
      <th colspan="2"><?php echo $info->name ?></th>
    </tr>
    </table>
  </div>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
