<?php
if ($_GET['public_login']) {
  $public_login = true;
}
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 6;
$load_menu = 3;
$sub_page = 3;

$values_only = (int)$_GET['values-only'];
if ($values_only) {
  $_GET['id'] = $values_only;
}

$id = (int)$_GET['id'];

$dataset = $db->record("SELECT * FROM mfa_dataset WHERE id = $project");

if (!$dataset->year_start || !$dataset->year_end) {
  $error = "You have not set the start and end year of your dataset. Set this first";
}

$list = $db->query("SELECT *,
  (SELECT COUNT(*) FROM mfa_materials m WHERE m.mfa_group = $id AND m.code LIKE CONCAT(mfa_materials.code, '.%')) AS subcategories 
FROM mfa_materials WHERE mfa_group = $id ORDER BY mfa_materials.code");

$years = range($dataset->year_start, $dataset->year_end);

$tables = $db->query("SELECT * FROM mfa_groups WHERE dataset = $project ORDER BY section");

$info = $db->record("SELECT * FROM mfa_groups WHERE id = $id AND dataset = $project");

$dataresults = $db->query("SELECT SUM(data*multiplier) AS total, mfa_data.year, mfa_data.material,
  mfa_materials.code
  FROM mfa_data
  JOIN mfa_materials ON mfa_data.material = mfa_materials.id
WHERE mfa_materials.mfa_group = $id AND mfa_data.include_in_totals = 1
GROUP BY mfa_materials.code, mfa_data.year");

if (count($dataresults)) {
  foreach ($dataresults as $row) {
    $data[$row['year']][$row['material']] = $row['total'];
    $overall_total[$row['year']] += $row['total'];
    $explode = explode(".", $row['code']);
    if (is_array($explode)) {
      unset($code);
      foreach ($explode as $value) {
        //$code .= $code ? ".$value" : $value;
        //$total[$code][$row['year']] += $row['total'];
      }
    }
  }
}

$population_list = $db->query("SELECT * FROM mfa_population WHERE dataset = $project");

foreach ($population_list as $row) {
  $population[$row['year']] = $row['population'];
}

function i($string) {
  $string = html_entity_decode($string);
  $string = utf8_decode($string);
  $string = trim($string);
  $first = substr($string, 0, 1);
  $check_int = (int)$first;
  $multiline = strpos($string, "\n");
  if ((!$multiline) && ($first > 0 || $first == "-" || $first == "+" || $first == "=" || $first == "0")) {
    // If there are commas, then we need the special formatting:
    // http://stackoverflow.com/questions/165042/stop-excel-from-automatically-converting-certain-text-values-to-dates
    $check_for_commas = strpos($string, ",");
    if ($check_for_commas > -1) {
      return '"=""' . $string . '"""';
    } else {
      return '="' . $string . '"';
    }
  }
  return '"' . $string . '"';
}

header('Content-Type: text/csv; charset=ISO-8859-1');
header('Content-Disposition: attachment; filename="'.$dataset->name.' '.$info->name.'.csv"');
?>
Category, <?php foreach ($years as $year) { ?>
<?php echo $year ?> (<?php echo $dataset->measurement ?>),<?php if ($population[$year]) { $extra_th++; ?>
<?php echo $year ?> - per cap. (<?php echo $dataset->measurement ?>/1000),<?php } ?><?php } ?>
<?php echo "\n"; ?>
<?php $count = 0; ?>
<?php foreach ($list as $row) { $count++; $all_zero = true; ?>
<?php echo i($row['code']. ". " . $row['name']) ?>,<?php foreach ($years as $year) {
$datapoint = $data[$year][$row['id']];
$final[$year] += $datapoint;
if ($overall_total[$year]) {
if (!$row['subcategories'] || $datapoint) {
$width = $datapoint/$overall_total[$year]*100;
} else {
$width = ($total[$row['code']][$year]/$overall_total[$year])*100;
}
} else {
$width = 0;
}
?>
<?php if (!$row['subcategories'] || $datapoint) { ?>
<?php $data_print = $datapoint; ?>
<?php echo $data_print > -1 ? number_format($data_print, $dataset->decimal_precision, '.', '') : ''; ?>
<?php if ($datapoint > -1) { $all_zero = false; } ?>
<?php } else { ?>
<?php $data_print = $total[$row['code']][$year]; ?>
<?php echo $data_print > -1 ? number_format($data_print, $dataset->decimal_precision, '.', '') : ''; ?>
<?php if ($total[$row['code']][$year] > 0) { $all_zero = false; } ?>
<?php } ?>,<?php if ($population[$year]) { ?><?php echo $data_print > -1 ? number_format($data_print/$population[$year]*1000, $dataset->decimal_precision, '.', '') : '';  ?>,<?php } ?>
<?php } echo "\n"; ?>
<?php if ($all_zero && $values_only) { $hiderow[] = $count; } ?>
<?php } ?>
<?php echo "\n"; ?>
<?php echo i($info->name) ?>,<?php foreach ($years as $year) { ?><?php echo number_format($final[$year],$dataset->decimal_precision, '.', '') ?>,<?php if ($population[$year]) { ?><?php echo number_format($final[$year]/$population[$year]*1000,$dataset->decimal_precision, '.', '') ?>,<?php } ?>
<?php } ?>
<?php echo "\n"; ?>
<?php if ($dataset->banner_text) { ?>
<?php echo "\n" ?>
<?php echo "\n" ?>
<?php $link = $public_login ? "projectinfo" : "dataset"; echo i($dataset->banner_text . " Go to " . URL . "$omat_link/$project/$link to read more"); ?>
<?php } ?>
