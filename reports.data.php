<?php
if ($_GET['public_login']) {
  $public_login = true;
}
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 3;
$sub_page = 3;

$project = (int)$_GET['project'];
$id = (int)$_GET['id'];
$year = (int)$_GET['year'];

$projectinfo = $db->record("SELECT * FROM mfa_dataset WHERE id = $project");

function traceOrigin($id, $type) {
  global $db;
  $to = $type == "source" ? "to_source" : "to_contact";
  $info = $db->record("SELECT * FROM mfa_leads WHERE $to = $id ORDER BY id LIMIT 1");
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
WHERE material = $id AND mfa_data.include_in_totals = 1 AND year = $year");

// It is possible that several data points have been entered for the 
// same year for the same material (e.g. if there are two 
// sub materials and no sub division has been made. We get main 
// data from one of these data points, and show all the related values. 
// If data is different between data points (e.g. source or scale), 
// then sub categories should be made by the user instead. 

$single_info = $db->record("SELECT mfa_data.*, mfa_sources.name AS source_name, mfa_scales.name AS scale_name
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
    .col-sm-3 { white-space:nowrap; overflow:hidden; text-overflow: ellipsis; }
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>
    <?php echo $info->name ?>
  </h1>

  <ol class="breadcrumb">
      <?php if ($public_login) { ?>
          <li><a href="omat/<?php echo $project ?>/projectinfo"><?php echo $check->name ?></a></li>
      <?php } else { ?>
          <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
      <?php } ?>
    <li><a href="<?php echo $omat_link ?>/<?php echo $project ?>/reports-tables">Data Tables</a></li>
    <li><a href="<?php echo $omat_link ?>/<?php echo $project ?>/reports-table/<?php echo $info->mfa_group ?>"><?php echo $info->group_name ?></a></li>
    <li class="active"><?php echo $info->name ?></li>
  </ol>

  <h2>General Information</h2>

  <?php if (!$single_info) { ?>

    <div class="alert alert-danger">No data point found</div>

  <?php } else { ?>

    <?php 
    
    $dqi_active = $db->query("SELECT classification FROM mfa_dqi WHERE data = {$single_info->id}");
    foreach ($dqi_active as $subrow) {
      $active[$subrow['classification']] = true;
    }
    ?>

      <dl class="dl-horizontal">
        <dt>Data Point ID</dt>
        <dd><?php echo $single_info->id ?></dd>

        <dt>Material</dt>
        <dd><?php echo $info->name ?></dd>

        <dt>Year</dt>
        <dd><?php echo $single_info->year ?></dd>

        <dt>Value</dt>
        <?php $total = 0; foreach ($list as $row) { ?>
          <dd>
            <?php echo number_format($row['data'],$dataset->decimal_precision) ?> 
            <?php if ($row['multiplier'] != 1) { ?> x <?php echo $row['multiplier'] ?> = 
              <?php echo number_format($row['data']*$row['multiplier'],$dataset->decimal_precision) ?>
            <?php } ?>
          <?php echo $projectinfo->measurement ?></dd>
        <?php $total += $row['data']*$row['multiplier']; } ?>
        <?php if (count($list) > 1) { ?>
          <dd><strong>Total: <?php echo number_format($total,$dataset->decimal_precision) ?></strong></dd>
        <?php } ?>

        <?php if ($single_info->source_id || $single_info->source) { ?>

          <dt>Source</dt>
          <?php if ($single_info->source_id) { ?>
            <dd>
              <a href="<?php echo $omat_link ?>/<?php echo $project ?>/sourcedetails/<?php echo $single_info->source_id ?>">
                <?php echo $single_info->source_name ?>
              </a>
            </dd>
          <?php } else { ?>
            <dd><?php echo $single_info->source ?></dd>
          <?php } ?>

        <?php } ?>

        <?php if ($single_info->scale) { ?>

          <dt>Scale</dt>
          <dd><?php echo $single_info->scale_name ?></dd>

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

      <?php if ($single_info->source_id && !$public_login) { ?>

      <?php
        $origins = traceOrigins($single_info->source_id, 'source');
        $origins = array_reverse($origins);
      ?>

      <?php if (is_array($origins)) { ?>

      <div class="route">

        <h2>Route to success</h2>

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

      <div class="summary">
        <?php echo formatTime($overall_time); ?>
      </div>
      </div>

      <?php } ?>

      <?php if (count($origins) == 1 && !$overall_time) { ?>
        <script type="text/javascript">
        $(function(){
          $(".route").hide();
        });
        </script>
      <?php } ?>


      <?php if ($overall_time) { ?>

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

      <?php } ?>

    <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
