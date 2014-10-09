<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 2;

$contact = (int)$_GET['contact'];
$source = (int)$_GET['source'];

if ($contact) {
  $type = "contact";
  $id = $contact;
} else {
  $type = "source";
  $id = $source;
}
$info = $db->record("SELECT * FROM mfa_{$type}s WHERE id = $id AND dataset = $project");
if (!count($info)) {
  kill("Invalid contact or source");
}

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("DELETE FROM mfa_material_links WHERE id = $delete AND $type = $id");
  $print = "Association was deleted";
}

if ($_POST['flow']) {
  $explode = explode(".", $_POST['flow']);
  if ($explode[1]) {
    $group = (int)$explode[1];
  } else {
    $material = (int)$_POST['flow'];
  }
  $post = array(
    'contact' => $contact ? $contact : NULL,
    'source' => $source ? $source : NULL,
    'mfa_group' => $group ? $group : NULL,
    'material' => $material ? $material : NULL,
  );
  $db->insert("mfa_material_links",$post);
  $print = "Information was saved";
}

$list = $db->query("SELECT mfa_groups.name AS groupname, mfa_materials.name AS material, l.id,
  (SELECT mfa_groups.name FROM mfa_groups WHERE mfa_materials.mfa_group = mfa_groups.id) AS material_groupname
FROM mfa_material_links l
  LEFT JOIN mfa_groups ON l.mfa_group = mfa_groups.id
  LEFT JOIN mfa_materials ON l.material = mfa_materials.id
WHERE $type = $id");


$groups = $db->query("SELECT * FROM mfa_groups WHERE dataset = $project ORDER BY section");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Material Links | <?php echo $info->name ?> | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Material Associations</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <?php if ($contact) { ?>
      <li><a href="omat/<?php echo $project ?>/contacts">Contacts</a></li>
      <li><a href="omat/<?php echo $project ?>/viewcontact/<?php echo $contact ?>"><?php echo $info->name ?></a></li>
    <?php } else { ?>
      <li><a href="omat/<?php echo $project ?>/sources">Sources</a></li>
      <li><a href="omat/<?php echo $project ?>/viewsource/<?php echo $source ?>"><?php echo $info->name ?></a></li>
    <?php } ?>
    <li class="active">Material Associations</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <div class="alert alert-info">
    This section can be used to link particular contacts or sources with one (or more) flows. This is 
    useful if you have many potential leads and need to keep track of what lead can provide information
    on which material flow. It is <strong>not</strong> used to link actual data with sources, because
    you do this in the Data section when you enter the actual numbers; it is only used to help you 
    manage potential leads and keep an organized overview of which contacts/sources are on your radar
    for particular flows.
  </div>

  <h2>Current associations</h2>

  <?php if (count($list)) { ?>
    <table class="table table-striped">
      <tr>
        <th>Group</th>
        <th>Material</th>
        <th>Actions</th>
      </tr>
    <?php foreach ($list as $row) { ?>
      <tr>
        <td><?php echo $row['material'] ? $row['material_groupname'] : $row['groupname'] ?></td>
        <td><?php echo $row['material'] ?></td>
        <td><a href="omat.materiallink.php?project=<?php echo $project ?>&amp;<?php echo $type ?>=<?php echo $id ?>&amp;delete=<?php echo $row['id'] ?>" onclick="javascript:return confirm('Are you sure?')" class="btn btn-danger">Delete</a></td>
      </tr>
    <?php } ?>
    </table>
  <?php } ?>

  <h2>With which flow is this <?php echo $contact ? "contact" : "source" ?> associated?</h2>

  <form method="post" class="form-horizontal">

    <div class="form-group">
      <label class="col-sm-2 control-label">Flow</label>
      <div class="col-sm-10">
        <select name="flow" class="form-control" required>
          <option></option>
          <?php foreach ($groups as $row) {
            $subgroups = $db->query("SELECT * FROM mfa_materials WHERE mfa_group = {$row['id']} ORDER BY code");
          ?>
            <optgroup label="<?php echo $row['section'] ?>. <?php echo $row['name'] ?>">
              <option value="group.<?php echo $row['id'] ?>"><?php echo $row['name'] ?> - all</option>
              <?php foreach ($subgroups as $subrow) { ?>
                <option value="<?php echo $subrow['id'] ?>"><?php echo $subrow['code'] ?>. <?php echo $subrow['name'] ?></option>
              <?php } ?>
            </optgroup>
          <?php } ?>
        </select>
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
