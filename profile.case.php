<?php
require_once 'functions.php';
require_once 'functions.profile.php';

$sub_page = 4;
$info = $profile_info;
$case = (int)$_GET['paper'];

$info = $db->record("SELECT 
  papers.*, case_studies.name
FROM people_papers
  JOIN papers ON people_papers.paper = papers.id
  JOIN case_studies ON case_studies.paper = papers.id
WHERE people_papers.people = $people_id AND case_studies.id = $case");

if (!$info->id) {
  kill("No publications found", "critical");
}

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("DELETE FROM analysis WHERE id = $delete AND case_study = $case LIMIT 1");
  $print = "Data point was deleted";
  $message = 
"New data point was deleted. 

{$info->title}
{$info->name}
{$profile_info->firstname} {$profile_info->lastname}
{$profile_info->access_email}

DATA POINT $delete

TECH DETAILS:
" . getinfo();

  mailadmins($message, "Data point deleted");
}

if ($_POST) {
  $post = array(
    'result' => $_POST['result'] ? (float)$_POST['result'] : NULL,
    'analysis_option' => (int)$_POST['option'],
    'case_study' => $case,
    'year' => $_POST['year'] ? (int)$_POST['year'] : NULL,
    'notes' => $_POST['notes'] ? html($_POST['notes']) : NULL,
  );
  $db->insert("analysis",$post);
  $print = "Data point has been added successfully";
  $message = 
"New data point was added. 

{$info->title}
{$info->name}
{$profile_info->firstname} {$profile_info->lastname}
{$profile_info->access_email}

DATA POINT:
Year: " . (int)$_POST['year'] . "
Value: " . (float)$_POST['result'] . "

TECH DETAILS:
" . getinfo();

  mailadmins($message, "New data point added");
}

$indicators = $db->query("SELECT *, analysis.id
FROM analysis 
  JOIN analysis_options o ON analysis.analysis_option = o.id
WHERE analysis.case_study = $case 
ORDER BY o.name, analysis.year");

foreach ($indicators as $row) {
  if ($row['year']) {
    $show_table[$row['name']] = true;
    $table[$row['name']][$row['year']] = $row['result'];
    $table[$row['name']][$row['year']] = $row['result'];
  } 
  if ($row['notes']) {
    $infolist[$row['name']][] = $row['notes'];
  }
  $previous = $row['name'];
}

$list = $db->query("SELECT 
  o.*, t.name AS type
FROM 
  analysis_options o 
  JOIN analysis_options_types t ON o.type = t.id
ORDER BY o.type, o.name");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Data Entry | <?php echo SITENAME ?></title>
    <style type="text/css">
    .fa-info-circle{float:left;margin-right:10px;font-size:40px}
    .jumbotron{margin-bottom:50px}
    </style>
    <script type="text/javascript">
    $(function(){
      <?php foreach ($list as $row) { ?>
        measure[<?php echo $row['id'] ?>] = "<?php echo $row['measure'] ?>";
      <?php } ?>
      $("select[name='option']").change(function(){
        var info = $(this).val();
        getmeasure = measure[info];
        $("#measure").html(getmeasure);
      });
    });
    </script>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Data Entry</h1>

    <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

    <p><strong>Publication: </strong><?php echo $info->title ?></p>
    <p><strong>City: </strong> <?php echo $info->name ?></p>

    <form method="post" class="form form-horizontal">
    
      <div class="form-group">
        <label class="col-sm-2 control-label">Type</label>
        <div class="col-sm-10">
          <select name="option" class="form-control" required>
            <option value=""></option>
            <?php foreach ($list as $row) { ?>
              <?php if ($row['type'] != $type) { ?>
                <?php if ($type) { ?></optgroup><?php } ?>
                <optgroup label="<?php echo $row['type'] ?>">
              <?php } $type = $row['type']; ?>
              <option value="<?php echo $row['id'] ?>"<?php if ($row['id'] == $info->name) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
            <?php } ?>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label">Year</label>
        <div class="col-sm-10">
          <input class="form-control" type="number" name="year" />
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label">Value</label>
        <div class="col-sm-10">
          <div class="input-group">
            <input class="form-control" type="number" step="0.01" name="result" />
            <span class="input-group-addon" id="measure"></span>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label">Comments</label>
        <div class="col-sm-10">
          <input class="form-control" type="text" name="notes" />
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </div>

    </form>

    <?php if (!count($indicators)) { ?>

      <div class="alert alert-info">
        No data points found.
      </div>

    <?php } else { ?>

      <table class="table table-striped">
       <tr>
         <th>Data category</th>
         <th>Year</th>
         <th>Value</th>
         <th>Comments</th>
         <th>Delete</th>
       </tr>
       <?php foreach ($indicators as $row) { ?>
       <?php
         if ((int)$row['result'] == $row['result']) {
           $decimals = 0;
         } else {
           $decimals = 2;
         }
       ?>
       <tr>
         <?php if ($row['name'] == $name) { ?>
           <td></td>
         <?php } else { ?>
         <td><?php echo $row['name'] ?></td>
         <?php } ?>
         <?php $name = $row['name']; ?>

         <td><?php echo $row['year'] ?></td>
         <td><?php echo number_format($row['result'],$decimals) ?> <?php echo $row['measure'] ?></td>
         <td><?php echo $row['notes'] ?></td>
         <td>
          <a href="profile.case.php?id=<?php echo $profile_id ?>&amp;paper=<?php echo $case ?>&amp;delete=<?php echo $row['id'] ?>"
          class="btn btn-danger"
          onclick="javascript:return confirm('Are you sure?')"
          >
            Delete
          </a>
         </td>
       </tr>
     <?php } ?>
     </table> 

   <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
