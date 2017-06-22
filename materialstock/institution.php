<?php
require_once '../functions.php';
require_once 'functions.stock.php';

$id = (int)$_GET['id'];
$affiliation = $affiliations[$_GET['id']];
$hits = $db->query("SELECT * FROM questionnaire WHERE affiliation = '".mysql_clean($affiliation)."'");

foreach ($hits as $row) {
    $department = $row['department'];
    $get_areas .= $row['areas'];
    $get_scales .= $row['scales'];
    $get_work .= $row['work'];
    $people[$row['id']] = $row['firstname']. " " . $row['lastname'];
    $city = $row['city'];
    $country = $row['country'];
}

$title = $affiliation;
$institutions = true;
$show_legend = true;

function getCount($string) {
  $explode = explode(",", $string);
  return count($explode);
}
function makelist($array, $values) {
  $explode = explode(",", $values);
  $explode = array_flip($explode);
  foreach ($explode as $value => $key) {
    if ($array[$value]) {
      $string .= '<tr><td>'.$array[$value] . "</td></tr>";
    }
  }
  return $string;
}

?>
<!doctype html>
<html lang="en">
<head>
    <?php require_once 'include.head.php'; ?>
	<title><?php echo $title ?></title>
    <style type="text/css">
    .largerfont{text-align:center}
    .author img{max-width:90%}
    .author p.center{text-align:center}
    .card-user .author {margin-top:10px}
    .map img{max-height:300px;}
    .map{text-align:center}
    .card h5{padding-top:40px;font-size:120%;text-align:center}
    </style>

</head>
<body>

<?php require_once 'include.header.php'; ?>

        <div class="content">
            <div class="container-fluid">

<div class="row">
<div class="card map">
<p>
  <img src="assets/maps/<?php echo $id ?>.png" alt="" />
</p>
</div>
</div>

<div class="row">
                    <div class="col-lg-4 col-md-5">
                        <div class="card card-user">
                            <div class="content">
                                <div class="author">
                                  <p class="center">
                                  <?php $ext = "jpg"; if (file_exists("assets/img/logos/$id.png")) { $ext = "png"; } ?>
                                  <img src="assets/img/logos/<?php echo $id ?>.<?php echo $ext ?>" alt="...">
                                  </p>
                                  <?php if ($department) { ?>
                                  <h4 class="title"><?php echo $department ?></h4>
                                  <?php } ?>
                                </div>
                                <p class="description text-center">
                                    <?php echo $city ?><br />
                                    <?php echo $country ?>
                                </p>

                    <h5>Representatives</h5>

                    <div class="largerfont">
                    <?php foreach ($people as $key => $name) { ?>
                        <p><a href="person.php?id=<?php echo $key ?>"><?php echo $name ?></a></p>
                    <?php } ?>
                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-7">
                        <div class="caard">
                            <div class="content">

                              <div class="row">
                                <div class="col-md-4">
                                  <h4 class="title">Areas</h4>
                                  <table class="table table-striped">
<?php echo makelist($work_areas, $get_areas) ?>
            <?php if ($info->areas_other) { echo '<tr><td>'.$info->areas_other . '</td></tr>'; } ?>

                                  </table>
                                </div>
                                <div class="col-md-4">
                                  <h4 class="title">Scales</h4>
                                  <table class="table table-striped">
<?php echo makelist($scales, $get_scales) ?>

                                  </table>
                                </div>
                                <div class="col-md-4">
                                  <h4 class="title">Work</h4>
                                  <table class="table table-striped">
<?php echo makelist($work, $get_work) ?>
            <?php if ($info->work_other) { echo '<tr><td>'.$info->work_other . '</td></tr>'; } ?>

                                  </table>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>



                </div>

            </div>
        </div>

<?php require_once 'include.footer.php'; ?>
