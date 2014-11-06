<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 6;

$id = (int)$_GET['id'];

if ($id) {
  $info = $db->record("SELECT * FROM mfa_industries WHERE dataset = $project AND id = $id");
}

if (!count($info) && $id) {
  kill("No record found");
}

if ($_POST) {
  $post = array(
    'dataset' => $project,
    'name' => html($_POST['name']),
    'indicator_weight' => (int)$_POST['indicator_weight'],
    'indicator_value' => (int)$_POST['indicator_value'],
    'indicator_environment' => (int)$_POST['indicator_environment'],
    'indicator_companies' => (int)$_POST['indicator_companies'],
    'indicator_illegality' => (int)$_POST['indicator_illegality'],
    'description_companies' => html($_POST['description_companies']),
    'description_illegality' => html($_POST['description_illegality']),
    'description_associations' => html($_POST['description_associations']),
    'description_general' => mysql_clean($_POST['description_general']),
  );
  if ($id) {
    $db->update("mfa_industries",$post,"id = $id");
  } else {
    $db->insert("mfa_industries",$post);
    $id = $db->insert_id;
  }
  header("Location: " . URL . "omat/$project/industries/saved");
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->name ? $info->name : "Add Industry"; ?> | <?php echo SITENAME ?></title>
    <script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <a href="omat/<?php echo $project ?>/industry/0" class="btn btn-success pull-right"><i class="fa fa-cogs"></i> Add industry</a>

  <h1>Industries</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/industries">Industries</a></li>
    <li class="active"><?php echo $info->name ? $info->name : 'Add industry'; ?></li>
  </ol>


  <form method="post" class="form-horizontal">
    
    <div class="form-group">
      <label class="col-sm-2 control-label">Name</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" required name="name" value="<?php echo $info->name ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Description</label>
      <div class="col-sm-10">
        <textarea id="description" rows="10" cols="80" class="form-control" name="description_general"><?php echo br2nl($info->description_general) ?></textarea>
            <script>
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace('description');
            </script>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Mass</label>
      <div class="col-sm-10">
        <select name="indicator_weight" class="form-control">
            <option value=""></option>
          <?php for ($i = 1; $i <= 5; $i++) { ?>
            <option value="<?php echo $i ?>"<?php if ($i == $info->indicator_weight) { echo ' selected'; } ?>><?php echo $i ?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Value</label>
      <div class="col-sm-10">
        <select name="indicator_value" class="form-control">
            <option value=""></option>
          <?php for ($i = 1; $i <= 3; $i++) { ?>
            <option value="<?php echo $i ?>"<?php if ($i == $info->indicator_value) { echo ' selected'; } ?>><?php echo $i ?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Environmental impact</label>
      <div class="col-sm-10">
        <select name="indicator_environment" class="form-control">
            <option value=""></option>
          <?php for ($i = 1; $i <= 3; $i++) { ?>
            <option value="<?php echo $i ?>"<?php if ($i == $info->indicator_environment) { echo ' selected'; } ?>><?php echo $i ?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Number of companies</label>
      <div class="col-sm-10">
        <select name="indicator_companies" class="form-control">
            <option value=""></option>
          <?php for ($i = 1; $i <= 3; $i++) { ?>
            <option value="<?php echo $i ?>"<?php if ($i == $info->indicator_companies) { echo ' selected'; } ?>><?php echo $i ?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Illegality</label>
      <div class="col-sm-10">
        <select name="indicator_illegality" class="form-control">
            <option value=""></option>
          <?php for ($i = 1; $i <= 3; $i++) { ?>
            <option value="<?php echo $i ?>"<?php if ($i == $info->indicator_illegality) { echo ' selected'; } ?>><?php echo $i ?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Details illegality</label>
      <div class="col-sm-10">
        <textarea class="form-control" name="description_illegality"><?php echo br2nl($info->description_illegality) ?></textarea>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Details company structure</label>
      <div class="col-sm-10">
        <textarea class="form-control" name="description_companies"><?php echo br2nl($info->description_companies) ?></textarea>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Details associations</label>
      <div class="col-sm-10">
        <textarea class="form-control" name="description_associations"><?php echo br2nl($info->description_associations) ?></textarea>
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
