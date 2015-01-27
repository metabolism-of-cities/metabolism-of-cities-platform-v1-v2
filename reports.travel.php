<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 3;
$sub_page = 5;

$type = (int)$_GET['type'];
$join = "JOIN";
if ($type) {
  $sql = " AND mfa_transportation.transportation_mode = $type";
} elseif ($_GET['show-all']) {
  $join = "LEFT JOIN";
  $sql = " AND a.name LIKE '%transport%'";
  $print = "This feature shows all the activities that have the word 'transport' in them";
}

$list = $db->query("SELECT l.*, a.name AS activity_name,
  mfa_sources.name AS source_name,
  mfa_contacts.name AS contact_name,
  modes.name AS mode,
  mfa_transportation.*,
  l.id
FROM mfa_activities_log l
  $join mfa_transportation ON mfa_transportation.activity = l.id
  $join mfa_transportation_modes modes ON mfa_transportation.transportation_mode = modes.id
  $join mfa_activities a ON l.activity = a.id
  LEFT JOIN mfa_sources ON l.source = mfa_sources.id
  LEFT JOIN mfa_contacts ON l.contact = mfa_contacts.id
WHERE a.dataset = $project $sql ORDER BY l.end");

$types = $db->query("SELECT * FROM mfa_transportation_modes ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Travel Log | <?php echo SITENAME ?></title>
    <style type="text/css">
    select.form-control{width:120px;display:inline}
    </style>
    <script type="text/javascript">
    $(function(){
      $(".latex").click(function(){
        $(".showlatex").toggle('fast');
      });
    });
    </script>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <a href="omat/<?php echo $project ?>/reports-travel<?php if (!$_GET['show-all']) { echo '/show-all'; } ?>" class="btn pull-right btn-<?php echo $_GET['show-all'] ? 'primary' : 'default'; ?>">
    Show all transport-related activities
  </a>
  <h1>Travel Log</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Travel Log</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <form class="form form-horizontal" action="reports.travel.php">

  <p>
    <select name="type" class="form-control">
      <option value=""></option>
      <?php foreach ($types as $row) { ?>
        <option value="<?php echo $row['id'] ?>"<?php if ($row['id'] == $type) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
      <?php } ?>
    </select>

    <button type="submit" class="btn btn-primary">Filter</button>
    <input type="hidden" name="project" value="<?php echo $project ?>" />

  </p>

  </form>

  <?php if (count($list)) { ?>

  <div class="alert alert-info">
    <?php echo count($list) ?> records found.
  </div>

  <table class="table table-striped ellipsis">
    <tr>
      <th>ID</th>
      <th>Related To</th>
      <th>Mode</th>
      <th>Time</th>
      <th>Distance</th>
      <th>Speed</th>
      <th>Cost</th>
    </tr>
    <?php foreach ($list as $row) { ?>
    <?php
      $totaldistance[$row['mode']] += $row['distance'];
      $totaltime[$row['mode']] += $row['time'];
      $numberoftrips[$row['mode']]++;
    ?>
    <tr>
      <td><a href="omat/<?php echo $project ?>/viewactivity/<?php echo $row['id'] ?>"><?php echo $row['id'] ?></a></td>
      <td>
        <?php if ($row['contact']) { ?>
          <a href="omat/<?php echo $project ?>/viewcontact/<?php echo $row['contact'] ?>"><?php echo $row['contact_name'] ?></a>
        <?php } else { ?>
          <a href="omat/<?php echo $project ?>/viewsource/<?php echo $row['source'] ?>"><?php echo $row['source_name'] ?></a>
        <?php } ?>
      </td>
      <td><?php echo $row['mode'] ?></td>
      <td><?php echo formatTime($row['time']); $time += $row['time']; ?></td>
      <td><?php echo $row['distance']; $distance += $row['distance']; ?> km</td>
      <td><?php echo number_format($row['distance']/$row['time']*60,1) ?> km/h</td>
      <td><?php echo number_format($row['cost'],2); $cost += $row['cost']; ?></td>
    </tr>
  <?php } ?>
  <tr>
    <th colspan="3">Total</th>
    <th><?php echo formatTime($time) ?></th>
    <th><?php echo $distance ?> km</th>
    <th><?php echo number_format($distance/$time*60,1) ?> km/h</th>
    <th><?php echo number_format($cost,2) ?></th>
  </tr>
  </table>

  <h2>Summary</h2>

  <table class="table table-striped">
    <tr>
      <th>Mode</th>
      <th>Total distance</th>
      <th>Total time</th>
      <th>Average speed</th>
      <th>Number of journeys</th>
    </tr>
    <?php foreach ($totaldistance as $key => $value) { ?>
      <tr>
        <td><?php echo $key ?></td>
        <td><?php echo $value ?></td>
        <td><?php echo formatTime($totaltime[$key]) ?></td>
        <td><?php echo number_format($value/$totaltime[$key]*60,1) ?> km/h</td>
        <td><?php echo $numberoftrips[$key] ?></td>
      </tr>
    <?php } ?>
  </table>

  <p><img src="img/tex.jpg" class="latex" title="Show this table in LaTeX, ready to copy and paste" alt="" /></p>

  <pre class="showlatex">
  
\begin{figure}
  \centering
  \rowcolors{1}{white}{lightgrey}
  \begin{tabular}{10cm}{lllll}
  \arrayrulecolor{darkgrey}\hline
  \hline
    \textbf{\textcolor{darkgrey}{Mode}} &amp;
    \textbf{\textcolor{darkgrey}{Total distance}} &amp;
    \textbf{\textcolor{darkgrey}{Total time}} &amp; 
    \textbf{\textcolor{darkgrey}{Average speed}} &amp; 
    \textbf{\textcolor{darkgrey}{Number of journeys}} \\
    \hline
    <?php foreach ($totaldistance as $key => $value) { ?>
     <?php echo $key ?> &amp; <?php echo number_format($value,1) ?> km &amp; <?php echo formatTime($totaltime[$key]) ?> &amp; <?php echo number_format($value/$totaltime[$key]*60,1) ?> km/h &amp; <?php echo $numberoftrips[$key] ?> \\
    <?php } ?>
  \bottomrule
  \end{tabular}
  \caption{Transportation distance and time}
\end{figure}

  </pre>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
