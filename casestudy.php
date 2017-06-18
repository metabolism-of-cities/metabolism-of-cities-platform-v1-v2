<?php
$show_breadcrumbs = true;
$skip_login = true;
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 5;
$page = 2;

$id = (int)$_GET['id'];

$info = $db->record("SELECT papers.*, case_studies.* 
FROM case_studies 
  JOIN papers
  ON case_studies.paper = papers.id
  WHERE case_studies.id = $id");

$this_page = $info->name;

$indicators = $db->query("SELECT data.*, s.name AS subarea, a.name AS area, i.name AS indicator
FROM data 
  JOIN data_indicators i ON data.indicator = i.id
  JOIN data_subareas s ON i.subarea = s.id
  JOIN data_areas a ON s.area = a.id
WHERE data.case_study = $id 
ORDER BY i.name, data.year");

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

  <h1>Regional Material Flow Analysis</h1>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <dl class="dl-horizontal">
    <dt>Publication</dt>
    <dd><a href="publication/<?php echo $info->paper ?>"><?php echo $info->title ?></a></dd>
    
    <dt>Author(s)</dt>
    <dd><?php echo $info->author ?></dd>

    <dt>City/Region</dt>
    <dd><?php echo $info->name ?></dd>

    <dt>Data Points</dt>
    <dd><?php echo count($indicators) ?></dd>

  </dl>

  <?php if ($indicators) { ?>

    <h2>Data</h2>

     <table class="table table-striped">
       <tr>
         <th>Area</th>
         <th>Sub-area</th>
         <th>Indicator</th>
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
         <td><?php echo $row['area'] ?></td>
         <td><?php echo $row['subarea'] ?></td>
         <?php if ($row['indicator'] == $indicator && false) { ?>
           <td></td>
         <?php } else { ?>
         <td><?php echo $row['indicator'] ?></td>
         <?php } ?>
         <?php $indicator = $row['indicator']; ?>

         <td><?php echo $row['year'] ?></td>
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
