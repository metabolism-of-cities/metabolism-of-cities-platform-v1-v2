<?php
if ($_GET['public_login']) {
  $public_login = true;
}
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 3;
$sub_page = 3;

$values_only = (int)$_GET['values-only'];
if ($values_only) {
  $_GET['id'] = $values_only;
}

$id = (int)$_GET['id'];
$dataset = $db->record("SELECT * FROM mfa_dataset WHERE id = $project");

if (!$dataset->year_start || !$dataset->year_end) {
  $error = "You have not set the start and end year of your dataset. Set this first";
}

$list = $db->query("SELECT *,
  (SELECT COUNT(*) FROM mfa_materials m WHERE m.mfa_group = $id AND m.code LIKE CONCAT(mfa_materials.code, '.%')) AS subcategories 
FROM mfa_materials WHERE mfa_group = $id ORDER BY mfa_materials.code");

$years = range($dataset->year_start, $dataset->year_end);

$tables = $db->query("SELECT * FROM mfa_groups WHERE dataset = $project ORDER BY section");

$info = $db->record("SELECT * FROM mfa_groups WHERE id = $id AND dataset = $project");

$dataresults = $db->query("SELECT SUM(data*multiplier) AS total, mfa_data.year, mfa_data.material,
  mfa_materials.code
  FROM mfa_data
  JOIN mfa_materials ON mfa_data.material = mfa_materials.id
WHERE mfa_materials.mfa_group = $id AND mfa_data.include_in_totals = 1
GROUP BY mfa_materials.code, mfa_data.year");

if (count($dataresults)) {
  foreach ($dataresults as $row) {
    $data[$row['year']][$row['material']] = $row['total'];
    $overall_total[$row['year']] += $row['total'];
    $explode = explode(".", $row['code']);
    if (is_array($explode)) {
      unset($code);
      foreach ($explode as $value) {
        $code .= $code ? ".$value" : $value;
        $total[$code][$row['year']] += $row['total'];
      }
    }
  }
}

$population_list = $db->query("SELECT * FROM mfa_population WHERE dataset = $project");

foreach ($population_list as $row) {
  $population[$row['year']] = $row['population'];
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->section ?>. <?php echo $info->name ?> | <?php echo SITENAME ?></title>
    <script type="text/javascript">
    $(function(){
      $(".display a").click(function(e){
        e.preventDefault();
        var level = $(this).data("level");
        if (level == 1) {
          $("table tr.level1").show();
          $("table tr.level2").hide();
          $("table tr.level3").hide();
          $("table tr.level4").hide();
        } else if (level == 2) {
          $("table tr.level1").show();
          $("table tr.level2").show();
          $("table tr.level3").hide();
          $("table tr.level4").hide();
        } else if (level == 3) {
          $("table tr.level1").show();
          $("table tr.level2").show();
          $("table tr.level3").show();
          $("table tr.level4").hide();
        } else if (level == 'all') {
          $("table tr").show();
        }
        $(".display a").removeClass('btn-primary').addClass('btn-default');
        $(this).addClass('btn-primary').removeClass('btn-default');
      });
    });
    </script>
    <style type="text/css">
    h2{font-size:23px}
    .moreinfo{opacity:0.7}
    .moreinfo:hover{opacity:1}
    #chart{height:400px}
    table tr.level2, table tr.level3, table tr.level4{display:none}
    .cut{
      <?php if (count($years) > 2) { ?>
      max-width: 200px;
      <?php } else { ?>
      max-width: 400px;
      <?php } ?>
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      display:inline-block;
    }
    .textright {
      text-align:right;
    }
    .progress-bg {
      background:url(img/progress.png); /* Fancier bar? Perhaps give people a choice */
      background:url(img/green.png);
      background-repeat:no-repeat;
      background-position:3px 6px;
    }
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <a href="<?php echo $omat_link ?>/<?php echo $project ?>/reports-table/<?php echo $id ?><?php echo $values_only ? '' : '/values-only'; ?>"
  class="printhide btn btn-<?php echo $values_only ? 'info' : 'default'; ?> pull-right">
    <?php
      if ($values_only) {
    ?>
      <i class="fa fa-check"></i>
    <?php } ?>
    Hide absent data categories
  </a>

  <h1>
    <?php echo $info->section ?>.
    <?php echo $info->name ?>
  </h1>

  <ol class="breadcrumb">
      <?php if ($public_login) { ?>
        <li><a href="omat/<?php echo $project ?>/projectinfo"><?php echo $check->name ?></a></li>
      <?php } else { ?>
        <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
      <?php } ?>
    <li><a href="<?php echo $omat_link ?>/<?php echo $project ?>/reports-tables">Data Tables</a></li>
    <li class="active"><?php echo $info->section ?>. <?php echo $info->name ?></li>
  </ol>

  <p class="display">
    <a href="#" class="btn btn-primary" data-level="1"><strong>1</strong> First level categories only</a>
    <a href="#" class="btn btn-default" data-level="2"><strong>2</strong> Up to second level</a>
    <a href="#" class="btn btn-default" data-level="3"><strong>3</strong> Up to third level</a>
    <a href="#" class="btn btn-default" data-level="all">Show all</a>
  </p>

  <table class="table table-striped data">
    <tr>
      <th></th>
      <?php foreach ($years as $year) { ?>
        <th class="textright">
          <?php echo $year ?><br />
          (<?php echo $dataset->measurement ?>)
        </th>
        <?php if ($population[$year]) { $extra_th++; ?>
          <th class="textright">
            <?php echo $year ?> - per cap.<br />
            (<?php echo $dataset->measurement ?>/1000)
          </th>
        <?php } ?>
      <?php } ?>
    </tr>
    <?php $count = 0; ?>
    <?php foreach ($list as $row) { $count++; $all_zero = true; ?>
    <tr class="level<?php echo substr_count($row['code'], ".")+1 ?>" id="row<?php echo $count ?>">
      <td style="padding-left:<?php echo strlen($row['code'])*10; ?>px">
        <span class="cut"><?php echo $row['code'] ?>. <?php echo $row['name'] ?></span>
      </td>
      <?php foreach ($years as $year) { ?>
      <?php 
        $datapoint = $data[$year][$row['id']];
        $final[$year] += $datapoint;
        if ($overall_total[$year]) {
          if (!$row['subcategories'] || $datapoint) {
            $width = $datapoint/$overall_total[$year]*100;
          } else {
            $width = ($total[$row['code']][$year]/$overall_total[$year])*100;
          }
        } else {
          $width = 0;
        }
      ?>
        <td class="textright progress-bg" style="background-size:<?php echo $width ?>% 25px">
        <?php if (!$row['subcategories'] || $datapoint) { ?>
          <a href="<?php echo $omat_link ?>/<?php echo $project ?>/reports-data/<?php echo $year ?>/<?php echo $row['id'] ?>">
            <?php $data_print = $datapoint; ?>
            <?php echo number_format($data_print,$dataset->decimal_precision); ?>
            <?php if ($datapoint > 0) { $all_zero = false; } ?>
          </a>
        <?php } else { ?>
          <?php $data_print = $total[$row['code']][$year]; ?>
          <?php echo number_format($data_print, $dataset->decimal_precision) ?>
            <?php if ($total[$row['code']][$year] > 0) { $all_zero = false; } ?>
        <?php } ?>
        </td>
        <?php if ($population[$year]) { ?>
          <td class="textright"><?php echo number_format($data_print/$population[$year]*1000, $dataset->decimal_precision) ?></td>          
        <?php } ?>
      <?php } ?>
      <?php if ($all_zero && $values_only) { $hiderow[] = $count; } ?>
      </tr>
    <?php } ?>

    <tr>
      <th><?php echo $info->name ?></th>
      <?php foreach ($years as $year) { ?>
        <th class="textright progress-bg" style="background-size:100% 25px"><?php echo number_format($final[$year],$dataset->decimal_precision) ?></th>
        <?php if ($population[$year]) { ?>
          <th class="textright"><?php echo number_format($final[$year]/$population[$year]*1000,$dataset->decimal_precision) ?></th>
        <?php } ?>
      <?php } ?>
    </tr>
  </table>

  <?php if ($dataset->banner_text) { ?>
    <div class="alert alert-info info-bar">
      <i class="fa fa-info-circle"></i>
      <?php echo $dataset->banner_text ?>
      <?php if ($dataset->description) { ?>
        <br />
        <a href="omat/<?php echo $project ?>/<?php echo $public_login ? "projectinfo" : "dataset"; ?>#description">Read more</a>
      <?php } ?>
    </div>
  <?php } ?>

  <div class="panel panel-info printhide">
    <div class="panel-heading">
      <h3 class="panel-title">Data Tables</h3>
    </div>
    <div class="panel-body">
      <ul class="nav nav-pills">
        <?php foreach ($tables as $row) { ?>
          <li class="<?php echo $row['id'] == $id ? 'active' : 'regular'; ?>">
            <a href="<?php echo $omat_link ?>/<?php echo $project ?>/reports-table/<?php echo $row['id'] ?>">
              <?php echo $row['section'] ?>. <?php echo $row['name'] ?>
            </a>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <?php if ($values_only && $hiderow) { ?>
  <script type="text/javascript">
  $(function(){
    
  <?php foreach ($hiderow as $value) { ?>
    $("#row<?php echo $value ?>").remove();
  <?php } ?>
  });
  </script>
  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
