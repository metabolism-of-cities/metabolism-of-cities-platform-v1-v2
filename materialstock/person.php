<?php
require_once '../functions.php';
require_once 'functions.stock.php';

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM questionnaire WHERE id = $id");
$list = $db->query("SELECT * FROM questionnaire WHERE id > 7 ORDER BY affiliation");
$title = $info->firstname . " " . $info->lastname;

$get_af = array_flip($affiliations);
$institution = $get_af[$info->affiliation];

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

$people = true;
?>
<!doctype html>
<html lang="en">
<head>
    <?php require_once 'include.head.php'; ?>
	<title><?php echo $info->firstname ?> <?php echo $info->lastname ?></title>
    <style type="text/css">
    
    .author img{max-width:90%}
    .author p.center{text-align:center}
.card-user .author {margin-top:10px}
    </style>

</head>
<body>

<?php require_once 'include.header.php'; ?>

        <div class="content">
            <div class="container-fluid">

<div class="row">
                    <div class="col-lg-4 col-md-5">
                        <div class="card card-user">
                            <div class="content">
                                <div class="author">
                                  <p class="center">
                                  <?php $ext = "jpg"; if (file_exists("assets/img/logos/$institution.png")) { $ext = "png"; } ?>
                                  <img src="assets/img/logos/<?php echo $institution ?>.<?php echo $ext ?>" alt="...">
                                  </p>
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
