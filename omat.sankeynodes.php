<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 7;

$project = (int)$project;
$id = (int)$_GET['id'];

$info = $db->record("SELECT * FROM mfa_sankey WHERE id = $id AND dataset = $project");
if (!$info->id && $id) {
  kill("Not found");
}

if ($_POST['new_name']) {
  $old_name = html($_POST['old_name']);
  $new_name = html($_POST['new_name']);
  $db->query("UPDATE mfa_sankey_nodes SET from_name = '$new_name' WHERE from_name = '$old_name' AND sankey = $id");
  $db->query("UPDATE mfa_sankey_nodes SET to_name = '$new_name' WHERE to_name = '$old_name' AND sankey = $id");
  $print = "Labels have been changed";
} elseif ($_POST) {
  $update = (int)$_POST['update'];
  $post = array(
    'from_name' => html($_POST['from_name']),
    'to_name' => html($_POST['to_name']),
    'weight' => (int)$_POST['weight'],
    'sankey' => $id,
  );
  if ($update) {
    $db->update("mfa_sankey_nodes",$post,"id = $update");
  } else {
    $db->insert("mfa_sankey_nodes",$post);
  }
  $print = "Information was saved";
}

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("DELETE FROM mfa_sankey_nodes WHERE id = $delete AND sankey = $id LIMIT 1");
  $print = "The sankey node was deleted";
}

$list = $db->query("SELECT * FROM mfa_sankey_nodes WHERE sankey = $id ORDER BY to_name, from_name");

if ($_GET['saved']) {
  $print = "Information was saved";
}

$edit = (int)$_GET['edit'];
if ($edit) {
  $details = $db->record("SELECT * FROM mfa_sankey_nodes WHERE id = $edit AND sankey = $id");
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Sankey Nodes | <?php echo SITENAME ?></title>
  </head>

  <body class="omat">

<?php require_once 'include.header.php'; ?>

  <h1>Manage Sankey Nodes</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/sankeys">Sankey Diagrams</a></li>
    <li><a href="omat/<?php echo $project ?>/viewsankey/<?php echo $id ?>"><?php echo $info->name ?></a></li>
    <li class="active">Nodes</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <?php if (!$edit) { ?>
  
    <div class="alert alert-info">
      <strong><?php echo count($list) ?></strong> nodes found.
    </div>

    <?php if (count($list)) { ?>

      <table class="table table-striped ellipsis">
        <tr>
          <th>From</th>
          <th>To</th>
          <th>Weight</th>
          <th class="short">Edit</th>
          <th class="short">Delete</th>
        </tr>
      <?php foreach ($list as $row) { ?>
        <tr>
          <td><?php echo $row['from_name'] ?></td>
          <td><?php echo $row['to_name'] ?></td>
          <td><?php echo $row['weight'] ?></td>
          <td><a href="omat/<?php echo $project ?>/sankeynodes/<?php echo $id ?>/edit/<?php echo $row['id'] ?>" class="btn btn-primary">Edit</a></td>
          <td><a href="omat/<?php echo $project ?>/sankeynodes/<?php echo $id ?>/delete/<?php echo $row['id'] ?>" class="btn btn-danger" onclick="javascript:return confirm('Are you sure?')">Delete</a></td>
        </tr>
      <?php } ?>
      </table>

    <?php } ?>

  <?php } ?>

    <h2><?php echo $edit ? "Edit" : "Add"; ?> Node</h2>

    <form method="post" class="form form-horizontal" action="omat/<?php echo $project ?>/sankeynodes/<?php echo $id ?>">

      <div class="form-group">
        <label class="col-sm-2 control-label">From</label>
        <div class="col-sm-10">
          <input class="form-control" type="text" name="from_name" value="<?php echo $details->from_name ?>" />
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label">To</label>
        <div class="col-sm-10">
          <input class="form-control" type="text" name="to_name" value="<?php echo $details->to_name ?>" />
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label">Weight</label>
        <div class="col-sm-10">
          <input class="form-control" type="number" name="weight" value="<?php echo $details->weight ?>" />
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary">Save</button>
          <input type="hidden" name="update" value="<?php echo $edit ?>" />
        </div>
      </div>
    
    </form>

    <?php if (!$edit) { ?>

      <h2>Change Label</h2>

      <div class="alert alert-info">
        Would you like to change a particular label for all of the fields? Instead of changing them one by one, 
        you can change them all at once using the form below.
      </div>

      <form method="post" class="form form-horizontal">

        <div class="form-group">
          <label class="col-sm-2 control-label">Old name</label>
          <div class="col-sm-10">
            <input class="form-control" type="text" name="old_name" />
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">New name</label>
          <div class="col-sm-10">
            <input class="form-control" type="text" name="new_name" />
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">Change all</button>
          </div>
        </div>
      
      </form>

    <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
