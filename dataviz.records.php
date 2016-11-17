<?php
require_once 'functions.php';
$section = 7;
$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM datavisualizations WHERE id = $id");

if (!$info->id) {
  kill("Dataviz not found");
}

if ($info->paper) {
  $paperinfo = $db->record("SELECT * FROM papers WHERE id = {$info->paper}");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->title ?> | <?php echo SITENAME ?></title>
    <link rel="canonical" href="<?php echo URL . "datavisualization/$id-".flatten($info->title) ?>" />
    <style type="text/css">
    .viz {text-align:center;}
    .viz img {border:1px solid #ddd; padding:5px}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1><?php echo $info->title ?></h1>

  <?php if (file_exists("media/dataviz/$id.jpg")) { ?>
    <div class="well viz">
      <img src="media/dataviz/<?php echo $id ?>.jpg" alt="" />
    </div>
  <?php } ?>

  <p><?php echo $info->description ?></p>

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

    <dt>Contributor</dt>
    <dd><?php echo $info->contributor ?></dd>
    <dt>Date</dt>
    <dd><?php echo format_date("M d, Y", $info->date) ?></dd>
  </dl>

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

  <p><a href="datavisualization/examples" class="btn btn-primary">&laquo; View the full list</a></p>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
