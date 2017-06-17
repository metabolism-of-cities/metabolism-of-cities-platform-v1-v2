<?php
require_once '../functions.php';
$list = $db->query("SELECT * FROM questionnaire WHERE id > 7 ORDER BY id");

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

function makelist($array, $values) {
  $explode = explode(",", $values);
  foreach ($explode as $key => $value) {
    if ($array[$value]) {
      $string .= $array[$value] . "<br />";
    }
  }
  return $string ? substr($string, 0, -6) : "";
}

$primary_data = array(
0 => "",
1 => "Yes",
2 => "No",
);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Results | <?php echo SITENAME ?></title>
    <style type="text/css">
    th,td{min-width:200px}
    table{min-width:3400px;}
    body{margin:10px;padding:10px}
    th.w,td.w{min-width:800px}
    </style>
  </head>

  <body class="notranslate">

  <?php if (strtolower($_POST['d']) == "spoonbill") { ?>

    <h1>Questionnaire results</h1>

    <div class="alert alert-info">
      <?php echo count($list) ?>
      participants
    </div>

    <table class="table table-striped" >
        <tr>
            <th>Name</th>
            <th>Affiliation</th>
            <th>Place</th>
            <th>Field of work</th>
            <th>Work includes...</th>
            <th>Areas</th>
            <th>Regions</th>
            <th>Scale</th>
            <th>Materials</th>
            <th>Primary data</th>
            <th>Type of data</th>
            <th>How do you process/store/archive?</th>
            <th>Software</th>
            <th class="w">Literature</th>
        </tr>
        <?php foreach ($list as $row) { ?>
        <tr>
            <td><?php echo $row['firstname'] ?> <?php echo $row['lastname'] ?></td>
            <td><?php echo $row['affiliation'] ?></td>
            <td><?php echo $row['city'] ?>, <?php echo $row['country'] ?></td>
            <td><?php echo makelist($fields, $row['work_field']) ?></td>
            <td><?php echo makelist($work, $row['work']) ?>
            <?php if ($row['work_other']) { echo ': <br />'.$row['work_other']; } ?>
            </td>
            <td><?php echo makelist($work_areas, $row['areas']) ?>
            <?php if ($row['areas_other']) { echo ': <br />'.$row['areas_other']; } ?>
            </td>
            <td><?php echo $row['regions_other'] ?></td>
            <td><?php echo makelist($scales, $row['scales']) ?>
            <?php if ($row['scales_other']) { echo ': <br />'.$row['scales_other']; } ?>
            </td>
            <td><?php echo $row['materials_details'] ?></td>
            <td><?php echo $primary_data[$row['primary_data']] ?></td>
            <td><?php echo $row['data_type'] ?></td>
            <td><?php echo $row['data_details'] ?></td>
            <td><?php echo $row['software'] ?></td>
            <td class="w"><?php echo $row['literature'] ?></td>
        </tr>
    <?php } ?>
    </table>

    <?php } else { ?>
      <p>
      <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/8c/Eurasian_Spoonbill-2.jpg/330px-Eurasian_Spoonbill-2.jpg" alt="" />
      </p>
      <form method="post" class="form form-horizontal">
            <input class="form-control" type="text" name="d" />
        <button type="submit" class="btn btn-primary">Go</button>
      </form>
    <?php } ?>

  </body>
</html>
