<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 8;

$id = (int)$project;

if ($_POST) {
  $post = array(
    'year' => (int)$_POST['year'],
    'population' => (int)$_POST['population'],
    'dataset' => $id,
  );
  $db->insert("mfa_population",$post);
}

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("DELETE FROM mfa_population WHERE id = $delete AND dataset = $id LIMIT 1");
  $print = "Population data removed";
}

$list = $db->query("SELECT * FROM mfa_population
WHERE dataset = $id
ORDER BY year");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Population | <?php echo SITENAME ?></title>
  </head>

  <body class="omat">

<?php require_once 'include.header.php'; ?>

  <h1>Population</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Population</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <?php if (count($list)) { ?>

    <table class="table table-striped ellipsis">
      <tr>
        <th>Year</th>
        <th>Population</th>
        <th>Action</th>
      </tr>
    <?php foreach ($list as $row) { ?>
      <tr>
        <td><?php echo $row['year'] ?></td>
        <td><?php echo number_format($row['population'],0) ?></td>
        <td><a href="omat/<?php echo $project ?>/population/delete/<?php echo $row['id'] ?>" class="btn btn-danger" onclick="javascript:return confirm('Are you sure?')">Remove</a></td>
      </tr>
    <?php } ?>
    </table>

  <?php } ?>

  <h2>Add population data</h2>

  <form method="post" class="form-horizontal">

    <div class="form-group">
      <label class="col-sm-2 control-label">Year</label>
      <div class="col-sm-10">
        <input class="form-control" type="number" name="year" value="<?php echo $info->year ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Population</label>
      <div class="col-sm-10">
        <input class="form-control" type="number" name="population" value="<?php echo $info->population ?>" />
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
