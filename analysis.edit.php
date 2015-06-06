<?php
$admin_login = true;
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 5;
$page = 99;


$case = (int)$_GET['case'];
$option = (int)$_GET['option'];
$id = (int)$_GET['edit'];

if ($id) {
  $info = $db->record("SELECT * FROM analysis WHERE id = $id");
  $case = $info->case_study;
  $option = $info->option;
}

$optioninfo = $db->record("SELECT * FROM analysis_options WHERE id = $option");
$caseinfo = $db->record("SELECT * FROM case_studies WHERE id = $case");
$type = $optioninfo->type;

if ($_POST) {
  $post = array(
    'result' => $_POST['result'] ? (float)$_POST['result'] : NULL,
    'option' => $option,
    'case_study' => $case,
    'year' => $_POST['year'] ? (int)$_POST['year'] : NULL,
    'notes' => $_POST['notes'] ? html($_POST['notes']) : NULL,
  );
  if ($id) {
    $db->update("analysis",$post,"id = $id");
  } else {
    $db->insert("analysis",$post);
    $id = $db->insert_id;
  }
  header("Location: " . URL . "analysis/{$case}/{$optioninfo->type}");
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Analysis | <?php echo SITENAME ?></title>
    <style type="text/css">
    textarea.form-control{height:200px}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Analysis</h1>

  <p>Case Study: <a href="publication/<?php echo $id ?>"><?php echo $caseinfo->name ?></a></p>
  <p>Option: <strong><?php echo $optioninfo->name ?></strong></p>

  <form method="post" class="form-horizontal">

    <div class="form-group">
      <label class="col-sm-2 control-label">Year</label>
      <div class="col-sm-10">
        <input class="form-control" type="number" name="year" value="<?php echo $info->year ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Value</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="result" value="<?php echo $info->result ?>" 
        <?php if ($optioninfo->measure) { ?>
        placeholder="Measure: <?php echo $optioninfo->measure ?>"
        <?php } ?>
        />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Information</label>
      <div class="col-sm-10">
        <textarea class="form-control" name="notes"><?php echo br2nl($info->notes) ?></textarea>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>

  </form>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
