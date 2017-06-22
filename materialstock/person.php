<?php
require_once '../functions.php';

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM questionnaire WHERE id = $id");
$list = $db->query("SELECT * FROM questionnaire WHERE id > 7 ORDER BY affiliation");

$fields = array(
  1 => "Academia",
  2 => "Consulting",
  3 => "Government",
  4 => "Industry",
  5 => "Other",
);

$work = array(
  1 => "Material stocks",
  2 => "Material flows (related to stocks)",
  3 => "Material flows (non-related to stocks)",
  4 => "Bottom-up approach",
  5 => "Top-down approach",
  6 => "Stationary and quasi-stationary models",
  7 => "Dynamic MFA models",
  99 => "Other",
);

$work_areas = array(
  1 => "Buildings and infrastructure",
  2 => "Construction/demolition materials",
  3 => "GHG emissions",
  4 => "Energy demand",
  5 => "Forecasting and scenario development",
  99 => "Other",
);

$scales = array(
  1 => "Urban",
  2 => "Rural",
  3 => "National",
  4 => "Regional",
  5 => "Global",
  99 => "Other",
);

$primary_data = array(
0 => "",
1 => "Yes",
2 => "No",
);

$title = $info->firstname . " " . $info->lastname;

function getCount($string) {
  $explode = explode(",", $string);
  return count($explode);
}
function makelist($array, $values) {
  $explode = explode(",", $values);
  foreach ($explode as $key => $value) {
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
	<title><?php echo $info->firstname ?> <?php echo $info->lastname ?></title>

</head>
<body>

<?php require_once 'include.header.php'; ?>

        <div class="content">
            <div class="container-fluid">

<div class="row">
                    <div class="col-lg-4 col-md-5">
                        <div class="card card-user">
                            <div class="image">
                                <img src="assets/img/background.jpg" alt="...">
                            </div>
                            <div class="content">
                                <div class="author">
                                  <img class="avatar border-white" src="assets/img/faces/face-2.jpg" alt="...">
                                  <h4 class="title"><?php echo $title ?></h4>
                                </div>
                                <p class="description text-center">
                                    <?php echo $info->affiliation ?><br />
                                    <?php echo $info->city ?><br />
                                    <?php echo $info->country ?>
                                </p>
                            </div>
                            <hr>
                            <div class="text-center">
                                <div class="row">
                                    <div class="col-md-3 col-md-offset-1">
                                        <h5><?php echo getCount($info->scales) ?><br><small>Scales</small></h5>
                                    </div>
                                    <div class="col-md-4">
                                        <h5><?php echo getCount($info->areas) ?><br><small>Areas</small></h5>
                                    </div>
                                    <div class="col-md-3">
                                        <h5><?php echo getCount($info->work) ?><br><small>Types of work</small></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-7">
                        <div class="caard">
                            <div class="content">

                              <div class="row">
                                <div class="col-md-4">
                                  <h4 class="title">Work</h4>
                                  <table class="table table-striped">
<?php echo makelist($work, $info->work) ?>
            <?php if ($info->work_other) { echo '<tr><td>'.$info->work_other . '</td></tr>'; } ?>

                                  </table>
                                </div>
                                <div class="col-md-4">
                                  <h4 class="title">Areas</h4>
                                  <table class="table table-striped">
<?php echo makelist($work_areas, $info->areas) ?>
            <?php if ($info->areas_other) { echo '<tr><td>'.$info->areas_other . '</td></tr>'; } ?>

                                  </table>
                                </div>
                                <div class="col-md-4">
                                  <h4 class="title">Scales</h4>
                                  <table class="table table-striped">
<?php echo makelist($scales, $info->scales) ?>

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
