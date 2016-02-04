<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 6;

$id = (int)$project;

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("DELETE FROM mfa_industries WHERE id = $delete AND dataset = $project LIMIT 1");
  $print = "The industry was deleted";
}

$list = $db->query("SELECT * FROM mfa_industries WHERE dataset = $project ORDER BY name");

if ($_GET['saved']) {
  $print = "Information was saved";
}

$scores = $db->query("SELECT s.* FROM mfa_industries_scores s
  JOIN mfa_industries i ON s.industry = i.id
WHERE i.dataset = $id AND type = 'mass'");
foreach ($scores as $row) {
  $scores[$row['industry']][$row['flow']] = $row['score'];
}

$time = array();

foreach ($list as $row) {
  $industry = $row['id'];
  $contacts = $db->query("SELECT id,name,
    (SELECT name FROM mfa_contacts c WHERE mfa_contacts.belongs_to = c.id) AS parent
  FROM mfa_contacts WHERE industry = $industry ORDER BY name");

  foreach ($contacts as $row) {
    $id = $row['id'];
    $time[$industry] += timeContact($id);
    getChildrenContacts($id, $industry);
  }

}

function getChildrenContacts($id, $parent) {
  global $db, $time;
  $children = $db->query("SELECT * FROM mfa_contacts WHERE belongs_to = $id");
  if (count($children)) {
    foreach ($children as $row) {
      $time[$parent] += timeContact($row['id']);
      getChildrenContacts($row['id'], $parent);
    }
  }
}

$max_time = max($time);

if ($_GET['relative']) {
  $relative = true;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Industries | <?php echo SITENAME ?></title>
    <style type="text/css">
    .btn-group .btn-default{opacity:0.2;cursor:normal}
    td.right{text-align:right}
    </style>
  </head>

  <body class="omat">

<?php require_once 'include.header.php'; ?>

  <a href="omat/<?php echo $project ?>/industrycomparison<?php echo $relative ? "" : "/relative"; ?>" class="pull-right btn btn-<?php echo $relative ? "primary" : "default"; ?>">
    Relative time
  </a>

  <h1>Industries</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/industries">Industries</a></li>
    <li class="active">Industry Comparison</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <div class="alert alert-info">
    <strong><?php echo count($list) ?></strong> industries found.
  </div>

  <?php if (count($list)) { ?>

    <table class="table table-striped">
      <tr>
        <th>Industry</th>
        <th>Extraction</th>
        <th>Import</th>
        <th>Export</th>
        <th>Output</th>
        <th>Time</th>
      </tr>
    <?php foreach ($list as $row) { ?>
      <tr>
        <td><a href="omat/<?php echo $project ?>/viewindustry/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
        <?php $options = array('extraction', 'import', 'export', 'output'); ?>
        <?php foreach ($options as $option) { ?>
        <td>
          <?php $score = $scores[$row['id']][$option]; ?>
          <?php if ($score) { ?>
            <div class="btn-group" role="group">
              <?php for ($i = 1; $i <= 5; $i++) { ?>
                <button type="button" class="btn btn-<?php echo $i > $score ? 'default' : 'primary'; ?>"><?php echo $i ?></button>
              <?php } ?>
            </div>
          <?php } ?>
        </td>
        <?php } ?>
        <td class="right">
        <?php if ($relative) { ?>
          <meter value="<?php echo $time[$row['id']] ?>" min="0" max="<?php echo $max_time ?>" title="<?php echo formatTime($time[$row['id']]) ?>">
        <?php } else { ?>
          <?php echo formatTime($time[$row['id']], true); ?>
        <?php } ?>
        </td>
      </tr>
    <?php } ?>
    </table>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
