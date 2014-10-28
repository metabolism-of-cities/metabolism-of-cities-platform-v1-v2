<?php
require_once 'functions.php';
require_once 'functions.omat.php';

$section = 6;
$load_menu = 1;
$sub_page = 2;

$id = $project;

$list = $db->query("SELECT * FROM mfa_contacts WHERE dataset = $id");
$sourcelist = $db->query("SELECT * FROM mfa_sources WHERE dataset = $id");

foreach ($list as $row) {
  if ($row['belongs_to']) {
    $contacts[$row['belongs_to']][$row['id']] = $row['name'];
    $parent[$row['id']] = $row['belongs_to'];
  } else {
    $contactnames[$row['id']] = $row['name'];
  }
}

function makeTree($id, $belongs_to) {
  global $parent, $all;
  if ($parent[$belongs_to]) {
    $all[$id][] = $parent[$belongs_to];
    makeTree($id, $parent[$belongs_to]);
  }
}

if (is_array($sourcelist)) {
  foreach ($sourcelist as $row) {
    if ($row['belongs_to']) {
      $sources[$row['belongs_to']][$row['id']] = $row['name'];
    } else {
      $sourcenames[$row['id']] = $row['name'];
    }
  }
}

if ($check->time_log && is_array($parent)) {
  $show_time = true;

  foreach ($parent as $key => $value) {
    makeTree($key, $value);
    $all[$key][] = $value;
  }

  $activities = $db->query("SELECT a.time, a.source, a.contact, mfa_sources.belongs_to
  FROM mfa_activities_log a
    LEFT JOIN mfa_contacts ON a.contact = mfa_contacts.id
    LEFT JOIN mfa_sources ON a.source = mfa_sources.id
  WHERE mfa_contacts.dataset = $project OR mfa_sources.dataset = $project");

  foreach ($activities as $row) {
    if ($row['contact']) {
      $time['contact'][$row['contact']] += $row['time'];
      if (is_array($all[$row['contact']])) {
        foreach ($all[$row['contact']] as $key => $value) {
          $totaltime[$value] += $row['time'];
        }
      }
      $totaltime[$row['contact']] += $row['time'];
    } else {
      $time['source'][$row['source']] += $row['time'];
      if (is_array($all[$row['belongs_to']])) {
        foreach ($all[$row['belongs_to']] as $key => $value) {
          $totaltime[$value] += $row['time'];
        }
        $totaltime[$row['belongs_to']] += $row['time'];
      }
      $totaltime_source[$row['source']] += $row['time'];
    }
  }
}

if (is_array($contactnames)) {
  asort($contactnames);
}
if (is_array($sourcenames)) {
  asort($sourcenames);
}

function buildList($id) {
  global $contacts, $project, $sources, $totaltime, $totaltime_source;
  if (is_array($contacts[$id]) || is_array($sources[$id])) {
    echo '<ul>';
    if (is_array($contacts[$id])) {
      asort($contacts[$id]);
      foreach ($contacts[$id] as $key => $value) {
        echo '<li class="viewcontact"><a href="omat/'.$project.'/viewcontact/'.$key.'">' . $value . '</a>';
        echo ' <span class="time"><i class="fa fa-clock-o"></i> ' . formatTime($totaltime[$key]) . '</span>';
        if (is_array($contacts[$key]) || is_array($sources[$key])) {
          buildList($key);
        }
        echo '</li>';
      }
    }
    if (is_array($sources[$id])) {
      asort($sources[$id]);
      foreach ($sources[$id] as $key => $value) {
        echo '<li><i class="fa fa-file-o"></i> <a href="omat/'.$project.'/viewsource/'.$key.'">' . $value . '</a>';
        echo ' <span class="time"><i class="fa fa-clock-o"></i> ' . formatTime($totaltime_source[$key]) . '</span>';
        echo '</li>';
      }
    }
    echo '</ul>';
  }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Contacts and Sources | <?php echo SITENAME ?></title>
    <style type="text/css">
    #contacts li i{color:#333}
    </style>
    <script type="text/javascript">
    $(function(){
      $(".display a").click(function(e){
        e.preventDefault();
        var level = $(this).data("level");
        if (level == 1) {
          $("#contacts ul").hide();
        } else if (level == 2) {
          $("#contacts ul").show();
          $("#contacts ul ul").hide();
        } else if (level == 3) {
          $("#contacts ul").show();
          $("#contacts ul ul").show();
          $("#contacts ul ul ul").hide();
        } else if (level == 'all') {
          $("#contacts ul").show();
          $("#contacts ul ul").show();
          $("#contacts ul ul ul").show();
        }
        $(".display a").removeClass('btn-primary').addClass('btn-default');
        $(this).addClass('btn-primary').removeClass('btn-default');
      });
    });
    </script>
    <style type="text/css">
      <?php if ($show_time) { ?>
        .time{opacity:0.9;font-size:12px}
      <?php } else { ?>
        .time{display:none}
      <?php } ?>
      .btn-success{margin-left:5px}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <a href="omat/<?php echo $project ?>/contact/0" class="btn btn-success pull-right"><i class="fa fa-user"></i> Add contact</a>
  <a href="omat/<?php echo $project ?>/source/0" class="btn btn-success pull-right"><i class="fa fa-file"></i> Add source</a>

  <h1>Manage Resources</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Manage Resources</li>
  </ol>

  <p class="display">
    <a href="#" class="btn btn-default" data-level="1"><strong>1</strong> First level contacts only</a>
    <a href="#" class="btn btn-default" data-level="2"><strong>2</strong> Up to second level</a>
    <a href="#" class="btn btn-default" data-level="3"><strong>3</strong> Up to third level</a>
    <a href="#" class="btn btn-primary" data-level="all">Show all</a>
  </p>

  <?php if (!is_array($contactnames)) { ?>
    <div class="alert alert-warning">No contacts found yet.</div>
  <?php } else { ?>

    <ul id="contacts">
      <?php foreach ($contactnames as $key => $value) { ?>
        <li>
          <strong>
            <a href="omat/<?php echo $project ?>/viewcontact/<?php echo $key ?>">
              <?php echo $value ?>
            </a>
           </strong>
           <span class="time">
             <i class="fa fa-clock-o"></i> <?php echo formatTime($totaltime[$key]) ?>
           </span>
          <?php buildList($key) ?>
        </li>
      <?php } ?>
      <?php if (is_array($sourcenames)) { ?>
        <li><strong>Unclassified sources</strong><ul>
      <?php foreach ($sourcenames as $key => $value) { ?>
        <li class="viewsource">
          <i class="fa fa-file-pdf-o"></i> 
          <a href="omat/<?php echo $project ?>/viewsource/<?php echo $key ?>">
            <?php echo $value ?>
          </a>
          <span class="time">
             <i class="fa fa-clock-o"></i> <?php echo formatTime($totaltime_source[$key]) ?>
           </span>
        </li>
      <?php } ?>
      </ul>
      </li>
      <?php } ?>
    </ul>
  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
