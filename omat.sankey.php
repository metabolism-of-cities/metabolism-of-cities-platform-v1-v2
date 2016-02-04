<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 7;

$project = (int)$project;
$id = (int)$_GET['id'];

if ($_POST['name']) {
  $post = array(
    'name' => html($_POST['name']),
    'dataset' => $project,
  );
  if ($id) {
    $db->update("mfa_sankey",$post,"id = $id");
  } else {
    $db->insert("mfa_sankey",$post);
    $id = $db->lastInsertId();
  }
  header("Location: " . URL . "omat/$project/sankeynodes/$id");
  exit();
}

$info = $db->record("SELECT * FROM mfa_sankey WHERE id = $id AND dataset = $project");
if (!$info->id && $id) {
  kill("Not found");
}

if ($_GET['saved']) {
  $print = "Information was saved";
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Sankey Diagrams | <?php echo SITENAME ?></title>
  </head>

  <body class="omat">

<?php require_once 'include.header.php'; ?>

  <h1><?php echo $id ? "Edit" : "Add"; ?> Sankey Diagrams</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/sankeys">Sankey Diagrams</a></li>
    <li class="active"><?php echo $id ? "Edit" : "Add" ?> Sankey Diagrams</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  
    <h2>Diagram Information</h2>

    <form method="post" class="form form-horizontal">

      <div class="form-group">
        <label class="col-sm-2 control-label">Name</label>
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

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
