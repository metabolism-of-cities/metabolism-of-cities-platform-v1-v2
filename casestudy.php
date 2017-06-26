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
$area = (int)$_GET['area'];

if ($id) {
  $info = $db->record("SELECT papers.*, case_studies.* 
  FROM case_studies 
    JOIN papers
    ON case_studies.paper = papers.id
    WHERE case_studies.id = $id");

  $this_page = $info->name;

  $indicators = $db->query("SELECT data.*, s.name AS subarea, a.name AS area, i.name AS indicator,
  i.id AS indicator_id,
  papers.title, c.name AS city, papers.title, papers.doi
  FROM data 
    JOIN data_indicators i ON data.indicator = i.id
    JOIN data_subareas s ON i.subarea = s.id
    JOIN data_areas a ON s.area = a.id
    JOIN case_studies c ON data.case_study = c.id
    JOIN papers ON c.paper = papers.id
  WHERE data.case_study = $id 
  ORDER BY i.name, data.year");
} elseif ($indicator) {
  $info = $db->record("SELECT i.*, s.name AS subarea, a.name AS area, i.name AS indicator,
  a.id AS area_id, s.id AS subarea_id
  FROM data_indicators i
    JOIN data_subareas s ON i.subarea = s.id
    JOIN data_areas a ON s.area = a.id
    WHERE i.id = $indicator");

  $this_page = $info->name;

  $indicators = $db->query("SELECT data.*, s.name AS subarea, a.name AS area, i.name AS indicator,
  c.name AS city, papers.title, c.paper,
  a.id AS area_id, s.id AS subarea_id, papers.doi
  FROM data 
    JOIN data_indicators i ON data.indicator = i.id
    JOIN data_subareas s ON i.subarea = s.id
    JOIN data_areas a ON s.area = a.id
    JOIN case_studies c ON data.case_study = c.id
    JOIN papers ON c.paper = papers.id
  WHERE i.id = $indicator 
  ORDER BY i.name, c.name, data.year");

  $add_page_to_breadcrumbs = '<li><a href="page/casestudies/indicators">Indicators</a></li>';
  $add_page_to_breadcrumbs .= '<li><a href="data/areas/'.$info->area_id.'">'.$info->area.'</a></li>';
  $add_page_to_breadcrumbs .= '<li><a href="data/subareas/'.$info->subarea_id.'">'.$info->subarea.'</a></li>';
} elseif ($subarea) {
  $info = $db->record("SELECT s.*, s.name AS subarea, a.name AS area,
  a.id AS area_id, s.id AS subarea_id
  FROM data_subareas s
    JOIN data_areas a ON s.area = a.id
    WHERE s.id = $subarea");

  $this_page = $info->name;

  $indicators = $db->query("SELECT data.*, s.name AS subarea, a.name AS area, i.name AS indicator,
  c.name AS city, papers.title, c.paper, i.id AS indicator_id,
  a.id AS area_id, s.id AS subarea_id, papers.doi
  FROM data 
    JOIN data_indicators i ON data.indicator = i.id
    JOIN data_subareas s ON i.subarea = s.id
    JOIN data_areas a ON s.area = a.id
    JOIN case_studies c ON data.case_study = c.id
    JOIN papers ON c.paper = papers.id
  WHERE s.id = $subarea 
  ORDER BY i.name, c.name, data.year");
  $add_page_to_breadcrumbs = '<li><a href="page/casestudies/indicators">Indicators</a></li>';
  $add_page_to_breadcrumbs .= '<li><a href="data/areas/'.$info->area_id.'">'.$info->area.'</a></li>';
} elseif ($area) {
  $info = $db->record("SELECT *,a.name AS area
  FROM data_areas a
    WHERE a.id = $area");

  $this_page = $info->name;

  $indicators = $db->query("SELECT data.*, s.name AS subarea, a.name AS area, i.name AS indicator,
  c.name AS city, papers.title, c.paper, i.id AS indicator_id, papers.doi
  FROM data 
    JOIN data_indicators i ON data.indicator = i.id
    JOIN data_subareas s ON i.subarea = s.id
    JOIN data_areas a ON s.area = a.id
    JOIN case_studies c ON data.case_study = c.id
    JOIN papers ON c.paper = papers.id
  WHERE a.id = $area 
  ORDER BY i.name, c.name, data.year");

  $add_page_to_breadcrumbs = '<li><a href="page/casestudies/indicators">Indicators</a></li>';
}

if ($_POST) {
  require_once 'data.export.php';
  $warning = "We are currently working on the DOWNLOAD functionality. Please check back within 1-2 days (Jun 19-20, 2017)";
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

  <?php if ($warning) { echo "<div class=\"alert alert-warning\">$warning</div>"; } ?>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <form method="post">

  <input type="hidden" name="download" value="1" />
  
  <dl class="dl-horizontal">

    <?php if ($id) { ?>
    <dt>Publication</dt>
    <dd><a href="publication/<?php echo $info->paper ?>"><?php echo $info->title ?></a></dd>
    
    <dt>Author(s)</dt>
    <dd><?php echo $info->author ?></dd>

    <dt>City/Region</dt>
    <dd><?php echo $info->name ?></dd>

    <?php } elseif ($indicator || $subarea || $area) { ?>

      <dt>Area</dt>
      <?php if ($area) { ?>
        <dd><?php echo $info->area ?></dd>
      <?php } else { ?>
        <dd><a href="data/areas/<?php echo $info->area_id ?>"><?php echo $info->area ?></a></dd>
      <?php } ?>

      <?php if (!$area) { ?>

        <dt>Sub-area</dt>
        <?php if ($subarea) { ?>
          <dd><?php echo $info->subarea ?></dd>
        <?php } else { ?>
          <dd><a href="data/subareas/<?php echo $info->subarea_id ?>"><?php echo $info->subarea ?></a></dd>
        <?php } ?>

        <?php if ($indicator) { ?>
        
        <dt>Indicator</dt>
        <dd><?php echo $info->name ?></dd>

        <?php } ?>

      <?php } ?>

    <?php } ?>

    <dt>Data Points</dt>
    <dd><?php echo number_format(count($indicators),0) ?></dd>

    <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download data</button>

  </dl>

  <?php if ($indicators) { ?>

    <h2>Data</h2>

     <table class="table table-striped">
       <tr>
         <?php if ($indicator || $subarea || $area) { ?>
          <th>City</th>
          <th>MTU</th>
          <th>Publication</th>
          <?php if ($subarea || $area) { ?>
            <th>Indicator</th>
          <?php } ?>
         <?php } elseif ($id) { ?>
         <th>MTU</th>
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
         <?php if ($indicator || $subarea || $area) { ?>
           <td><a href="casestudy/<?php echo $row['case_study'] ?>"><?php echo $row['city'] ?></a></td>
           <td><?php echo $row['mtu'] ?></td>
           <td><a href="publication/<?php echo $row['paper'] ?>"><?php echo $row['title'] ?></a></td>
           <?php if ($subarea || $area) { ?>
           <td><a href="data/indicators/<?php echo $row['indicator_id'] ?>"><?php echo $row['indicator'] ?></a></td>
           <?php } ?>
         <?php } elseif ($id) { ?>
           <td><?php echo $row['mtu'] ?></td>
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

    <p>
    <button type="submit" class="btn btn-primary "><i class="fa fa-download"></i> Download data</button>
    </p>
    </form>

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
