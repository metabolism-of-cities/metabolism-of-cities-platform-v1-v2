<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 3;
$sub_page = 3;

$project = (int)$_GET['project'];
$id = (int)$_GET['id'];
$year = (int)$_GET['year'];

function traceOrigin($id, $type) {
  global $db;
  $to = $type == "source" ? "to_source" : "to_contact";
  $info = $db->record("SELECT * FROM mfa_leads WHERE $to = $id");
  if ($info->from_contact) {
    $return = array('type' => 'contact', 'id' => $info->from_contact);
  } elseif ($info->from_source) {
    $return = array('type' => 'source', 'id' => $info->from_source);
  }
  return $return;
}

function traceOrigins($id, $type) {
  $array[] = array('id' => $id, 'type' => $type);
  do {
    $return = traceOrigin($id, $type);
    if ($return) {
      $array[] = $return;
      $id = $return['id'];
      $type = $return['type'];
    }
  } while ($return);
  return $array;
}

function trackTime($id, $type) {
  global $db;
  $list = $db->query("SELECT SUM(time) AS time, a.name
  FROM 
  mfa_activities_log l
    JOIN mfa_activities a ON l.activity = a.id
  WHERE l.$type = $id 
  GROUP BY a.name
  ORDER BY a.name");
  foreach ($list as $row) {
    $return[$row['name']] = $row['time'];
  }
  return $return;
}

$info = $db->record("SELECT mfa_materials.*, mfa_groups.name AS group_name
FROM mfa_materials 
  JOIN mfa_groups ON mfa_materials.mfa_group = mfa_groups.id
WHERE mfa_materials.id = $id");

$list = $db->query("SELECT mfa_data.*, mfa_sources.name AS source_name, mfa_scales.name AS scale_name
  FROM mfa_data
  LEFT JOIN mfa_sources ON mfa_data.source_id = mfa_sources.id
  LEFT JOIN mfa_scales ON mfa_data.scale = mfa_scales.id
WHERE material = $id AND mfa_data.include_in_totals = 1 AND year = $year LIMIT 1");

$dqi = $db->query("SELECT dqi_classifications.*, dqi_sections.name AS section_name
FROM dqi_classifications
  JOIN dqi_sections ON dqi_classifications.section = dqi_sections.id
WHERE dqi_sections.dataset = $project
ORDER BY dqi_sections.name, dqi_classifications.score");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->name ?> | <?php echo SITENAME ?></title>
    <script type="text/javascript">
    $(function(){
      $(".tooltip").tooltip({
        container: 'body' 
      });
      $(".btn").button();
    });
    </script>
    <style type="text/css">
    #dqi .btn{opacity:0.6}
    #dqi .active{opacity:1}
    a.reset{position:relative;left:10px;top:5px}
    input.small{width:100px}
     .multicolumn {
        -webkit-column-count: 3; /* Chrome, Safari, Opera */
        -moz-column-count: 3; /* Firefox */
        column-count: 3;
    } 
    .well h2{margin-top:0;margin-bottom:10px;font-size:1.5em}
    .arrow, .summary {
      width: 100px;
      background-color: #614789;
      float:left;
      margin-right:15px;
      position: relative;
      border: 4px solid #34264A;
      padding:20px 0;
      text-align:center;
      color:#fff;
    }
    .summary {
      position:relative;
      top:10px;
      font-weight:bold;
      font-size:1.2em;
      background:#ccc;
      border-color:#333;
      color:#333;
    }
    .arrow i {
      font-size:22px;
      display:block;
      margin-bottom:4px;
    }

    .arrow:after, .arrow:before {
      border: solid transparent;
      content: ' ';
      height: 0;
      left: 100%;
      position: absolute;
      width: 0;
    }

    .arrow:after {
      border-width: 9px;
      border-left-color: #614789;
      top: 33px;
    }

    .arrow:before {
      border-width: 14px;
      border-left-color: #34264A;
      top: 28px;
    }
    .contact {
      background-color: #428BCA;
      border: 4px solid #31495E;
    }

    .contact:after {
      border-left-color: #428BCA;
    }
    .contact:before {
      border-left-color: #31495E;
    }

    .arrow a {
      color:#fff;
    }

    h2 {
      font-size:20px;
      clear:both;
      padding-top:22px;
    }

    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>
    <?php echo $info->name ?>
  </h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/reports-tables">Data Tables</a></li>
    <li><a href="omat/<?php echo $project ?>/reports-table/<?php echo $info->mfa_group ?>"><?php echo $info->group_name ?></a></li>
    <li class="active"><?php echo $info->name ?></li>
  </ol>

  <h2>General Information</h2>

  <?php 
  foreach ($list as $row) { 

  $dqi_active = $db->query("SELECT classification FROM mfa_dqi WHERE data = {$row['id']}");
  foreach ($dqi_active as $subrow) {
    $active[$subrow['classification']] = true;
  }
  ?>

    <dl class="dl-horizontal">
      <dt>Data Point ID</dt>
      <dd><?php echo $row['id'] ?></dd>

      <dt>Year</dt>
      <dd><?php echo $row['year'] ?></dd>

      <dt>Value</dt>
      <dd><?php echo number_format($row['data'],2) ?></dd>

      <?php if ($row['source_id'] || $row['source']) { ?>

        <dt>Source</dt>
        <?php if ($row['source_id']) { ?>
          <dd>
            <a href="omat/<?php echo $project ?>/viewsource/<?php echo $row['source_id'] ?>">
              <?php echo $row['source_name'] ?>
            </a>
          </dd>
        <?php } else { ?>
          <dd><?php echo $row['source'] ?></dd>
        <?php } ?>

      <?php } ?>

      <?php if ($row['scale']) { ?>

        <dt>Scale</dt>
        <dd><?php echo $row['scale_name'] ?></dd>

      <?php } ?>

    </dl>

    <?php if (count($dqi)) { ?>

      <fieldset id="dqi">
        <legend>Data Quality Indicators</legend>

        <?php $dqisection = false; foreach ($dqi as $subrow) { ?>
          <?php if ($subrow['section'] != $dqi_section) { ?>
          <?php if ($dqi_section) { ?>
          </div>
          </div>
          </div><?php } ?>
          <div class="row">
            <div class="col-sm-3">
              <?php echo $subrow['section_name'] ?>
            </div>
            <div class="col-sm-9">
              <div class="btn-group group-<?php echo $subrow['section'] ?>" data-toggle="buttons">
          <?php } $dqi_section = $subrow['section']; ?>
          <label class="tooltip btn btn-default <?php echo $active[$subrow['id']] ? "active" : ''; ?>" data-toggle="tooltip" title="<?php echo $subrow['name'] ?>" data-placement="right">
            <input type="radio" id="q<?php echo $subrow['id'] ?>" name="quality[<?php echo $subrow['section'] ?>]" <?php echo $active[$subrow['id']] ? 'checked' : ''; ?> value="<?php echo $subrow['id'] ?>"> <?php echo $subrow['score'] ?>
          </label>
        <?php } ?>
        </div>
        </div>
        </div>
        
      </fieldset>
    <?php } ?>

    <?php if ($row['source_id']) { ?>

    <h2>Route to success</h2>

    <?php
      $origins = traceOrigins($row['source_id'], 'source');
      $origins = array_reverse($origins);
    ?>

    <?php 
    foreach ($origins as $value) { 
      $gettime = trackTime($value['id'], $value['type']);
      if (is_array($gettime)) {
        foreach ($gettime as $subkey => $subvalue) {
          $totaltime[$subkey] += $subvalue;
          $this_time += $subvalue;
          $overall_time += $subvalue;
        }
      }
    ?>

    <div class="arrow <?php echo $value['type']; ?>">
      <a href="omat/<?php echo $project ?>/view<?php echo $value['type'] ?>/<?php echo $value['id'] ?>">
      <i class="fa fa-<?php echo $value['type'] == 'contact' ? 'user' : 'file'; ?>"></i>
      <?php echo formatTime($this_time); $this_time = 0; ?>
      </a>
    </div>

    <?php } ?>

    <div class="summary">
      <?php echo formatTime($overall_time); ?>
    </div>

    <h2>Time breakdown</h2>

    <table class="table table-striped">
      <tr>
        <th>Activity</th>
        <th>Time</th>
      </tr>
      <?php $overall_time = 0; foreach ($totaltime as $key => $value) { ?>
      <tr>
        <td><?php echo $key ?></td>
        <td><?php echo formatTime($value); $overall_time += $value; ?></td>
      </tr>
      <?php } ?>
      <tr>
        <th>Total</th>
        <th><?php echo formatTime($overall_time) ?></th>
      </tr>
    </table>

    <?php } ?>

    <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
