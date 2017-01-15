<?php
$skip_login = true;
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 7;
$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM datavisualizations WHERE id = $id");

if (!$info->id) {
  kill("Dataviz not found");
}

if ($_GET['go'] == "previous") {
  $date = $info->date;
  $go = $db->record("SELECT * FROM datavisualizations WHERE date < '$date' ORDER BY date DESC LIMIT 1");
  header("Location: " . URL . "datavisualizations/" . $go->id . "-" . flatten($go->title));
  exit();
}

if ($_GET['go'] == "next") {
  $date = $info->date;
  $go = $db->record("SELECT * FROM datavisualizations WHERE date > '$date' ORDER BY date ASC LIMIT 1");
  header("Location: " . URL . "datavisualizations/" . $go->id . "-" . flatten($go->title));
  exit();
}

if ($info->paper) {
  $paperinfo = $db->record("SELECT * FROM papers WHERE id = {$info->paper}");
}

if (date("Y-m-d") < 20170121 && $info->date < "2017-01-01") {
  $voting = true;
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->title ?> | <?php echo SITENAME ?></title>
    <link rel="canonical" href="<?php echo URL . "datavisualization/$id-".flatten($info->title) ?>" />
    <style type="text/css">
    .vote i{font-size:80px}
    .vote{background:#61a9bd;border-radius:4px;color:#fff;padding-bottom:10px;margin-bottom:10px;text-align:justify}
    .vote .col-md-5{text-align:center}
    .vote a{margin-top:30px}
    .viz {text-align:center;}
    .viz img {border:1px solid #ddd; padding:5px}
    @media (max-width:992px){
      .vote a{display:block}
    }
    h1{text-align:center}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <ol class="breadcrumb">
    <li><a href="./">Home</a></li>
    <li><a href="stakeholders">Stakeholders Initiative</a></li>
    <li><a href="datavisualization">Data Visualizations</a></li>
    <li><a href="datavisualization/examples">Examples</a></li>
    <li class="active"><?php echo $info->title ?></li>
  </ol>

  <h1>
    <?php if ($id != 1) { ?>
    <a href="datavisualizations/<?php echo $id ?>-<?php echo flatten($info->title) ?>/previous"><i class="fa fa-arrow-circle-left pull-left"></i></a>
    <?php } ?>
      <?php echo $info->title ?>
    <?php if ($id != 106) { ?>
      <a href="datavisualizations/<?php echo $id ?>-<?php echo flatten($info->title) ?>/next"><i class="fa fa-arrow-circle-right pull-right"></i></a>
    <?php } ?>
  </h1>

  <?php if (file_exists("media/dataviz/$id.jpg")) { ?>
    <div class="well viz">
      <img src="media/dataviz/<?php echo $id ?>.jpg" alt="" />
    </div>
  <?php } ?>

  <p><?php echo $info->description ?></p>

  <div class="row">
    <div class="col-md-8">
      <h2>Further details</h2>
      <dl class="dl dl-horizontal">
      <?php if ($info->paper) { ?>
        <dt>Source</dt>
        <dd><?php echo $paperinfo->title ?></dd>
        <dt>Year</dt>
        <dd><?php echo $paperinfo->year ?></dd>
        <dt>Author(s)</dt>
        <dd><?php echo $paperinfo->author ?></dd>
      <?php } ?>
        <?php if ($info->source_details) { ?>
        <dt>Source</dt>
        <dd><?php echo $info->source_details ?></dd>
        <?php } ?>
        <?php if ($info->year) { ?>
          <dt>Year</dt>
          <dd><?php echo $info->year ?></dd>
        <?php } ?>

        <dt>More information</dt>
        <?php if ($info->url) { ?>
          <dd><a href="<?php echo $info->url ?>"><?php echo $info->url ?></a></dd>
        <?php } else { ?>
          <dd><a href="publication/<?php echo $info->paper ?>"><?php echo URL ?>publication/<?php echo $info->paper ?></a></dd>
        <?php } ?>

        <dt>Uploaded by</dt>
        <dd><?php echo $info->contributor ?></dd>
        <dt>Date</dt>
        <dd><?php echo format_date("M d, Y", $info->date) ?></dd>
      </dl>

      </div>

    <?php if ($voting) { ?>
    <div class="col-md-4 vote">
      <h2>Vote now</h2>

      <div class="row">
      
        <div class="col-md-7">
          Do you like this data visualization? 
          Cast your vote now! We are selecting the 
          best data visualization by popular vote and 
          you can cast <strong>3 votes</strong> for your
          favorite visualizations. <br /><br />Voting ends on
          <strong>January 20, 2017</strong>
        </div>

        <div class="col-md-5">
          <i class="fa fa-check-circle"></i>
          <a href="vote.php?id=<?php echo $id ?>" class="btn btn-default">Vote Now</a>
        </div>

      </div>
    </div>
    <?php } ?>
    
    </div>

  <div class="well">
  
    <h3>Copyright notice</h3>

    <p>
    Please note that these data visualizations come from external sources (journal publications, 
    academic books, reports, etc.). Copyright of these data visualizations is held by the publishers or 
    authors. We have included these data visualizations on our website under the principles of 
    <a href="https://en.wikipedia.org/wiki/Fair_use">fair use</a>. However, if you would like to 
    reuse any of these images be sure to either comply with the rules around fair use in your country or 
    consult the original authors or publishers if in doubt.
    </p>

  </div>

  <p>
    <a href="datavisualization/examples" class="btn btn-primary">&laquo Back to the overview</a>
    <a href="datavisualization/examples/all" class="btn btn-primary pull-right">View ALL data visualizations on a single page</a>
  </p>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
