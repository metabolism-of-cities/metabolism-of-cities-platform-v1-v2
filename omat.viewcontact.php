<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 2;

$id = (int)$_GET['id'];
$project = (int)$_GET['project'];

$info = $db->record("SELECT c.*, t.name AS industry, o.status AS status_name
FROM mfa_contacts c 
  LEFT JOIN mfa_industries t ON c.industry = t.id
  JOIN mfa_status_options o ON c.status = o.id
WHERE c.id = $id AND c.dataset = $project");

if ($_GET['activity-deleted']) {
  $print = "Activity has been deleted";
}

if (!count($info)) {
  die("This contact was not found");
}

if ($_GET['status']) {
  $status = (int)$_GET['status'];
  $post = array(
    'status' => $status,
  );
  $db->update("mfa_contacts",$post,"id = $id");
  header("Location: " . URL . "omat/$project/viewcontact/$id");
  exit();
}

if ($_GET['flag']) {
  $post = array(
    'flag' => (int)$_GET['flag'],
    'contact' => $id,
  );
  $db->insert("mfa_contacts_flags",$post);
  header("Location: " . URL . "omat/$project/viewcontact/$id");
  exit();
} elseif ($_GET['unflag']) {
  $unflag = (int)$_GET['unflag'];
  $db->query("DELETE FROM mfa_contacts_flags WHERE contact = $id AND flag = $unflag");
  header("Location: " . URL . "omat/$project/viewcontact/$id");
  exit();
}

if ($_GET['delete']) {
  $delete = (int)$_GET['id'];
  $db->query("DELETE FROM mfa_contacts WHERE id = $delete LIMIT 1");
  header("Location: " . URL . "omat/$project/contacts/deleted");
  exit();
}

$contact_leads = $db->query("SELECT 
  mfa_contacts.*
FROM mfa_leads
  JOIN mfa_contacts ON mfa_leads.to_contact = mfa_contacts.id
WHERE from_contact = $id ORDER BY mfa_contacts.name");

$sources_leads = $db->query("SELECT 
  mfa_sources.*
FROM mfa_leads
  JOIN mfa_sources ON mfa_leads.to_source = mfa_sources.id
WHERE from_contact = $id ORDER BY mfa_sources.name");

$referred_source = $db->query("SELECT 
  mfa_sources.*
FROM mfa_leads
  JOIN mfa_sources ON mfa_leads.from_source = mfa_sources.id
WHERE to_contact = $id");

$referred_contact = $db->query("SELECT 
  mfa_contacts.*
FROM mfa_leads
  JOIN mfa_contacts ON mfa_leads.from_contact = mfa_contacts.id
WHERE to_contact = $id");

$interactionlist = $db->query("SELECT * FROM mfa_activities WHERE dataset = $project ORDER BY name");

$interaction = $db->query("SELECT 
  l.*, a.name
FROM 
mfa_activities_log l
  JOIN mfa_activities a ON l.activity = a.id
WHERE l.contact = $id ORDER BY start DESC");

$flags = $db->query("SELECT *,
  (SELECT COUNT(*) FROM mfa_contacts_flags WHERE contact = $id AND flag = mfa_special_flags.id) AS active
FROM mfa_special_flags WHERE dataset IS NULL OR dataset = $project ORDER BY name");

$status_options = $db->query("SELECT * FROM mfa_status_options ORDER BY id");

$associations = $db->query("SELECT mfa_groups.name AS groupname, mfa_materials.name AS material, l.id,
  (SELECT mfa_groups.name FROM mfa_groups WHERE mfa_materials.mfa_group = mfa_groups.id) AS material_groupname
FROM mfa_material_links l
  LEFT JOIN mfa_groups ON l.mfa_group = mfa_groups.id
  LEFT JOIN mfa_materials ON l.material = mfa_materials.id
WHERE contact = $id");

$children = $db->query("SELECT * FROM mfa_contacts WHERE belongs_to = $id AND dataset = $project ORDER BY name");
$file_children = $db->query("SELECT * FROM mfa_sources WHERE belongs_to = $id AND dataset = $project ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->name ?> | <?php echo SITENAME ?></title>
    <style type="text/css">
    dd{margin-bottom:10px;}
    .form-inline select.small{width:140px}
    div.right,a.right{float:right;margin-left:5px}
    .padding-bottom-5{padding-bottom:5px}
    h2{font-size:1.3em;}
    #help{display:none;}
    .leads{margin-top:30px}
    #sourceleads{margin-top:53px}
    #error{display:none}
    #delete{margin-top:30px}
    #activitylist .makesmall{font-size:11px;opacity:0.7}
    .badge{margin-left:8px}
    .panel-title{font-weight:700}
    h1{clear:both;padding-top:10px}
    .fa-ul i{opacity:0.7}
    ol.breadcrumb{clear:both;margin-top:45px}
    </style>
    <script type="text/javascript">
    $(function(){
      $("#showhelp").click(function(e){
        e.preventDefault();
        $("#help").slideToggle('fast');
      });
      $("#start_timer").click(function(){
        if ($("#start_timer").is(":checked")) {
          $("#activity_time").attr("disabled", true);
        } else {
          $("#activity_time").attr("disabled", false);
        }
      });
      $("#addcontact").submit(function(e){
        $("#addcontact button").attr("disabled",true);
        if ($("#organization").is(":checked")) {
          organization = 1;
        } else {
          organization = 0;
        }
        if ($("#works_for_referral_organization").is(":checked")) {
          works_for_referral_organization = 1;
        } else {
          works_for_referral_organization = 0;
        }
        $.post("ajax.contact.php",{
          contact: <?php echo $id ?>,
          action: 'addcontact',
          name: $("#contact_name").val(),
          organization: organization,
          works_for_referral_organization: works_for_referral_organization,
          project: <?php echo $project ?>,
          dataType: "json"
        }, function(data) {
          if (data.response == "OK") {
            $("#contactleads").show().prepend(data.message);
            $("#addcontact button").attr("disabled",false).val("");
            $("#contact_name").val('');
          } else {
            $("#error").html("There was a problem saving the contact. Please refresh the page. Report this error if it persists. Error 243.");
            $("#error").show();
          }
        },'json')
        .error(function(){
          $("#error").html("There was a problem saving the contact. Please refresh the page. Report this error if it persists. Error 244.");
          $("#error").show();
        });
        e.preventDefault();
      });
      $("#addsource").submit(function(e){
        $("#addsource button").attr("disabled",true);
        $.post("ajax.contact.php",{
          contact: <?php echo $id ?>,
          action: 'addsource',
          name: $("#source_name").val(),
          project: <?php echo $project ?>,
          dataType: "json"
        }, function(data) {
          if (data.response == "OK") {
            $("#sourceleads").show().prepend(data.message);
            $("#addsource button").attr("disabled",false).val("");
            $("#source_name").val('');
          } else {
            $("#error").html("There was a problem saving the source. Please refresh the page. Report this error if it persists. Error 245.");
            $("#error").show();
          }
        },'json')
        .error(function(){
          $("#error").html("There was a problem saving the source. Please refresh the page. Report this error if it persists. Error 246.");
          $("#error").show();
        });
        e.preventDefault();
      });
      $("#addactivity").submit(function(e){
        $("#addactivity button").attr("disabled",true);
        if ($("#start_timer").is(":checked")) {
          timer = 1;
        } else {
          timer = 0;
        }
        $.post("ajax.contact.php",{
          contact: <?php echo $id ?>,
          action: 'addactivity',
          type: $("#activity_type").val(),
          project: <?php echo $project ?>,
          time: $("#activity_time").val(),
          timer: timer,
          dataType: "json"
        }, function(data) {
          if (data.response == "OK") {
            $("#activitylist").show().prepend(data.message);
            $("#addactivity button").attr("disabled",false).val("");
            $("#activity_time").val('');
          } else {
            $("#error").html("There was a problem saving the activity. Please refresh the page. Report this error if it persists. Error 247.");
            $("#error").show();
          }
        },'json')
        .error(function(){
          $("#error").html("There was a problem saving the activity. Please refresh the page. Report this error if it persists. Error 248.");
          $("#error").show();
        });
        e.preventDefault();
      });
    });
    </script>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>
  
  <div class="dropdown right">
    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
      Status: <?php echo $info->status_name; ?>
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
    <?php foreach ($status_options as $row) { ?>
      <li role="presentation"<?php if ($info->status == $row['id']) { echo ' class="active"'; } ?>>
        <a role="menuitem" tabindex="-1" href="omat/<?php echo $project ?>/viewcontact/<?php echo $id ?>/status/<?php echo $row['id'] ?>">
          <?php if ($row['id'] == $info->status) { ?>
            <i class="fa fa-check"></i>
          <?php } ?>
          <?php echo $row['status'] ?>
        </a>
      </li>
    <?php } ?>
    </ul>
  </div>

  <?php foreach ($flags as $row) { ?>
    <a href="omat/<?php echo $project ?>/viewcontact/<?php echo $info->id ?>/<?php echo $row['active'] ? "unflag" : "flag" ?>/<?php echo $row['id'] ?>" class="btn right <?php echo $row['active'] ? 'btn-success' : 'btn-default' ?>">
    <?php if ($row['active']) { ?><i class="fa fa-check"></i> <?php } ?>
    <?php echo $row['name'] ?></a>
  <?php } ?>

  <a href="omat/<?php echo $project ?>/contact/<?php echo $info->id ?>" class="btn btn-primary right">Edit</a>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/contacts">Contacts</a></li>
    <li><?php echo $info->name ?></li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <dl class="dl-horizontal">

    <dt>ID</dt>
    <dd><?php echo $id ?></dd>

    <dt>Name</dt>
    <dd>
      <?php if ($info->belongs_to) { hierarchyTree($info->belongs_to); ?>
        <?php krsort($ancestors); foreach ($ancestors as $key => $value) { ?>
          <a href="omat/<?php echo $project ?>/viewcontact/<?php echo $value[0] ?>"><?php echo $value[1] ?></a> &raquo;
        <?php } ?>
      <?php } ?>
      <?php echo $info->name ?>
    </dd>

    <dt>Record Type</dt>
    <dd>
    <i class="fa fa-<?php echo $info->organization ? "building-o" : "user"; ?>"></i> 
    <?php echo $info->organization ? "Organization" : "Individual" ?></dd>

    <?php if ($info->industry) { ?>
      <dt>Industry</dt>
      <dd><?php echo $info->industry ?></dd>
    <?php } ?>

    <?php if ($info->employer) { ?>

      <dt>Employer</dt>
      <dd><?php echo $info->employer ?></dd>

    <?php } ?>

    <?php if ($info->url) { ?>
      <dt>URL</dt>
      <dd><a href="<?php echo $info->url ?>"><?php echo $info->url ?></a></dd>
    <?php } ?>

    <?php if ($check->resource_management) { ?>
      <dt>Associations</dt>
      <?php foreach ($associations as $row) { ?>
        <dd>
          <?php echo $row['material'] ? $row['material_groupname'] : $row['groupname'] ?>
          <?php if ($row['material']) { ?> &raquo; <?php echo $row['material'] ?><?php } ?>
        </dd>
      <?php } ?>
      <dd><a href="omat/<?php echo $project ?>/materiallink/contact/<?php echo $id ?>">
      <i class="fa fa-external-link-square"></i> 
      Manage associations</a></dd>
    <?php } ?>
    
    <?php if ($info->details) { ?>
      <dt>Notes</dt>
      <dd><?php echo $info->details ?></dd>
    <?php } ?>

    <dt>Added</dt>
    <dd><?php echo format_date("M d, Y", $info->created) ?></dd>

    <?php if (count($referred_contact) || count($referred_source)) { ?>
      <dt><?php if ($info->works_for_referral_organization) { echo 'Works for'; } else { echo 'Referred to by'; } ?></dt>
      <?php if (is_array($referred_source)) { foreach ($referred_source as $row) { ?>
      <dd>
        <a href="omat/<?php echo $project ?>/viewsource/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a>
      </dd>
      <?php } } ?>
      <?php if (is_array($referred_contact)) { foreach ($referred_contact as $row) { ?>
      <dd>
        <a href="omat/<?php echo $project ?>/viewcontact/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a>
      </dd>
      <?php } } ?>
    <?php } ?>

  </dl>

  <?php if (count($children)) { ?>

    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Contacts belonging to <?php echo $info->name ?></h3>
      </div>
      <div class="panel-body">
        <ul class="fa-ul">
        <?php foreach ($children as $row) { ?>
          <li><i class="fa-li fa fa-<?php echo $row['organization'] ? 'building' : 'user'; ?>"></i><a href="omat/<?php echo $project ?>/viewcontact/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></li>
        <?php } ?>
        </ul>
      </div>
    </div>

  <?php } ?>

  <?php if (count($file_children)) { ?>

    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title">Files belonging to <?php echo $info->name ?></h3>
      </div>
      <div class="panel-body">
        <ul class="fa-ul">
        <?php foreach ($file_children as $row) { ?>
          <li><i class="fa-li fa fa-file"></i><a href="omat/<?php echo $project ?>/viewsource/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></li>
        <?php } ?>
        </ul>
      </div>
    </div>

  <?php } ?>

  <h1>
    Manage Leads and Activity Log
    <a href="#help" id="showhelp" class="right"><i class="fa fa-question-circle"></i></a>
  </h1>

  <div class="alert alert-info" id="help">
    Here you can add the other contacts and sources that <?php echo $info->name ?> has 
    referred you to. This not only helps you keep track of this information, but it enables
    OMAT to create detailed statistics of where information came from.<br />
    By logging interaction with <?php echo $info->name ?>, OMAT can provide more details on 
    how much time you invested in obtaining your data and provide insight into efficiency and
    effectiveness.
    <?php if (!$check->time_log) { ?><br />
    <strong>Note</strong>: before you can start logging time, you need to active this option
    in your Project Settings.
    <?php } ?>
  </div>

  <div class="alert alert-danger" id="error"></div>

  <div class="container-fluid">

    <div class="row">
    
      <div class="col-md-<?php echo $check->time_log ? 4 : 6 ?>">
        <h2>Leads: Contacts</h2>

        <form method="post" class="form-inline" id="addcontact">

          <div class="form-group">
            <label class="sr-only">Name</label>
            <input type="text" id="contact_name" class="form-control" placeholder="Name" required />
            <button type="submit" class="btn btn-success">Add</button>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" id="organization" value="1" /> This is an organization
            </label>
          </div>
          <?php if ($info->organization) { ?>
            <div class="checkbox">
              <label>
                <input type="checkbox" id="works_for_referral_organization" value="1" /> 
                  This contact works for <em><?php echo $info->name ?></em>
              </label>
            </div>
          <?php } ?>

        </form>

        <div class="leads list-group" id="contactleads">
          <?php foreach ($contact_leads as $row) { ?>
            <a 
              class="list-group-item<?php if ($row['status'] == 2) { echo ' list-group-item-success'; } elseif ($row['status'] == 5) { echo ' list-group-item-danger'; } ?>" 
              href="omat/<?php echo $project ?>/viewcontact/<?php echo $row['id'] ?>">
              <i class="fa fa-<?php echo $row['organization'] ? 'building' : 'user' ?>"></i>
              <?php echo $row['name'] ?>
            </a>
          <?php } ?>
        </div>

      </div>

      <div class="col-md-<?php echo $check->time_log ? 4 : 6 ?>">
        <h2>Leads: Sources</h2>

        <form method="post" class="form-inline" id="addsource">

          <div class="form-group">
            <label class="sr-only">Name</label>
            <input type="text" id="source_name" class="form-control" placeholder="Name" required />
            <button type="submit" class="btn btn-success">Add</button>
          </div>

        </form>

        <div class="leads list-group" id="sourceleads">
          <?php foreach ($sources_leads as $row) { ?>
            <a 
              class="list-group-item<?php if ($row['status'] == 2) { echo ' list-group-item-success'; } elseif ($row['status'] == 5) { echo ' list-group-item-danger'; } ?>" 
              href="omat/<?php echo $project ?>/viewsource/<?php echo $row['id'] ?>">
              <?php echo $row['name'] ?>
            </a>
          <?php } ?>
        </div>

      </div>

      <div class="col-md-4<?php echo !$check->time_log ? ' hide' : ''; ?>">
        <h2>Log Activity</h2>

        <?php if (!count($interactionlist)) { ?>

        <p>Before you can log activities, please <a href="omat/<?php echo $project ?>/maintenance-activities">define possible activities</a>.</p>

        <?php } else { ?>

        <form method="post" class="form-inline" id="addactivity">

          <div class="form-group padding-bottom-5">
            <label class="sr-only">Type</label>
              <select id="activity_type" class="form-control small" required>
                <?php foreach ($interactionlist as $row) { ?>
                  <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                <?php } ?>
              </select>
            <button type="submit" class="btn btn-success">Add</button>
          </div>

          <div class="form-group">
            <label class="sr-only">Time</label>
            <input type="text" id="activity_time" class="form-control" placeholder="Time, e.g. 40 of 0:40" required />
          </div>

          <div class="checkbox">
            <label>
              <input type="checkbox" id="start_timer" value="1" /> Start timer
            </label>
          </div>

        </form>

        <div class="leads list-group" id="activitylist">
          <?php $totaltime = 0; foreach ($interaction as $row) { $totaltime += $row['time']; ?>
            <a 
              class="list-group-item<?php if (!$row['end']) { echo ' list-group-item-warning'; } ?>"
              href="omat/<?php echo $project ?>/viewactivity/<?php echo $row['id'] ?>">
              <?php if (!$row['end']) { ?>
                <i class="fa fa-clock-o"></i> 
              <?php } else { ?>
                <span class="badge"><?php echo format_date("M d", $row['end']) ?></span>
              <?php } ?>
              <?php echo $row['name'] ?>
              <?php if ($row['end']) { ?>
              <span class="makesmall"><?php echo $row['time'] ?> min</span>
              <?php } else { ?><em class="makesmall">ongoing</em><?php } ?>
            </a>
          <?php } ?>
          <?php if (count($interaction)) { ?>
          <a
            class="list-group-item list-group-item-info"
            href="omat/<?php echo $project ?>/activityreport/<?php echo $id ?>/source"
          >
            <strong>Total time: 
            <?php echo formatTime($totaltime) ?>
            </strong>
          </a>
          <?php } ?>
        </div>

        <?php } ?>

      </div>

    </div>
  
  <a id="delete" href="omat/<?php echo $project ?>/viewcontact/<?php echo $info->id ?>/delete" onclick="javascript:return confirm('Are you sure?')" 
    class="btn btn-danger pull-right">
    <i class="fa fa-trash"></i>
    Delete this contact
  </a>

  </div>


  

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
