<?php
$show_breadcrumbs = true;
$skip_login = true;
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 5;
$page = 2;

$id = (int)$_GET['id'];
$indicator = (int)$_GET['indicator'];
$subarea = (int)$_GET['subarea'];

if ($id) {
  $info = $db->record("SELECT papers.*, case_studies.* 
  FROM case_studies 
    JOIN papers
    ON case_studies.paper = papers.id
    WHERE case_studies.id = $id");

  $this_page = $info->name;

  $indicators = $db->query("SELECT data.*, s.name AS subarea, a.name AS area, i.name AS indicator,
  i.id AS indicator_id
  FROM data 
    JOIN data_indicators i ON data.indicator = i.id
    JOIN data_subareas s ON i.subarea = s.id
    JOIN data_areas a ON s.area = a.id
  WHERE data.case_study = $id 
  ORDER BY i.name, data.year");
} elseif ($indicator) {
  $info = $db->record("SELECT i.*, s.name AS subarea, a.name AS area, i.name AS indicator
  FROM data_indicators i
    JOIN data_subareas s ON i.subarea = s.id
    JOIN data_areas a ON s.area = a.id
    WHERE i.id = $indicator");

  $this_page = $info->name;

  $indicators = $db->query("SELECT data.*, s.name AS subarea, a.name AS area, i.name AS indicator,
  c.name AS city, papers.title, c.paper
  FROM data 
    JOIN data_indicators i ON data.indicator = i.id
    JOIN data_subareas s ON i.subarea = s.id
    JOIN data_areas a ON s.area = a.id
    JOIN case_studies c ON data.case_study = c.id
    JOIN papers ON c.paper = papers.id
  WHERE i.id = $indicator 
  ORDER BY i.name, c.name, data.year");
} elseif ($subarea) {
  $info = $db->record("SELECT s.*, s.name AS subarea, a.name AS area
  FROM data_subareas s
    JOIN data_areas a ON s.area = a.id
    WHERE s.id = $subarea");

  $this_page = $info->name;

  $indicators = $db->query("SELECT data.*, s.name AS subarea, a.name AS area, i.name AS indicator,
  c.name AS city, papers.title, c.paper, i.id AS indicator_id
  FROM data 
    JOIN data_indicators i ON data.indicator = i.id
    JOIN data_subareas s ON i.subarea = s.id
    JOIN data_areas a ON s.area = a.id
    JOIN case_studies c ON data.case_study = c.id
    JOIN papers ON c.paper = papers.id
  WHERE s.id = $subarea 
  ORDER BY i.name, c.name, data.year");
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->name ?> | Material Flow Analysis Case Studies | <?php echo SITENAME ?></title>
    <style type="text/css">
    .explanation{border-bottom:2px dotted #999}
    .table.ellipsis{border-top:0}
    .table > tbody > tr > th {border-top:0}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Data Overview</h1>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <dl class="dl-horizontal">

    <?php if ($id) { ?>
    <dt>Publication</dt>
    <dd><a href="publication/<?php echo $info->paper ?>"><?php echo $info->title ?></a></dd>
    
    <dt>Author(s)</dt>
    <dd><?php echo $info->author ?></dd>

    <dt>City/Region</dt>
    <dd><?php echo $info->name ?></dd>

    <?php } elseif ($indicator || $subarea) { ?>

      <dt>Area</dt>
      <dd><?php echo $info->area ?></dd>

      <dt>Sub-area</dt>
      <dd><?php echo $info->subarea ?></dd>

      <?php if ($indicator) { ?>
      
      <dt>Indicator</dt>
      <dd><?php echo $info->name ?></dd>

      <?php } ?>

    <?php } ?>

    <dt>Data Points</dt>
    <dd><?php echo count($indicators) ?></dd>

  </dl>

  <?php if ($indicators) { ?>

    <h2>Data</h2>

     <table class="table table-striped">
       <tr>
         <?php if ($indicator || $subarea) { ?>
          <th>City</th>
          <th>Publication</th>
          <?php if ($subarea) { ?>
            <th>Indicator</th>
          <?php } ?>
         <?php } elseif ($id) { ?>
         <th>Area</th>
         <th>Sub-area</th>
         <th>Indicator</th>
         <?php } ?>
         <th>Year</th>
         <th>Value</th>
         <th>Unit</th>
         <th>Comments</th>
       </tr>
       <?php foreach ($indicators as $row) { ?>
       <?php
         if ((int)$row['value'] == $row['value']) {
           $decimals = 0;
         } else {
           $decimals = 2;
         }
       ?>
       <tr>
         <?php if ($indicator || $subarea) { ?>
           <td><a href="casestudy/<?php echo $row['case_study'] ?>"><?php echo $row['city'] ?></a></td>
           <td><a href="publication/<?php echo $row['paper'] ?>"><?php echo $row['title'] ?></a></td>
           <?php if ($subarea) { ?>
           <td><a href="data/indicators/<?php echo $row['indicator_id'] ?>"><?php echo $row['indicator'] ?></a></td>
           <?php } ?>
         <?php } elseif ($id) { ?>
           <td><?php echo $row['area'] ?></td>
           <td><?php echo $row['subarea'] ?></td>
           <?php if ($row['indicator'] == $previous_indicator && false) { ?>
             <td></td>
           <?php } else { ?>
           <td><a href="data/indicators/<?php echo $row['indicator_id'] ?>"><?php echo $row['indicator'] ?></a></td>
           <?php } ?>
           <?php $previous_indicator = $row['indicator']; ?>
         <?php } ?>

         <td><?php echo $row['year'] ?>
         <?php if ($row['year_end']) { ?>-<?php echo $row['year_end']; } ?>
         </td>
         <td><?php echo number_format($row['value'],$decimals) ?> </td>
         <td><?php echo $row['unit'] ?></td>
         <td>
          <?php if ($row['notes']) { ?>
            <span class="explanation" data-toggle="tooltip" data-placement="bottom"  title="<?php echo $row['notes'] ?>">
              <i class="fa fa-question-circle"></i>
              Comments
            </span>
         <?php } ?>
         </td>
       </tr>
     <?php } ?>
     </table>

  <?php } ?>

    <div class="alert alert-warning">
      You can help! 
      We have embarked upon the time-consuming but very rewarding task of
      extracting urban metabolism figures from publications, in order to enable
      fellow researchers to more easily and quickly access these figures and 
      save time in their work. 
      Although we already have a lot of data, there is still lots more out there.
      Are you
      willing to help? <a href="page/contact">Get in touch!</a>
    </div>

<?php require_once 'include.footer.php'; ?>

<script type="text/javascript">
$(function(){
    $('[data-toggle="tooltip"]').tooltip({
      container: 'body'
    });
});
</script>

  </body>
</html>
