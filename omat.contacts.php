<?php
require_once 'functions.php';
require_once 'functions.omat.php';

if (LOCAL) {
  require_once 'include.capetown.php';
}

$section = 6;
$load_menu = 1;
$sub_page = 2;

$id = $project;

$subgroup = (int)$_GET['subgroup'];
$industry = (int)$_GET['industry'];

$list = $db->query("SELECT * FROM mfa_contacts WHERE dataset = $id");
$sourcelist = $db->query("SELECT * FROM mfa_sources WHERE dataset = $id");

foreach ($list as $row) {
  if ($row['belongs_to']) {
    $contacts[$row['belongs_to']][$row['id']] = $row['name'];
    $parent[$row['id']] = $row['belongs_to'];
  } else {
    $mainparent[$row['id']] = true;
    if ($subgroup && $row['id'] == $subgroup || !$subgroup) {
      $contactnames[$row['id']] = $row['name'];
    }
  }
}

if ($subgroup) {
  // A particular subgroup of contacts has been selected. We need to make sure we only
  // include activities from this subgroup and everything that falls under this subgroup
  $all_contacts = $db->query("SELECT id, belongs_to FROM mfa_contacts WHERE dataset = $id");
  foreach ($all_contacts as $row) {
    findFirstParent($row['id']);
    // This will create an array ($firstparents) that holds the highest level parent for each contact
  }
  foreach ($firstparents as $key => $value) {
    if ($value == $subgroup) {
      $allowed_contacts[$key] = true;
    }
  }
  $subgroupnames = array(
    329 => 'Province',
    336 => 'Academia',
    330 => 'City',
    334 => 'Gov',
    335 => 'Private',
  );
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

  $activities = $db->query("SELECT a.time, a.source, a.contact, mfa_sources.belongs_to,
    mfa_activities.name AS activityname, a.activity
  FROM mfa_activities_log a
    LEFT JOIN mfa_contacts ON a.contact = mfa_contacts.id
    LEFT JOIN mfa_sources ON a.source = mfa_sources.id
    JOIN mfa_activities ON a.activity = mfa_activities.id
  WHERE mfa_contacts.dataset = $project OR mfa_sources.dataset = $project
    ORDER BY FIELD(a.activity, 6,7,10,21,22,12,8,9)
  ");

  foreach ($activities as $row) {
    if ($row['activity'] == "Phone call" && $subgroup) {
      $row['activity'] = "Mail";
    }
    
    $include_in_totals = true;
    if ($subgroup) {
      if ($row['contact'] && !$allowed_contacts[$row['contact']]) {
        $include_in_totals = false;
      }
      if ($row['source'] && !$allowed_contacts[$row['belongs_to']]) {
        $include_in_totals = false;
      }
    }
    if ($include_in_totals) {
      $totalactivitytime[$row['activity']] += $row['time'];
      $totalactivitycount[$row['activity']] ++;
      $activityname[$row['activity']] = $row['activityname'];
      if ($row['contact']) {
        $totalcontacts[$row['contact']] = true;
      } elseif ($row['source']) {
        $totalsources[$row['source']] = true;
      }
    }

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
      }
      $totaltime[$row['belongs_to']] += $row['time'];
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

// For CT:
if (LOCAL) {
  unset($sources);
}

function buildList($id) {
  // Last four are CT only
  global $contacts, $project, $sources, $totaltime, $totaltime_source;
  if (is_array($contacts[$id]) || is_array($sources[$id])) {
    echo '<ul>';
    if (is_array($contacts[$id])) {
      asort($contacts[$id]);
      foreach ($contacts[$id] as $key => $value) {
        echo '<li class="viewcontact time-'.$totaltime[$key].'"><a href="omat/'.$project.'/viewcontact/'.$key.'">' . $value . '</a>';
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
        echo '<li class="time-'.$totaltime_source[$key].'"><i class="fa fa-file-o"></i> <a href="omat/'.$project.'/viewsource/'.$key.'">' . $value . '</a>';
        echo ' <span class="time"><i class="fa fa-clock-o"></i> ' . formatTime($totaltime_source[$key]) . '</span>';
        echo '</li>';
      }
    }
    echo '</ul>';
  }
}

$fullwidth = 500;

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
      <?php if ($_GET['single']) { ?>
        $("#firstlevel").click();
      <?php } ?>
      <?php if ($_GET['subgroup']) { ?>
        $("#secondlevel").click();
      <?php } ?>
        $(".latex").click(function(){
          $(".showlatex").toggle('fast');
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
      .well h2 { margin-top:0;font-size:19px}
      .activityname{display:inline-block;width:100px;overflow:hidden;height:1.3em;text-align:right;padding-right:5px}
      .showbar {
        display:inline-block;
        margin:0 10px;
        background:#CF4803;
        border:1px solid #ccc;
        height:1.2em;
      }
      .well table{margin-top:30px;display:none}
      .activitybars{list-style:none;margin-top:20px}
      .totalblock{list-style:none;margin-left:115px}
      .totalblock li{display:inline-block;margin-right:30px}
      <?php if ($_GET['active']) { ?>
        .time-,.time-0{display:none}
      <?php } ?>
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <a href="omat/<?php echo $project ?>/contact/0" class="btn btn-success pull-right"><i class="fa fa-user"></i> Add contact</a>
  <a href="omat/<?php echo $project ?>/source/0" class="btn btn-success pull-right"><i class="fa fa-file"></i> Add source</a>

  <div class="message btn"></div>

  <h1>Manage Resources</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Manage Resources</li>
  </ol>

  <p class="display">
    <a href="#" id="firstlevel" class="btn btn-default" data-level="1"><strong>1</strong> First level contacts only</a>
    <a href="#" id="secondlevel" class="btn btn-default" data-level="2"><strong>2</strong> Up to second level</a>
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
             <i class="fa fa-clock-o"></i> 
             <a href="omat/<?php echo $project ?>/contacts/subgroup/<?php echo $key ?>">
               <?php echo formatTime($totaltime[$key]); $overall_total += $totaltime[$key]; ?>
             </a>
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
             <i class="fa fa-clock-o"></i> <?php echo formatTime($totaltime_source[$key]); $overall_total += $totaltime_source[$key]; ?>
           </span>
        </li>
      <?php } ?>
      </ul>
      </li>
      <?php } ?>
    </ul>
  <?php } ?>


  <?php if ($overall_total) { ?>
  <div class="well">
    <h2 class="hide">Totals</h2>
    <ul class="totalblock">
      <li>
        <strong>Time</strong>
        <i class="fa fa-clock-o"></i> <?php echo formatTime($overall_total) ?>
      </li>
      <li><strong>Contacts</strong>: <?php echo count($totalcontacts) ?></li>
      <li><strong>Sources</strong>: <?php echo count($totalsources) ?></li>
    </ul>

    <ul class="activitybars">
    <?php foreach ($totalactivitytime as $key => $value) { ?>
      <li>
        <span class="activityname">
          <?php echo $activityname[$key] ?>
        </span>
        <span class="showbar" style="width:<?php echo $fullwidth*($value/max($totalactivitytime)) ?>px">
        </span>
        <i class="fa fa-clock-o"></i>
        <?php echo formatTime($value) ?>
      </li>
    <?php } ?>
    </ul>

    <table class="table table-striped">
      <tr>
        <th>Activity</th>
        <th>Time</th>
        <th>Quantity</th>
        <th>Average time</th>
      </tr>
      <?php foreach ($totalactivitytime as $key => $value) { ?>
        <tr>
          <td><a href="omat/<?php echo $project ?>/reports-activities/<?php echo $key ?>/<?php echo $subgroup ?>"><?php echo $activityname[$key] ?></a></td>
          <td><?php echo formatTime($value) ?></td>
          <td><?php echo $totalactivitycount[$key] ?></td>
          <td><?php $avg[$key] = (int)$value/$totalactivitycount[$key]; echo formatTime($avg[$key]) ?></td>
        </tr>
      <?php } ?>
    </table>

  </div>

  <?php if (LOCAL) { ?>
  <?php
    $names = array(
      6 => 'Mail',
      7 => 'Meeting',
      8 => 'Background',
      9 => 'Calls',
      10 => 'Transport',
      12 => 'Calc',
      21 => 'Reading',
      22 => 'Admin',
    );
    $type = $subgroupnames[$subgroup];
  ?>
  <p><img src="img/tex.jpg" class="latex" title="Show this table in LaTeX, ready to copy and paste" alt="" /></p>

  <pre class="showlatex">
  
\begin{figure}[H]
  \centering
  \rowcolors{1}{white}{lightgrey}
  \begin{tabularx}{10cm}{llll}
  \arrayrulecolor{darkgrey}\hline
  \hline
    \textbf{\textcolor{darkgrey}{Activity}} &amp;
    \textbf{\textcolor{darkgrey}{Time}} &amp;
    \textbf{\textcolor{darkgrey}{Quantity}} &amp; 
    \textbf{\textcolor{darkgrey}{Average time}} \\
    \hline
      <?php foreach ($totalactivitytime as $key => $value) { ?>
        <?php echo $activityname[$key] ?> &amp; <?php echo formatTime($value) ?> &amp; <?php echo $totalactivitycount[$key] ?> &amp; <?php echo formatTime($avg[$key]) ?> \\
      <?php } ?>
  \bottomrule
  \end{tabularx}
  \caption{Breakdown of activities}
\end{figure}

\newcommand{\time<?php echo $type ?>}{<?php echo formatTime($overall_total) ?>}
<?php foreach ($totalactivitytime as $key => $value) { 
?>
\newcommand{\time<?php echo $type ?><?php echo $names[$key] ?>}{<?php echo formatTime($value) ?>}
\newcommand{\count<?php echo $type ?><?php echo $names[$key] ?>}{<?php echo $totalactivitycount[$key] ?>}
\newcommand{\average<?php echo $type ?><?php echo $names[$key] ?>}{<?php echo formatTime($avg[$key]) ?>}
<?php } ?>
\newcommand{\contacts<?php echo $type ?>}{<?php echo count($totalcontacts) ?>}
\newcommand{\sources<?php echo $type ?>}{<?php echo count($totalsources) ?>}

<?php
// CT MFA
if (LOCAL) {
  $check = $db->record("SELECT COUNT(*) AS total FROM mfa_contacts WHERE dataset = $id AND folder = 0");
  $checkSources = $db->record("SELECT COUNT(*) AS total FROM mfa_sources WHERE dataset = $id");

?>
\newcommand{\contactsAll<?php echo $type ?>}{<?php echo $check->total ?>}
\newcommand{\sourcesAll<?php echo $type ?>}{<?php echo $checkSources->total ?>}
<?php }
// End CT MFA
?>
    </pre>
  <?php } ?>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
