<?php
require_once 'functions.php';

$load_menu = 1;
$sub_page = 1;
$section = 6;

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM mfa_materials WHERE id = $id");

$getproject = $db->record("SELECT g.dataset, g.name, g.id AS groupid FROM mfa_materials m JOIN mfa_groups g ON m.mfa_group = g.id WHERE m.id = $id");
$project = $getproject->dataset;

require_once 'functions.omat.php';

if ($_POST) {
  $post = array(
    'notes' => html($_POST['notes']),
    'material' => $id,
  );
  if ($update) {
    $db->update("mfa_materials_notes",$post,"id = $update");
  } else {
    $db->insert("mfa_materials_notes",$post);
  }
  header("Location: " . URL . "omat/material-comments/$id/saved");
  exit();
}

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("DELETE FROM mfa_materials_notes WHERE id = $delete AND material = $id LIMIT 1");
  header("Location: " . URL . "omat/material-comments/$id/deleted");
  exit();
}

if ($_GET['message'] == 'saved') {
  $print = "Information has been saved";
} elseif ($_GET['message'] == 'deleted') {
  $print = "Comment was deleted";
}

$list = $db->query("SELECT * FROM mfa_materials_notes WHERE material = $id ORDER BY date");

$associations = $db->query("SELECT s.name AS sourcename, c.name AS contactname, c.organization,
  l.source, l.contact
  FROM mfa_material_links l
    LEFT JOIN mfa_sources s ON l.source = s.id
    LEFT JOIN mfa_contacts c ON l.contact = c.id
 WHERE l.material = $id ORDER BY organization DESC, contactname, sourcename");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->name ?> | Notes and Comments | <?php echo SITENAME ?></title>
    <style type="text/css">
    .well a{float:right;opacity:0.4}
    .well a:hover{opacity:1}
    textarea.form-control{height:222px}
    .striped li:nth-child(odd) { background: #f4f4f4; }
    .striped li{border-bottom:1px dotted #aaa}
    .striped{border-top: 1px dotted #aaa}
    h2{font-size:20px}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Comments</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/manage">Data</a></li>
    <li><a href="omat/datagroup/<?php echo $getproject->groupid ?>"><?php echo $getproject->name ?></a></li>
    <li><a href="omat/data/<?php echo $info->id ?>"><?php echo $info->name ?></a></li>
    <li class="active">Notes and Comments</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <?php if (count($associations)) { ?>
    <h2>Associated resources</h2>

    <div class="alert alert-info">
      <?php echo count($associations) ?> resource(s) found
    </div>

    <ul class="nav nav-stacked nav-pills striped">
    <?php foreach ($associations as $row) { 
      $id = $row['contact'];
      if ($row['source']) {
        $id = $row['source'];
        $icon = 'file';
      } elseif ($row['organization']) {
        $icon = 'building';
      } else {
        $icon = 'user';
      }
    ?>
      <li>
        <a href="omat/<?php echo $project ?>/view<?php echo $row['source'] ? 'source' : 'contact'; ?>/<?php echo $id; ?>">
          <i class="fa fa-<?php echo $icon; ?>"></i> 
          <?php echo $row['sourcename'] ? $row['sourcename'] : $row['contactname'] ?>
        </a>
      </li>
    <?php } ?>
    </ul>


  <?php } ?>

  <h2>Comments</h2>

  <div class="alert alert-info">
    <?php echo count($list) ?> comment(s) found
  </div>

  <?php if (count($list)) { ?>
    <?php foreach ($list as $row) { ?>
      <div class="well">
        <a 
          href="omat/material-comments/<?php echo $id ?>/delete/<?php echo $row['id'] ?>" onclick="javascript:return confirm('Are you sure?')"
          class="btn btn-danger"
        >
          <i class="fa fa-ban"></i>
        </a>
        <p><strong>Comment from user on <?php echo format_date("M d, Y", $row['date']) ?></strong></p>
        <?php echo $row['notes'] ?>
      </div>
    <?php } ?>
  <?php } ?>

  <form method="post" class="form form-horizontal">

    <div class="form-group">
      <label class="col-sm-2 control-label">Notes</label>
      <div class="col-sm-10">
        <textarea class="form-control" name="notes"><?php echo $info->notes ?></textarea>
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
