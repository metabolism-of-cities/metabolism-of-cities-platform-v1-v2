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

if (is_array($names)) {
  foreach ($names as $key => $value) {
    if (substr_count($key, ".") != 1) {
      unset($names[$key]);
    }
  }
}

if (is_array($total)) {
  foreach ($total as $key => $value) {
    if (is_int($key)) {
      $max = max($value, $max);
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Graphs: <?php echo $info->section ?>. <?php echo $info->name ?> | <?php echo SITENAME ?></title>
    <?php if (!$singleyear) { ?>
      <link rel="stylesheet" href="css/nv.d3.min.css" />
    <?php } ?>
    <style type="text/css">
      #graph{height:500px}
      .pie{height:400px;width:400px;float:left}
    </style>
    <script type="text/javascript">
    $(function(){
      $("#samesize").click(function(e){
        e.preventDefault();
        $(".pie").width(500);
        $(".pie").height(400);
        <?php foreach ($materials as $row) { ?>
          drawChart<?php echo $row['id'] ?>();
        <?php } ?>
      });
    });
    </script>
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

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ['Group', 'Flow size (tons)'],
         <?php foreach ($materials as $row) { ?>
        ['<?php echo $row['name'] ?>', <?php echo $total[$row['code']] ?>],
        <?php } ?>
      ]);

        var options = {
          title: 'Flow breakdown',
        };

        var chart = new google.visualization.BarChart(document.getElementById('chart_div'));

        chart.draw(data, options);
      }
    </script>

    <div id="chart_div" style="width: 900px; height: 500px;"></div>

    <h2>Breakdown of flows</h2>

    <a href="#" id="samesize" class="btn btn-default">Show all charts in the same size</a>

    <?php foreach ($materials as $row) { ?>
    <?php
      $size = $total[$row['code']]*(500/$max);
    ?>

      <script type="text/javascript">
        google.load("visualization", "1", {packages:["corechart"]});
        google.setOnLoadCallback(drawChart<?php echo $row['id'] ?>);
        function drawChart<?php echo $row['id'] ?>() {

          var data = google.visualization.arrayToDataTable([
            ['Type', 'Size'],
            <?php foreach ($names as $key => $value) { ?>
            <?php if (substr($key, 0, 1) == $row['code']) { ?>
            ['<?php echo $value ?>', <?php echo $total[$key] ?>],
            <?php } } ?>
          ]);

          var options = {
            title: '<?php echo $row['name'] ?>',
            legend: 'none',
            pieHole: 0.4
          };

          var chart = new google.visualization.PieChart(document.getElementById('piechart-<?php echo $row['code'] ?>'));
          chart.draw(data, options);
        }
      </script>
      <div class="pie" id="piechart-<?php echo $row['code'] ?>" style="width:<?php echo $size ?>px;height:<?php echo $size ?>px"></div>
    <?php } ?>

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
    foreach ($materials as $row) { 

      $data = $db->query("SELECT SUM(data) AS total, mfa_data.year
        FROM mfa_data
        JOIN mfa_materials ON mfa_data.material = mfa_materials.id
      WHERE mfa_materials.mfa_group = {$id} 
        AND mfa_data.year >= {$dataset->year_start} 
        AND mfa_data.year <= {$dataset->year_end}
        AND mfa_materials.code LIKE '{$row['code']}%'
      GROUP BY mfa_data.year");

      foreach ($data as $subrow) {

        // We can't have one of the years missing or D3 won't render
        // the graph, so we first check if data is available for 
        // all of the years for all of the materials.

        // We should really optimize this script a bit, very roundabout way 
        // of doing things at the moment. 

        $years_active[$subrow['year']][$row['code']] = true;

      }

    }

    foreach ($years_active as $key => $value) {
      // Let's count how many records there are for each year
      $count = count($value);
      $max_number_of_years = max($max_number_of_years, $count);
    }

    foreach ($years_active as $key => $value) {
      if (count($value) == $max_number_of_years) {
        // And we only allow this year to be included if it has 
        // values for all years
        $in_years .= $key.",";
      }
    }

    $in_years = substr($in_years, 0, -1);

    foreach ($materials as $row) { 
      $counter++; 

      $data = $db->query("SELECT SUM(data) AS total, mfa_data.year
        FROM mfa_data
        JOIN mfa_materials ON mfa_data.material = mfa_materials.id
      WHERE mfa_materials.mfa_group = {$id} 
        AND mfa_data.year IN ($in_years)
        AND mfa_materials.code LIKE '{$row['code']}%'
      GROUP BY mfa_data.year");

      if (count($data)) {

  ?>
  {
    "key" : "<?php echo $row['name'] ?>" ,
    "values" : [ 
    <?php $subcounter = 0; foreach ($data as $subrow) { $subcounter++; ?>
    [ <?php echo $subrow['year'] ?> , <?php echo $subrow['total'] ?>]<?php if (count($data) > $subcounter) { echo ','; } echo "\n"; ?>
    <?php } ?>
    ]
  } <?php if (count($materials) > $counter) { echo ','; } ?>
  <?php } } ?>
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
