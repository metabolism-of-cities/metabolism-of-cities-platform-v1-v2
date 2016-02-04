<?php
if ($_GET['public_login'] || $_GET['type'] == 'omat-public') {
  $public_login = true;
}
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 3;
$sub_page = 7;

$id = (int)$_GET['id'];
$project = (int)$_GET['project'];

$info = $db->record("SELECT s.*, t.name AS type, o.status AS status_name
FROM mfa_sources s
  LEFT JOIN mfa_sources_types t ON s.type = t.id
  JOIN mfa_status_options o ON s.status = o.id
WHERE s.id = $id AND s.dataset = $project");

if (!$info->id) {
  die("This source was not found");
}

$files = $db->query("SELECT * FROM mfa_files WHERE source = $id ORDER BY name");

function get_file_extension($source) {
  return strtolower(substr($source, strrpos($source, '.') + 1));
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->name ?> | <?php echo SITENAME ?></title>
    <style type="text/css">
    dd { margin-bottom:10px; }
    .form-inline select.small{width:140px}
    div.right,a.right{float:right;margin-left:5px}
    h2{font-size:1.3em;}
    #help{display:none;}
    .leads{margin-top:30px}
    #sourceleads{margin-top:53px}
    #error{display:none}
    #delete{margin-top:30px;float:right}
    #activitylist .makesmall{font-size:11px;opacity:0.7}
    .badge{margin-left:8px}
    .ellipsis th.short{width:90px;max-width:90px}
    .ellipsis th.shorter{width:70px;max-width:90px}
    .ellipsis td,.ellipsis th{width:230px;max-width:230px}
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
        $.post("ajax.contact.php",{
          source: <?php echo $id ?>,
          action: 'addcontact',
          name: $("#contact_name").val(),
          organization: organization,
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
          source: <?php echo $id ?>,
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
          source: <?php echo $id ?>,
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

  <body class="omat">

<?php require_once 'include.header.php'; ?>

  <h1><?php echo $info->name ?></h1>

  <ol class="breadcrumb">
      <?php if ($public_login) { ?>
          <li><a href="omat/<?php echo $project ?>/projectinfo"><?php echo $check->name ?></a></li>
      <?php } else { ?>
          <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
      <?php } ?>
    <li><a href="<?php echo $omat_link ?>/<?php echo $project ?>/reports-sources">Data Sources</a></li>
    <li class="active"><?php echo $info->name ?></li>
  </ol>

  <dl class="dl-horizontal">

    <dt>Source ID</dt>
    <dd><?php echo $id ?></dd>

    <?php if ($info->type) { ?>

      <dt>Type</dt>
      <dd><?php echo $info->type ?></dd>

    <?php } ?>

    <?php if ($info->url) { ?>

      <dt>URL</dt>
      <dd><a href="<?php echo $info->url ?>"><?php echo $info->url ?></a></dd>

    <?php } ?>

    <?php if ($info->details) { ?>
      <dt>Notes</dt>
      <dd><?php echo $info->details ?></dd>
    <?php } ?>

    <dt>Added</dt>
    <dd><?php echo format_date("M d, Y", $info->created) ?></dd>

    <?php if (count($referred_contact) || count($referred_source)) { ?>
      <dt>Referred to by</dt>
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

  <?php if (count($files)) { ?>
  <section>
    <h1>Files</h1>

    <?php if (count($files)) { ?>

    <table class="table table-striped ellipsis">
      <tr>
        <th class="long">File</th>
        <th class="short">Type</th>
        <th class="long">MD5 checksum</th>
        <th class="long">Uploaded</th>
      </tr>
    <?php foreach ($files as $row) { ?>
      <tr>
        <td>
        <?php if ($row['size']) { ?>
          <a href="<?php echo $omat_link ?>/<?php echo $project ?>/download/<?php echo $row['id'] ?>">
            <?php echo $row['name'] ? $row['name'] : 'Download'; ?>
          </a>
        <?php } elseif ($row['url']) { ?>
          <a href="<?php echo $row['url'] ?>"><?php echo $row['name'] ? $row['name'] : 'Visit website' ?></a>
        <?php } else { ?>
          <?php echo $row['name'] ?>
        <?php } ?>
          <?php if ($row['url'] && $row['size']) { ?>
            <a href="<?php echo $row['url'] ?>" title="Link to website"><i class="fa fa-link"></i></a>
          <?php } ?>
        </td>
        <td><?php echo strtoupper(get_file_extension($row['original_name'])) ?></td>
        <?php
          $file = UPLOAD_PATH . "$project.{$row['source']}.{$row['id']}";
        ?>
        <td><?php echo md5_file($file) ?></td>
        <td><?php echo format_date("M d, Y", $row['uploaded']) ?></td>
      </tr>
    <?php } ?>
    </table>

    <?php } ?>

  </section>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
