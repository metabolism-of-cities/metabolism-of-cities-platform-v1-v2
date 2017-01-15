<?php
require_once 'functions.php';
$section = 7;
$page = 2;
$today = date("Y-m-d");
$list = $db->query("SELECT * FROM datavisualizations WHERE 
date <= '2017-01-01'
ORDER BY date DESC");

if (date("Y-m-d") < 20170121) {
  $voting = true;
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Data Visualization Examples | All images | <?php echo SITENAME ?></title>
    <style type="text/css">
    .datavizlist {
      list-style:none;
      margin:0;
      padding:0;
    }
    .datavizlist li {
      display:inline-block;
      width:270px;
      vertical-align:top;
    }
    .datavizlist li a {
      max-height:180px;
      overflow:hidden;
      display:block;
      border:1px solid #ccc;
      padding:2px;
    }
    .vote i{font-size:80px;position:relative;top:40px}
    .vote{background:#61a9bd;border-radius:4px;color:#fff;padding-bottom:10px;margin-bottom:10px;text-align:justify}
    .vote .col-md-5{text-align:center}
    .vote a{margin-top:30px}
    .panel{text-align:center}
    </style>

  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <ol class="breadcrumb">
    <li><a href="./">Home</a></li>
    <li><a href="stakeholders">Stakeholders Initiative</a></li>
    <li><a href="datavisualization">Data Visualizations</a></li>
    <li class="active">Examples</li>
  </ol>

    <h1>Data Visualization Examples - All Images</h1>

    <?php if ($voting) { ?>
    <div class="row vote">

      <div class="a">
      
        <div class="col-md-10">
      <h2>Vote now</h2>
      <p>
          Do you like this data visualization? 
          Cast your vote now! We are selecting the 
          best data visualization by popular vote and 
          you can cast <strong>3 votes</strong> for your
          favorite visualizations. 
          
          To cast your vote, simply click the image(s) 
          of your choice in the list below.
          
          <br /><br />Voting ends on
          <strong>January 20, 2017</strong>
          </p>
        </div>

        <div class="col-md-2">
          <i class="fa fa-check-circle"></i>
        </div>

      </div>
    </div>

    <?php } ?>

  <?php foreach ($list as $row) { ?>
      <div class="panel panel-default">
      <div class="panel-heading"><?php echo $row['title'] ?></div>
      <div class="panel-body">
          <a href="vote.php?id=<?php echo $row['id'] ?>" target="_blank">
            <img src="media/dataviz/<?php echo $row['id'] ?>.jpg" alt="" />
          </a>
      </div>
      </div>

  <?php } ?>

  <p><a href="datavisualization" class="btn btn-primary">Back to the Data Visualization portal</a></p>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
