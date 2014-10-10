<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 2;

$id = $project;

$type = (int)$_GET['type'];
$status = (int)$_GET['status'];

$sql = $type ? " AND c.type = $type" : false;
if ($status) {
  $sql .= " AND c.status = $status";
  $status_name = $db->record("SELECT * FROM mfa_status_options WHERE id = $status");
  $type_sql = "AND mfa_contacts.status = $status";
}

if ($_GET['flag']) {
  $flag = (int)$_GET['flag'];
  $sql .= " AND EXISTS (SELECT * FROM mfa_contacts_flags WHERE contact = c.id AND flag = $flag)";
  $type_sql .= " AND EXISTS (SELECT * FROM mfa_contacts_flags WHERE contact = mfa_contacts.id AND flag = $flag)";
}

if ($_GET['random-contact']) {
  $contact = $db->record("SELECT * FROM mfa_contacts WHERE dataset = $project AND status = 1 ORDER BY RAND() LIMIT 1");
  if ($contact->id) {
    header("Location: " . URL . "omat/$project/viewcontact/{$contact->id}");
    exit();
  }
}

if ($_GET['edit']) {
  $edit = true;
}

$organization = (int)$_GET['organization'];
if ($organization == 1) {
  $org_label = "Organization";
  $sql .= " AND c.organization = 1";
  $type_sql .= " AND organization = 1";
} elseif ($organization == 2) {
  $org_label = "People";
  $sql .= " AND c.organization = 0";
  $type_sql .= " AND organization = 0";
}

$list = $db->query("SELECT c.*, t.name AS type, o.status,
  (SELECT name FROM mfa_leads
    JOIN mfa_contacts ON mfa_leads.from_contact = mfa_contacts.id
    WHERE mfa_leads.to_contact = c.id ORDER BY mfa_leads.id DESC LIMIT 1) AS referral
FROM mfa_contacts c
  LEFT JOIN mfa_contacts_types t ON c.type = t.id
  JOIN mfa_status_options o ON c.status = o.id
WHERE c.dataset = $id $sql
  ORDER BY name
");

$all_flags = $db->query("SELECT f.* 
FROM mfa_contacts_flags f
  JOIN mfa_contacts c ON f.contact = c.id
WHERE c.dataset = $id $sql");

foreach ($all_flags as $row) {
  $flag_active[$row['contact']][$row['flag']] = true;
}

$types = $db->query("SELECT *,
  (SELECT COUNT(*) FROM mfa_contacts WHERE type = mfa_contacts_types.id $type_sql) as total
FROM mfa_contacts_types WHERE dataset = $id ORDER BY name");

foreach ($types as $row) {
  $overall_total += $row['total'];
}

$unclassified = $db->record("SELECT COUNT(*) AS total FROM mfa_contacts WHERE dataset = $id AND type IS NULL $type_sql");

if ($_GET['deleted']) {
  $print = "The contact has been deleted";
}

$status_options = $db->query("SELECT * FROM mfa_status_options ORDER BY id");
$flags = $db->query("SELECT * FROM mfa_special_flags ORDER BY name");

$specialties = array(
  1 => "Biomass extraction",
  2 => "All biomass",
  3 => "Freight",
  4 => "Metal imports and exports",
  5 => "Non-mineral extraction",
  6 => "Wood: all",
  7 => "Wood: production",
  8 => "Emissions to air",
  9 => "Emissions to water",
  10 => "Fossil fuels",
  11 => "Plastics - other products",
  12 => "Waste imports and exports",
  13 => "Wild fish catch",
  14 => "Retailer",
  15 => "Fruits",
  16 => "Imports and exports",
);

$organizations = $db->query("SELECT id,name FROM mfa_contacts WHERE dataset = $project AND organization = 1 ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Contacts | <?php echo SITENAME ?></title>
    <style type="text/css">
    table {width:100%;table-layout: fixed;}
    th,td{ white-space:nowrap; overflow:hidden; text-overflow: ellipsis; }
    .right{float:right;margin:6px 0 0}
    .table > tbody > tr > th{border-top:0}
    .row-name{width:auto}
    .row-edit{width:710px}
    .row-employer{width:200px}
    .row-status,.row-added{width:130px}
    .message{display:none;position:fixed;bottom:10px;right:10px;font-size:15px;color:#fff;font-weight:bold;padding:5px}
    </style>
    <script type="text/javascript">
    $(function(){
      $(".specialty").change(function(e){
        var id = $(this).data("id");
        $.post("ajax.contact.php?project=<?php echo $project ?>",{
          id: id,
          specialty: $(this).val(),
          dataType: "json"
        }, function(data) {
          if (data.response == "OK") {
            $(".message").html("#"+id+" - Information was saved").show().addClass("btn-success").removeClass("btn-danger");
          } else {
            $(".message").html("There was an error. The information could be saved.").addClass("btn-danger").show().removeClass("btn-succes");
          }
        },'json')
        .error(function(){
            $(".message").html("There was an error. Could not send data.").addClass("btn-danger").show().removeClass("btn-succes");
        });
        e.preventDefault();
      });
      $(".belongs_to").change(function(e){
        var id = $(this).data("id");
        $.post("ajax.contact.php?project=<?php echo $project ?>",{
          id: id,
          belongs_to: $(this).val(),
          dataType: "json"
        }, function(data) {
          if (data.response == "OK") {
            $(".message").html("#"+id+" - Information was saved").show().addClass("btn-success").removeClass("btn-danger");
          } else {
            $(".message").html("There was an error. The information could be saved.").addClass("btn-danger").show().removeClass("btn-succes");
          }
        },'json')
        .error(function(){
            $(".message").html("There was an error. Could not send data.").addClass("btn-danger").show().removeClass("btn-succes");
        });
        e.preventDefault();
      });
    });
    </script>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <div class="message btn"></div>

  <a href="omat/<?php echo $id ?>/contact/0" class="btn btn-success right">Add Contact</a>

  <?php foreach ($flags as $row) { ?>
    <form method="get" action="omat.contacts.php">
      <button 
      type="submit" class="btn btn-<?php echo $_GET['flag'] == $row['id'] ? 'primary' : 'default' ?> right" 
      name="flag" value="<?php echo $_GET['flag'] == $row['id'] ? 0 : $row['id'] ?>"><?php echo $row['name'] ?></button>
      <input type="hidden" name="project" value="<?php echo $project ?>" />
      <input type="hidden" name="status" value="<?php echo $status ?>" />
      <input type="hidden" name="type" value="<?php echo $type ?>" />
      <input type="hidden" name="organization" value="<?php echo $organization ?>" />
      <input type="hidden" name="edit" value="<?php echo $edit ?>" />
    </form>
  <?php } ?>

  <div class="dropdown right">
    <button class="btn btn-<?php echo $status ? 'primary' : 'default'; ?> dropdown-toggle" type="button" data-toggle="dropdown">
      <?php echo $_GET['status'] ? "Status: <strong>" . $status_name->status . "</strong>" : 'Filter by Status'; ?>
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
      <li role="presentation"<?php if (!$_GET['status']) { echo ' class="active"'; } ?>>
        <a role="menuitem" tabindex="-1" href="omat.contacts.php?project=<?php echo $id ?>&amp;type=<?php echo $type ?>&amp;flag=<?php echo $flag ?>&amp;organization=<?php echo $organization ?>&amp;edit=<?php echo $edit ?>">
          <?php if (!$_GET['status']) { ?>
            <i class="fa fa-check"></i>
          <?php } ?>
          All
        </a>
      </li>
    <?php foreach ($status_options as $row) { ?>
      <li role="presentation"<?php if ($_GET['status'] == $row['id']) { echo ' class="active"'; } ?>>
        <a role="menuitem" tabindex="-1" href="omat.contacts.php?project=<?php echo $id ?>&amp;type=<?php echo $type ?>&amp;status=<?php echo $row['id'] ?>&amp;flag=<?php echo $flag ?>&amp;organization=<?php echo $organization ?>&amp;edit=<?php echo $edit ?>">
          <?php if ($row['id'] == $_GET['status']) { ?>
            <i class="fa fa-check"></i>
          <?php } ?>
          <?php echo $row['status'] ?>
        </a>
      </li>
    <?php } ?>
    </ul>
  </div>

  <div class="dropdown right">
    <button class="btn btn-<?php echo $organization ? 'primary' : 'default'; ?> dropdown-toggle" type="button" data-toggle="dropdown">
      <?php echo $_GET['organization'] ? "Type: <strong>" . $org_label . "</strong>" : 'Filter by Type'; ?>
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
      <li role="presentation"<?php if (!$_GET['organization']) { echo ' class="active"'; } ?>>
        <a role="menuitem" tabindex="-1" href="omat.contacts.php?project=<?php echo $id ?>&amp;type=<?php echo $type ?>&amp;flag=<?php echo $flag ?>">
          <?php if (!$_GET['organization']) { ?>
            <i class="fa fa-check"></i>
          <?php } ?>
          All
        </a>
      </li>
      <li role="presentation"<?php if ($_GET['organization'] == 1) { echo ' class="active"'; } ?>>
        <a role="menuitem" tabindex="-1" href="omat.contacts.php?project=<?php echo $id ?>&amp;type=<?php echo $type ?>&amp;status=<?php echo $status ?>&amp;flag=<?php echo $flag ?>&amp;edit=<?php echo $edit ?>&amp;organization=1">
          <?php if (1 == $_GET['organization']) { ?>
            <i class="fa fa-check"></i>
          <?php } ?>
          Organizations
        </a>
      </li>
      <li role="presentation"<?php if ($_GET['organization'] == 2) { echo ' class="active"'; } ?>>
        <a role="menuitem" tabindex="-1" href="omat.contacts.php?project=<?php echo $id ?>&amp;type=<?php echo $type ?>&amp;status=<?php echo $status ?>&amp;flag=<?php echo $flag ?>&amp;edit=<?php echo $edit ?>&amp;organization=2">
          <?php if (2 == $_GET['organization']) { ?>
            <i class="fa fa-check"></i>
          <?php } ?>
          People
        </a>
      </li>
    </ul>
  </div>


  <h1>Contacts</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Contacts</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <?php if (count($types)) { ?>
    <ul class="nav nav-tabs" role="tablist">
      <li class="<?php echo !$_GET['type'] ? 'active' : 'regular'; ?>"><a href="omat.contacts.php?project=<?php echo $id ?>&amp;status=<?php echo $status ?>&amp;flag=<?php echo $flag ?>&amp;organization=<?php echo $organization ?>&amp;edit=<?php echo $edit ?>">All (<?php echo $overall_total+$unclassified->total ?>)</a></li>
    <?php foreach ($types as $row) { ?>
      <li class="<?php if ($_GET['type'] == $row['id']) { echo 'active'; } elseif (!$row['total']) { echo 'disabled'; } else { echo 'regular'; } ?>">
        <a href="omat.contacts.php?project=<?php echo $id ?>&amp;status=<?php echo $status ?>&amp;type=<?php echo $row['id'] ?>&amp;flag=<?php echo $flag ?>&amp;organization=<?php echo $organization ?>&amp;edit=<?php echo $edit ?>">
          <?php echo $row['name'] ?> (<?php echo $row['total'] ?>)
        </a>
      </li>
    <?php } ?>
    </ul>
  <?php } else { ?>
    <div class="alert alert-info">A total of <?php echo count($list) ?> contacts where found.</div>
  <?php } ?>

  <?php if (count($list)) { ?>

    <table class="table table-striped">
      <tr>
        <th class="row-name">Name</th>
        <?php if ($edit) { ?>
          <th class="row-edit">Edit</th>
        <?php } else { ?>
          <th class="row-employer">Employer</th>
          <th class="row-added">Added</th>
          <th class="row-status">Status</th>
        <?php } ?>
      </tr>
    <?php foreach ($list as $row) { ?>
      <tr>
        <td class="<?php echo $edit ? "medium" : "long"; ?>"><a href="omat/<?php echo $project ?>/viewcontact/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
        <?php if ($edit) { ?>
          <td>
            
            <select class="form-control specialty hide" data-id="<?php echo $row['id'] ?>">
              <option></option>
              <?php foreach ($specialties as $key => $value) { ?>
                <option <?php echo $key == $row['specialty'] ? 'selected' : ''; ?> value="<?php echo $key ?>"><?php echo $value ?></option>
              <?php } ?>
            </select>

            <select class="form-control belongs_to" data-id="<?php echo $row['id'] ?>">
              <option></option>
              <?php foreach ($organizations as $subrow) { ?>
                <option <?php echo $subrow['id'] == $row['belongs_to'] ? 'selected' : ''; ?> value="<?php echo $subrow['id'] ?>"><?php echo $subrow['name'] ?></option>
              <?php } ?>
            </select>

            <?php foreach ($flags as $flagrow) { ?>
              <a class="btn btn-<?php echo $flag_active[$row['id']][$flagrow['id']] ? 'success' : 'default'; ?>"><?php echo $flagrow['name'] ?></a>
            <?php } ?>
              
          </td>
        <?php } else { ?>
          <td class="medium"><?php echo $row['works_for_referral_organization'] ? $row['referral'] : $row['employer']; ?></td>
          <td><?php echo format_date("M d, Y", $row['created']) ?></td>
          <td>
            <?php echo $row['status'] ?>
          </td>
        <?php } ?>
      </tr>
    <?php } ?>
    </table>

  <?php } ?>

  <a href="omat/<?php echo $id ?>/contact/0" class="btn btn-success"><i class="fa fa-plus"></i> Add Contact</a>
  <a href="omat/<?php echo $project ?>/contacts/random-contact" class="btn btn-success"><i class="fa fa-random"></i> Open random pending contact</a>
  <a href="omat.contacts.php?project=<?php echo $id ?>&amp;status=<?php echo $status ?>&amp;flag=<?php echo $flag ?>&amp;organization=<?php echo $organization ?>&amp;edit=1" class="btn btn-success"><i class="fa fa-edit"></i> Mass edit contacts</a>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
