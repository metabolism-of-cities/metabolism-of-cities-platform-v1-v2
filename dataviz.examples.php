<?php
require_once 'functions.php';
$section = 7;
$page = 2;
$today = date("Y-m-d");
$list = $db->query("SELECT * FROM datavisualizations WHERE 
date <= '$today'
ORDER BY date DESC");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Data Visualization Examples | <?php echo SITENAME ?></title>
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
    </style>

  </head>

  <body>

<?php require_once 'include.header.php'; ?>

    <h1>Data Visualization Examples</h1>

    <div class="alert alert-info">
    On this page we are listing interesting data visualization examples. We aim 
    to publish a new example every day, and we welcome your contributions! 
    Mail us at info@metabolismofcities.org with your contributions!
    </div>

  <ul class="datavizlist">
  <?php foreach ($list as $row) { ?>
    <li>

    <div class="panel panel-default">
      <div class="panel-heading"><?php echo $row['title'] ?></div>
      <div class="panel-body">
        <a href="datavisualizations/<?php echo $row['id'] ?>-<?php echo $row['title'] ?>">
          <img src="media/dataviz/<?php echo $row['id'] ?>.thumb.jpg" alt="" />
        </a>
        <br />
        <?php echo format_date("M d, Y", $row['date']) ?>
      </div>
    </div>

     </li>
  <?php } ?>
  </ul>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
