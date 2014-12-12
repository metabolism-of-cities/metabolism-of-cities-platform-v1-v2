<?php
if ($_GET['public_login']) {
  $public_login = true;
}
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 3;
$sub_page = 3;

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

$dataresults = $db->query("SELECT AVG(data*multiplier) AS total, mfa_data.year, mfa_data.material,
  mfa_materials.code
  FROM mfa_data
  JOIN mfa_materials ON mfa_data.material = mfa_materials.id
WHERE mfa_materials.mfa_group = $id AND mfa_data.include_in_totals = 1
GROUP BY mfa_materials.code, mfa_data.year");

if (count($dataresults)) {
  foreach ($dataresults as $row) {
    $data[$row['year']][$row['material']] = $row['total'];
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
    .cut{
      max-width: 200px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      display:inline-block;
    }
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

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
    <a href="#" class="btn btn-default" data-level="1"><strong>1</strong> First level categories only</a>
    <a href="#" class="btn btn-default" data-level="2"><strong>2</strong> Up to second level</a>
    <a href="#" class="btn btn-default" data-level="3"><strong>3</strong> Up to third level</a>
    <a href="#" class="btn btn-primary" data-level="all">Show all</a>
  </p>

  <table class="table table-striped data">
    <tr>
      <th></th>
      <?php foreach ($years as $year) { ?>
        <th><?php echo $year ?></th>
      <?php } ?>
    </tr>
    <?php foreach ($list as $row) { ?>
    <tr class="level<?php echo substr_count($row['code'], ".")+1 ?>">
      <td style="padding-left:<?php echo strlen($row['code'])*10; ?>px">
        <span class="cut"><?php echo $row['code'] ?>. <?php echo $row['name'] ?></span>
      </td>
      <?php foreach ($years as $year) { ?>
      <?php 
        $datapoint = $data[$year][$row['id']];
        $final[$year] += $datapoint;
      ?>
        <td>
        <?php if (!$row['subcategories'] || $datapoint) { ?>
          <a href="<?php echo $omat_link ?>/<?php echo $project ?>/reports-data/<?php echo $year ?>/<?php echo $row['id'] ?>"><?php echo number_format($datapoint,$dataset->decimal_precision) ?></a>
        <?php } else { ?>
          <?php echo number_format($total[$row['code']][$year], $decimal_precision) ?>
        <?php } ?>
        </td>
      <?php } ?>
      </tr>
    <?php } ?>

    <tr>
      <th><?php echo $info->name ?></th>
      <?php foreach ($years as $year) { ?>
        <th><?php echo number_format($final[$year],$decimal_precision) ?></th>
      <?php } ?>
    </tr>
  </table>

  <?php if ($dataset->banner_text) { ?>
    <div class="alert alert-info info-bar">
      <i class="fa fa-info-circle"></i>
      <?php echo $dataset->banner_text ?>
    </div>
  <?php } ?>

  <div class="panel panel-info">
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

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
