<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 2;

$id = (int)$_GET['id'];
$project = (int)$_GET['project'];

$industries = $db->query("SELECT * FROM mfa_industries WHERE dataset = $project ORDER BY name");

if ($id) {
  $info = $db->record("SELECT * FROM mfa_contacts WHERE id = $id AND dataset = $project");
  if (!count($info)) {
    die("Record not found");
  }
}

if ($_POST) {
  $post = array(
    'name' => html($_POST['name']),
    'organization' => (int)$_POST['organization'],
    'employer' => $_POST['employer'] ? html($_POST['employer']) : NULL,
    'industry' => $_POST['industry'] ? (int)$_POST['industry'] : NULL,
    'belongs_to' => $_POST['belongs_to'] ? (int)$_POST['belongs_to'] : NULL,
    'details' => html($_POST['details']),
    'dataset' => $project,
  );
  if ($id) {
    $db->update("mfa_contacts",$post,"id = $id");
  } else {
    $db->insert("mfa_contacts",$post);
    $id = $db->lastInsertId();
  }
  header("Location: " . URL . "omat/$project/viewcontact/$id");
  exit();
}

$referred_contact = $db->record("SELECT 
  mfa_contacts.*
FROM mfa_leads
  JOIN mfa_contacts ON mfa_leads.from_contact = mfa_contacts.id
WHERE to_contact = $id");

$organizations = $db->query("SELECT id,name FROM mfa_contacts WHERE dataset = $project AND organization = 1 ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php if ($id) { echo $info->name . " | "; } ?> Contacts | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Contacts</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/contacts">Contacts</a></li>
    <?php if ($id) { ?>
      <li><a href="omat/<?php echo $project ?>/viewcontact/<?php echo $id ?>"><?php echo $info->name ?></a></li>
    <?php } ?>
    <li class="active"><?php echo $id ? "Edit" : "Add" ?> Contact</li>
  </ol>

  <form method="post" class="form form-horizontal">

    <div class="form-group">
      <label class="col-sm-2 control-label">Name</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="name" value="<?php echo $info->name ?>" />
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="organization" value="1" <?php echo $info->organization ? 'checked' : ''; ?> /> 
              This is an organization
          </label>
        </div>
      </div>
    </div>

    <?php if (count($industries)) { ?>

      <div class="form-group">
        <label class="col-sm-2 control-label">Industry</label>
        <div class="col-sm-10">
          <select name="industry" class="form-control">
              <option value=""></option>
            <?php foreach ($industries as $row) { ?>
              <option value="<?php echo $row['id'] ?>"<?php if ($row['id'] == $info->industry) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
            <?php } ?>
          </select>
        </div>
      </div>

    <?php } ?>

    <?php if (count($organizations)) { ?>

      <div class="form-group">
        <label class="col-sm-2 control-label">Belongs to</label>
        <div class="col-sm-10">
          <select name="belongs_to" class="form-control">
              <option value=""></option>
            <?php foreach ($organizations as $row) { ?>
              <option value="<?php echo $row['id'] ?>"<?php if ($row['id'] == $info->belongs_to) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
            <?php } ?>
          </select>
        </div>
      </div>

    <?php } ?>

    <div class="form-group">
      <label class="col-sm-2 control-label">Notes</label>
      <div class="col-sm-10">
        <textarea class="form-control" name="details"><?php echo br2nl($info->details) ?></textarea>
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
