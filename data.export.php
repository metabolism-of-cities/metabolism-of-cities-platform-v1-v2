<?php
require_once 'functions.php';

function outputCSV($data) {
  $output = fopen("php://output", "w");
  foreach ($data as $row) {
    fputcsv($output, $row);
  }
  fclose($output);
}
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="data-export.csv"');

$csv[] = array("City/area", "Microterritorial Unit", "Category", "Sub-category", "Indicator", "Year", "Value", "Unit", "Comments", "Source", "DOI", "URL");


foreach ($indicators as $row) {
  $csv[] = array(
    $row['city'],
    $row['mtu'],
    $row['area'],
    $row['subarea'],
    $row['indicator'],
    $row['year'],
    $row['value'],
    $row['unit'],
    $row['notes'],
    $row['title'],
    $row['doi'],
    URL . "publication/".$row['paper'],
  );

}
$csv[] = array();
$csv[] = array("Total: " . count($indicators) . " data points. Generation date: " . date("r"));
$csv[] = array("This data is collected on the open source Metabolism of Cities website: http://metabolismofcities.org."); 
$csv[] = array("Errors or shortcomings? Contact us to help improve the data!");

outputCSV($csv);
?>
<?php die(); ?>
