<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 2;
$sub_page = 1;

$id = (int)$_GET['id'];
$project = (int)$_GET['project'];

$info = $db->record("SELECT * FROM dqi_classifications WHERE id = $id");
if ($id) {
  $check = $db->query("SELECT * FROM dqi_sections WHERE id = {$info->section}");
  $dqisection = $info->section;
  // Let's make sure this is indeed part of the project this user has access to
  if ($check->dataset != $project) {
    die("No access");
  }
} else {
  $dqisection = (int)$_GET['section'];
}

$sectioninfo = $db->query("SELECT * FROM dqi_sections WHERE id = $dqisection");

if ($_POST) {
  $post = array(
    'score' => (int)$_POST['score'],
    'name' => html($_POST['name']),
    'section' => $dqisection,
  );
  if ($id) {
    $db->update("dqi_classifications",$post,"id = $id");
  } else {
    $db->insert("dqi_classifications",$post);
    $id = $db->lastInsertId();
  }
  header("Location: " . URL . "omat/$project/maintenance-dqi-section/$dqisection");
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <link rel="stylesheet" href="css/sidebar.css" />
    <title>DQI | <?php echo $info->name ?> | OMAT | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>
<?php require_once 'include.omatheader.php'; ?>

  <h1>Data Quality Indicator Settings</h1>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/maintenance-dqi">Data Quality Indicators</a></li>
    <li><a href="omat/<?php echo $project ?>/maintenance-dqi-section/<?php echo $dqisection ?>"><?php echo $sectioninfo->name ?></a></li>
    <li class="active"><?php echo $id ? "Edit" : "Add" ?> Score <?php echo $info->score ?></li>
  </ol>

  <form method="post" class="form form-horizontal">
  
    <div class="form-group">
      <label class="col-sm-2 control-label">Score</label>
      <div class="col-sm-10">
        <input class="form-control" type="number" name="score" value="<?php echo $info->score ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Description</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="name" value="<?php echo $info->name ?>" />
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>

  </form>

<?php require_once 'include.omatfooter.php'; ?>
<?php require_once 'include.footer.php'; ?>

  </body>
</html>
