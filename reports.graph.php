<?php
if ($_GET['public_login']) {
  $public_login = true;
}
require_once 'functions.php';
require_once 'functions.omat.php';

$section = 6;
$load_menu = 3;
$sub_page = 6;

$dataset = $db->record("SELECT * FROM mfa_dataset WHERE id = $project");
if (!$dataset->year_start || !$dataset->year_end) {
  $error = "You have not set the start and end year of your dataset. Set this first";
} elseif ($dataset->year_start == $dataset->year_end) {
  $singleyear = true;
}

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM mfa_groups WHERE dataset = $project AND id = $id");

if (!$info->id) {
  die("Graph not found");
}

$materials = $db->query("SELECT * FROM mfa_materials WHERE mfa_group = $id AND LENGTH(code) = 1");

if ($singleyear) {
  $dataresults = $db->query("SELECT AVG(data*multiplier) AS total, mfa_data.material,
    mfa_materials.code, mfa_materials.name
    FROM mfa_materials
    LEFT JOIN mfa_data ON mfa_data.material = mfa_materials.id
  WHERE mfa_materials.mfa_group = $id AND (mfa_data.include_in_totals = 1 OR mfa_data.include_in_totals IS NULL)
  GROUP BY mfa_materials.code");

  if (count($dataresults)) {
    foreach ($dataresults as $row) {
      $names[$row['code']] = $row['name'];
      $data[$row['material']] = $row['total'];
      $explode = explode(".", $row['code']);
      if (is_array($explode)) {
        unset($code);
        foreach ($explode as $value) {
          $code .= $code ? ".$value" : $value;
          $total[$code] += $row['total'];
        }
      }
    }
  }
}

foreach ($names as $key => $value) {
  if (substr_count($key, ".") != 1) {
    unset($names[$key]);
  }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Graphs: <?php echo $info->section ?>. <?php echo $info->name ?> | <?php echo SITENAME ?></title>
    <link rel="stylesheet" href="css/nv.d3.min.css" />
    <style type="text/css">
      #graph{height:500px}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Graphs: <?php echo $info->section ?>. <?php echo $info->name ?></h1>

    <ol class="breadcrumb">
      <?php if ($public_login) { ?>
          <li><a href="omat/<?php echo $project ?>/projectinfo"><?php echo $check->name ?></a></li>
          <li><a href="omat-public/<?php echo $project ?>/reports-graphs">Graphs</a></li>
      <?php } else { ?>
          <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
          <li><a href="omat/<?php echo $project ?>/reports-graphs">Graphs</a></li>
      <?php } ?>
      <li class="active"><?php echo $info->section ?>. <?php echo $info->name ?></li>
    </ol>

  <?php if ($singleyear) { ?>
  <?php
  
  ?>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
      var data = google.visualization.arrayToDataTable([
        [<?php foreach ($names as $key) { ?>'<?php echo $key ?>',<?php } ?>
         { role: 'annotation' } ],
         <?php foreach ($materials as $row) { ?>
        ['<?php echo $row['name'] ?>', <?php foreach ($names as $key => $value) { 
          echo substr($key, 0, 1) == $row['code'] ? (float)$total[$key] : 0; ?>,<?php } ?>],
        <?php } ?>
      ]);

      var options = {
        width: 800,
        height: 500,
        legend: { position: 'none' },
        bar: { groupWidth: '75%' },
        isStacked: true
      };

        var chart = new google.visualization.BarChart(document.getElementById('chart_div'));

        chart.draw(data, options);
      }
    </script>
    <div id="chart_div" style="width: 900px; height: 500px;"></div>

  <?php } else { ?>
  <div>
    <svg id="graph"></svg>
  </div>


  <script src="js/d3.v3.min.js"></script>
  <script src="js/nvd3/nv.d3.min.js"></script>
  <script src="js/nvd3/utils.js"></script>
  <script src="js/nvd3/models/axis.js"></script>
  <script src="js/nvd3/tooltip.js"></script>
  <script src="js/nvd3/interactiveLayer.js"></script>
  <script src="js/nvd3/models/legend.js"></script>
  <script src="js/nvd3/models/scatter.js"></script>
  <script src="js/nvd3/models/stackedArea.js"></script>
  <script src="js/nvd3/models/stackedAreaChart.js"></script>
  <script>


var histcatexplong = [
  <?php 
    foreach ($materials as $row) { $counter++; 
    $data = $db->query("SELECT SUM(data) AS total, mfa_data.year
      FROM mfa_data
      JOIN mfa_materials ON mfa_data.material = mfa_materials.id
    WHERE mfa_materials.mfa_group = {$id} 
      AND mfa_data.year >= {$dataset->year_start} 
      AND mfa_data.year <= {$dataset->year_end}
      AND mfa_materials.code LIKE '{$row['code']}%'
    GROUP BY mfa_data.year");

  ?>
  {
    "key" : "<?php echo $row['name'] ?>" ,
    "values" : [ 
    <?php $subcounter = 0; foreach ($data as $subrow) { $subcounter++; ?>
    [ <?php echo $subrow['year'] ?> , <?php echo $subrow['total'] ?>]<?php if (count($data) > $subcounter) { echo ','; } echo "\n"; ?>
    <?php } ?>
    ]
  } <?php if (count($materials) > $counter) { echo ','; } ?>
  <?php } ?>
  ];

var colors = d3.scale.category20();
keyColor = function(d, i) {return colors(d.key)};

var chart;
nv.addGraph(function() {
  chart = nv.models.stackedAreaChart()
                .useInteractiveGuideline(true)
                .x(function(d) { return d[0] })
                .y(function(d) { return d[1] })
                .color(keyColor)
                .transitionDuration(300);

  chart.yAxis
      .tickFormat(d3.format('.4s'));

  d3.select('#graph')
    .datum(histcatexplong)
    .transition().duration(1000)
    .call(chart)
    .each('start', function() {
        setTimeout(function() {
            d3.selectAll('#graph *').each(function() {
              if(this.__transition__)
                this.__transition__.duration = 1;
            })
          }, 0)
      })

  nv.utils.windowResize(chart.update);

  return chart;
});

</script>

<?php } ?>

  <?php if ($dataset->banner_text) { ?>
    <div class="alert alert-info info-bar">
      <i class="fa fa-info-circle"></i>
      <?php echo $dataset->banner_text ?>
    </div>
  <?php } ?>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
