<?php
require_once 'functions.php';
$no_project_selected = true;
require_once 'functions.omat.php';
$section = 6;
$id = (int)$_GET['id'];
$edit = (int)$_GET['edit'];
$page = !$edit ? 2 : false;

if ($edit) {
  $info = $db->record("SELECT * FROM mfa_dataset WHERE id = $edit");
}

if ($_POST) {
  $_POST['start_year'] = (int)$_POST['start_year'];
  $_POST['end_year'] = (int)$_POST['end_year'];
  if (!$_POST['start_year'] || !$_POST['end_year']) {
    $error = "Please provide start and end years for the dataset. Use the same year if you only want to input data for one year";
  } elseif ($_POST['start_year'] > $_POST['end_year']) {
    $error = "Start year provided was after the end year, please correct this";
  }
  if (!$error) {
    $post = array(
      'year_start' => (int)$_POST['start_year'],
      'year_end' => (int)$_POST['end_year'],
      'name' => html($_POST['name']),
      'research_project' => $id ? $id : $info->research_project,
      'decimal_precision' => (int)$_POST['decimal_precision'],
      'dqi' => (int)$_POST['dqi'],
      'contact_management' => (int)$_POST['contact_management'],
      'time_log' => (int)$_POST['time_log'],
      'access' => html($_POST['access']),
      'measurement' => html($_POST['measurement']),
      'multiscale' => (int)$_POST['multiscale'],
      'type' => (int)$_POST['type'],
    );
    if ($edit) {
      $db->update("mfa_dataset",$post,"id = $edit");
      $id = $edit;
    } else {
      $db->insert("mfa_dataset",$post);
      $id = $db->lastInsertId();
      $post = array(
        'user' => (int)$_SESSION['user_id'],
        'dataset' => $id,
      );
      $db->insert("users_permissions",$post);
    }
    header("Location: " . URL . "omat/dashboard/$id");
    exit();
  }
}

$types = $db->query("SELECT * FROM mfa_dataset_types ORDER BY name");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $edit ? "Edit" : "Create"; ?> online dataset | <?php echo SITENAME ?></title>
    <style type="text/css">
    .jumpdown{margin-top:14px;}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1><?php echo $edit ? "Edit" : "Create"; ?> an online dataset</h1>

  <?php if ($error) { echo "<div class=\"alert alert-danger\">$error<br /><a href='javascript:history.back(1)'>Click here to go back</a></div>"; } else { ?>

  <?php if (!$edit) { ?>
    <div class="alert alert-info">
      <p>With <a href="omat/about">the Online Material Flow Analysis Tool (OMAT)</a> you can create and manage
      your MFA-related dataset online. You can load the data yourself, or involve others in the creation and
      administration of your dataset. </p>
      <p>This tool is a work in progress and all help in trying out and providing feedback is much appreciated!</p>
    </div>
  <?php } ?>

  <form method="post" class="form form-horizontal">

  <fieldset>
    <legend>General information</legend>
    

    <div class="form-group">
      <label class="col-sm-2 control-label">Name</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="name" value="<?php echo $info->name ?>" required />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Type</label>
      <div class="col-sm-10">
        <select name="type" class="form-control" required>
          <option value=""></option>
          <?php foreach ($types as $row) { ?>
            <option value="<?php echo $row['id'] ?>"<?php if ($row['id'] == $info->type) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Start Year</label>
      <div class="col-sm-10">
        <input class="form-control" type="number" name="start_year" required value="<?php echo $info->year_start ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">End Year</label>
      <div class="col-sm-10">
        <input class="form-control" type="number" name="end_year" required value="<?php echo $info->year_end ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Standard measurement</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="measurement" value="<?php echo $info->measurement ?>" placeholder="For instance 'kg' or 'tons'" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Decimal precision</label>
      <div class="col-sm-10">
        <input class="form-control" type="number" name="decimal_precision" value="<?php echo $info->decimal_precision ?>" min="0" max="4" />
        How many decimals would you like to show? Maximum: 4
      </div>
    </div>

  </fieldset>

    <fieldset class="hide">
      <legend>Dataset Access</legend>
      
      <div class="btn-group" data-toggle="buttons">
        <label class="btn btn-default <?php echo $info->access == 'private' || !count($info) ? 'active' : ''; ?>">
          <input type="radio" name="access" value="private" <?php echo $info->access == 'private' || !count($info) ? 'checked' : ''; ?>> <strong>Private project</strong> - only authorized users can access
        </label>
        <label class="btn btn-default <?php echo $info->access == 'link_only' ? 'active' : ''; ?>">
          <input type="radio" name="access" value="link_only" <?php echo $info->access == 'link_only' ? 'checked' : ''; ?>> <strong>Semi-private project</strong> - only visitors with the link can access
        </label>
        <label class="btn btn-default <?php echo $info->access == 'public' ? 'active' : ''; ?>">
          <input type="radio" name="access" value="public" <?php echo $info->access == 'public' ? 'checked' : ''; ?>> <strong> Public project</strong> - include the dataset in the public list
        </label>
      </div>

    </fieldset>

    <fieldset class="jumpdown">
      <legend>Additional Options</legend>
      
      <div class="checkbox">
        <label>
          <input type="checkbox" name="dqi" value="1" <?php echo $info->dqi ? 'checked' : ''; ?>> Include Data Quality Indicators for your data points
        </label>
      </div>

      <div class="checkbox">
        <label>
          <input type="checkbox" name="multiscale" value="1" <?php echo $info->multiscale ? 'checked' : ''; ?>> Track data on multiple scales (e.g. on both urban and regional scale)
        </label>
      </div>

      <div class="checkbox">
        <label>
          <input type="checkbox" name="contact_management" value="1" <?php echo $info->contact_management ? 'checked' : ''; ?>> Manage contacts and sources as well
        </label>
      </div>

      <div class="checkbox">
        <label>
          <input type="checkbox" name="time_log" value="1" <?php echo $info->time_log ? 'checked' : ''; ?>> Log time spent on different activities
        </label>
      </div>

    </fieldset>

     <button type="submit" class="btn btn-primary jumpdown"><?php echo $edit ? "Save settings" : "Create data set" ?></button>

    </div>

  
  </form>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
