<?php
require_once '../functions.php';
require_once 'functions.stock.php';

$all = $db->record("SELECT COUNT(*) AS total FROM questionnaire WHERE id > 7");
$list = $db->query("SELECT * FROM questionnaire WHERE id > 7");

foreach ($list as $row) { 
  $get_af = array_flip($affiliations);
  $institution = $get_af[$row['affiliation']];
  $value = $row['scales'];
  $explode = explode(",", $value);
  foreach ($explode as $key => $value) {
    if ($value) {
      $active[$institution][$value] = true;
    }
  }
}

function makelist($array, $values) {
  $explode = explode(",", $values);
  foreach ($explode as $key => $value) {
    if ($array[$value]) {
      $string .= $array[$value] . "<br />";
    }
  }
  return $string ? substr($string, 0, -6) : "";
}

$scales_page = true;
$title = "Scales";
unset($scales[99]);
?>
<!doctype html>
<html lang="en">
<head>
    <?php require_once 'include.head.php'; ?>
	<title>Material Stock Survey Results</title>
    <?php if (false) { ?>
    <style type="text/css">
    .sidebar{display:none}
.main-panel{width:100%}
    </style>
    <?php } ?>

<style type="text/css">
.table a{color:#000;text-decoration:underline}
    table .fa{font-size:155%;color:green}
</style>
</head>
<body>

<?php require_once 'include.header.php'; ?>

        <div class="content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Scales</h4>
                                <p class="category">Breakdown by type</p>
                            </div>
                            <div class="content">

                            <div class="content table-responsive table-full-width">
                                <table class="table table-striped">
                                    <thead>
                                        <th>Institution</th>
                                        <?php foreach ($scales as $key => $value) { ?>
                                            <th>
                                                <?php echo $value ?>
                                            </th>
                                        <?php } ?>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($affiliations as $key => $name) { $aff = $key; ?>
                                        <tr>
                                        	<td>
                                              <a href="institution.php?id=<?php echo $key ?>">
                                                <?php echo $name ?>
                                              </a>
                                            </td>
                                            
                                            <?php foreach ($scales as $key => $value) { ?>
                                                <td>
                                                  <?php if ($active[$aff][$key]) { ?>
                                                    <i class="fa fa-check-circle"></i>
                                                  <?php } ?>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php require_once 'include.footer.php'; ?>
