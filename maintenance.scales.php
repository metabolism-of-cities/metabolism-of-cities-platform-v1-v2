<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 2;
$sub_page = 5;

$id = (int)$_GET['id'];

if ($_POST) {
  $post = array(
    'multiscale_as_proxy' => (int)$_POST['multiscale_as_proxy'],
  );
  $db->update("mfa_dataset",$post,"id = $id");
  $print = "Option has been saved";
}

$info = $db->record("SELECT * FROM mfa_dataset WHERE id = $id");

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $check = $db->query("SELECT * FROM mfa_data WHERE scale = $delete");
  if (count($check)) {
    die("Sorry, we could not delete this scale type because there are data points for this scale already logged!");
  }
  $db->query("DELETE FROM mfa_scales WHERE id = $delete AND dataset = $id LIMIT 1");
  $print = "Scale was deleted";
}

$list = $db->query("SELECT * FROM mfa_scales WHERE dataset = $id ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <link rel="stylesheet" href="css/sidebar.css" />
    <title>Types of Scales | <?php echo $info->name ?> | OMAT | <?php echo SITENAME ?></title>
    <style type="text/css">
    form label{font-weight:normal;cursor:pointer}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>
<?php require_once 'include.omatheader.php'; ?>

  <h1>Types of Scales</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Types of Scales</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <h2>Use of scales</h2>

  <div class="alert alert-info">
    How should the scales be used? One option is that scales are used for
    <strong>comparison</strong>.  You should use this if you intend to get full
    datasets for each of the scales and to contrast the different results. An
    example of this multi-scale approach is <a href="publication/69">Barles'
    study on Paris, Paris and its suburbs, and the entire region</a>.
    Alternatively, you can use several scales because you want to use data from
    different scales <strong>as proxies</strong> if data is not available for your scale of
    choice. For instance, if you are studying the City of Cape Town but you
    sometimes have to make do with data from the province (the Western Cape),
    then you can enter this data and OMAT will convert it for you (using
    parameters that you can set). 
  </div>

  <form method="post">
  <p>
  <label>
     <input type="radio" name="multiscale_as_proxy" value="0" <?php echo !$info->multiscale_as_proxy ? 'checked="checked"' : ''; ?> />
      Use scales to compare different systems (each scale will be an individual dataset)
   </label>
   </p>
  <p>
  <label>
     <input type="radio" name="multiscale_as_proxy" value="1" <?php echo $info->multiscale_as_proxy ? 'checked="checked"' : ''; ?> />
      Use scales as proxies (all scales will form one single dataset)
   </label>
   </p>

  <div class="form-group">
      <button type="submit" class="btn btn-primary">Save</button>
  </div>

  </form>

  <?php if (!count($list)) { ?>

  <div class="alert alert-info">
    In this section you can define scale levels for your project. In the data points you can save values
    for each scale that you define. Scales could include for instance 'urban core', 'city-wide region', and 'province'.
  </div>

  <?php } else { ?>
    <table class="table table-striped">
      <tr>
        <th>Name</th>
        <?php if ($info->multiscale_as_proxy) { ?>
          <th>Standard Multiplier</th>
        <?php } ?>
        <th>Edit</th>
        <th>Delete</th>
      </tr>
    <?php foreach ($list as $row) { ?>
      <tr>
        <td><?php echo $row['name'] ?></td>
        <?php if ($info->multiscale_as_proxy) { ?>
          <td><?php echo $row['standard_multiplier'] ?></td>
        <?php } ?>
        <td><a href="omat/<?php echo $project ?>/maintenance-scale/<?php echo $row['id'] ?>" class="btn btn-primary">Edit</a></td>
        <td><a href="omat/<?php echo $project ?>/maintenance-scales/<?php echo $row['id'] ?>/delete" class="btn btn-danger" onclick="javascript:return confirm('Are you sure?')">Delete</a></td>
      </tr>
    <?php } ?>
    </table>
  <?php } ?>

  <p><a href="omat/<?php echo $project ?>/maintenance-scale/0" class="btn btn-success">Add scale</a></p>

<?php require_once 'include.omatfooter.php'; ?>
<?php require_once 'include.footer.php'; ?>

  </body>
</html>
