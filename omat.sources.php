<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 3;

$id = $project;

$type = (int)$_GET['type'];
$status = (int)$_GET['status'];

$sql = $type ? " AND c.type = $type" : false;
if ($status) {
  $sql .= " AND c.status = $status";
  $status_name = $db->record("SELECT * FROM mfa_status_options WHERE id = $status");
  $type_sql = "AND mfa_sources.status = $status";
}

if ($_GET['flag']) {
  $flag = (int)$_GET['flag'];
  $sql .= " AND EXISTS (SELECT * FROM mfa_sources_flags WHERE source = c.id AND flag = $flag)";
  $type_sql .= " AND EXISTS (SELECT * FROM mfa_sources_flags WHERE source = mfa_sources.id AND flag = $flag)";
}

if ($_GET['random-source']) {
  $source = $db->record("SELECT * FROM mfa_sources WHERE dataset = $project AND status = 1 ORDER BY RAND() LIMIT 1");
  if ($source->id) {
    header("Location: " . URL . "omat/$project/viewsource/{$source->id}");
    exit();
  }
}

$list = $db->query("SELECT c.*, t.name AS type, o.status,
  (SELECT name FROM mfa_leads
    JOIN mfa_sources ON mfa_leads.from_source = mfa_sources.id
    WHERE mfa_leads.to_source = c.id LIMIT 1) AS referral
FROM mfa_sources c
  LEFT JOIN mfa_sources_types t ON c.type = t.id
  JOIN mfa_status_options o ON c.status = o.id
WHERE c.dataset = $id $sql
  ORDER BY name
");

$types = $db->query("SELECT *,
  (SELECT COUNT(*) FROM mfa_sources WHERE type = mfa_sources_types.id $type_sql) as total
FROM mfa_sources_types WHERE dataset = $id ORDER BY name");

foreach ($types as $row) {
  $overall_total += $row['total'];
}

$unclassified = $db->record("SELECT COUNT(*) AS total FROM mfa_sources WHERE dataset = $id AND type IS NULL $type_sql");

if ($_GET['deleted']) {
  $print = "The source has been deleted";
}

$status_options = $db->query("SELECT * FROM mfa_status_options ORDER BY id");
$flags = $db->query("SELECT * FROM mfa_special_flags ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Sources | <?php echo SITENAME ?></title>
    <style type="text/css">
    table {width:100%;table-layout: fixed;}
    th,td{ white-space:nowrap; overflow:hidden; text-overflow: ellipsis; }
    .right{float:right;margin-left:6px;}
    .table > tbody > tr > th{border-top:0}
    .row-name{width:auto}
    .row-employer{width:200px}
    .row-status,.row-added{width:130px}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <a href="omat/<?php echo $id ?>/source/0" class="btn btn-success right">Add Source</a>

  <?php foreach ($flags as $row) { ?>
    <form method="get" action="omat.sources.php">
      <button 
      type="submit" class="btn btn-<?php echo $_GET['flag'] == $row['id'] ? 'primary' : 'default' ?> right" 
      name="flag" value="<?php echo $_GET['flag'] == $row['id'] ? 0 : $row['id'] ?>"><?php echo $row['name'] ?></button>
      <input type="hidden" name="project" value="<?php echo $project ?>" />
      <input type="hidden" name="status" value="<?php echo $status ?>" />
      <input type="hidden" name="type" value="<?php echo $type ?>" />
    </form>
  <?php } ?>

  <div class="dropdown right">
    <button class="btn btn-<?php echo $status ? 'primary' : 'default'; ?> dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
      <?php echo $_GET['status'] ? "Status: <strong>" . $status_name->status . "</strong>" : 'Filter by Status'; ?>
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
      <li role="presentation"<?php if (!$_GET['status']) { echo ' class="active"'; } ?>>
        <a role="menuitem" tabindex="-1" href="omat.sources.php?project=<?php echo $id ?>&amp;type=<?php echo $type ?>&amp;flag=<?php echo $flag ?>">
          <?php if (!$_GET['status']) { ?>
            <i class="fa fa-check"></i>
          <?php } ?>
          All
        </a>
      </li>
    <?php foreach ($status_options as $row) { ?>
      <li role="presentation"<?php if ($_GET['status'] == $row['id']) { echo ' class="active"'; } ?>>
        <a role="menuitem" tabindex="-1" href="omat.sources.php?project=<?php echo $id ?>&amp;type=<?php echo $type ?>&amp;status=<?php echo $row['id'] ?>&amp;flag=<?php echo $flag ?>">
          <?php if ($row['id'] == $_GET['status']) { ?>
            <i class="fa fa-check"></i>
          <?php } ?>
          <?php echo $row['status'] ?>
        </a>
      </li>
    <?php } ?>
    </ul>
  </div>


  <h1>Sources</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Sources</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <?php if (count($types)) { ?>
    <ul class="nav nav-tabs" role="tablist">
      <li class="<?php echo !$_GET['type'] ? 'active' : 'regular'; ?>"><a href="omat.sources.php?project=<?php echo $id ?>&amp;status=<?php echo $status ?>&amp;flag=<?php echo $flag ?>">All (<?php echo $overall_total+$unclassified->total ?>)</a></li>
    <?php foreach ($types as $row) { ?>
      <li class="<?php if ($_GET['type'] == $row['id']) { echo 'active'; } elseif (!$row['total']) { echo 'disabled'; } else { echo 'regular'; } ?>">
        <a href="omat.sources.php?project=<?php echo $id ?>&amp;status=<?php echo $status ?>&amp;type=<?php echo $row['id'] ?>&amp;flag=<?php echo $flag ?>">
          <?php echo $row['name'] ?> (<?php echo $row['total'] ?>)
        </a>
      </li>
    <?php } ?>
    </ul>
  <?php } else { ?>
    <div class="alert alert-info">A total of <?php echo count($list) ?> sources where found.</div>
  <?php } ?>

  <?php if (count($list)) { ?>

    <table class="table table-striped">
      <tr>
        <th class="row-name">Name</th>
        <th class="row-employer">Employer</th>
        <th class="row-added">Added</th>
        <th class="row-status">Status</th>
      </tr>
    <?php foreach ($list as $row) { ?>
      <tr>
        <td class="long"><a href="omat/<?php echo $project ?>/viewsource/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
        <td class="medium"><?php echo $row['works_for_referral_organization'] ? $row['referral'] : $row['employer']; ?></td>
        <td><?php echo format_date("M d, Y", $row['created']) ?></td>
        <td>
          <?php echo $row['status'] ?>
        </td>
      </tr>
    <?php } ?>
    </table>

  <?php } ?>

  <a href="omat/<?php echo $id ?>/source/0" class="btn btn-success">Add Source</a>
  <a href="omat/<?php echo $project ?>/sources/random-source" class="btn btn-success"><i class="fa fa-random"></i> Open random pending source</a>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
