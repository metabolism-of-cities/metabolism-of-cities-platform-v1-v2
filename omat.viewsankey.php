<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 1;
$sub_page = 7;

$project = (int)$project;
$id = (int)$_GET['id'];

$info = $db->record("SELECT * FROM mfa_sankey WHERE id = $id AND dataset = $project");
if (!$info->id) {
  kill("Not found");
}

$list = $db->query("SELECT * FROM mfa_sankey_nodes WHERE sankey = $id ORDER BY from_name, to_name");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Sankey Diagram: <?php echo $info->name ?> | <?php echo SITENAME ?></title>
    <script type="text/javascript"
     src="//www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['sankey']}]}">
    </script>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1><?php echo $info->name ?> Sankey Diagram</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/sankeys">Sankey Diagrams</a></li>
    <li class="active"><?php echo $info->name ?></li>
  </ol>

  <?php if (!count($list)) { ?>

  <div class="alert alert-danger">
    Please add <a href="omat/<?php echo $project ?>/sankeynodes/<?php echo $id ?>">nodes</a> first.
  </div>

  <?php } else { ?>

<div id="sankey" style="width: 900px; height: 400px;"></div>

<script type="text/javascript">
google.setOnLoadCallback(drawChart);
   function drawChart() {
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'From');
    data.addColumn('string', 'To');
    data.addColumn('number', 'Weight');
    data.addRows([
    <?php $i = 0; foreach ($list as $row) { $i++; ?>
       [ '<?php echo $row['from_name'] ?>', '<?php echo $row['to_name'] ?>', <?php echo $row['weight'] ?>]<?php if ($i < count($list)) { echo ','; } ?>
    <?php } ?>
    ]);

    // Set chart options
    var options = {
      width: 600,
      sankey: {node: {nodePadding: 40 } }
    };

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.Sankey(document.getElementById('sankey'));
    chart.draw(data, options);
   }
</script>

    
  <?php } ?>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
