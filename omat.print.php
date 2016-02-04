<?php
require_once 'functions.php';
require_once 'functions.omat.php';

$section = 6;
$load_menu = 1;
$sub_page = 1;
$id = (int)$_GET['id'];

$list = $db->query("SELECT * FROM mfa_groups WHERE dataset = $id ORDER BY section");

foreach ($list as $row) {
  $sublist[$row['id']] = $db->query("SELECT * FROM mfa_materials WHERE mfa_group = {$row['id']} ORDER BY code");
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Print Data Overview | <?php echo SITENAME ?></title>
    <link rel="stylesheet" href="css/sidebar.css" />
    <style type="text/css">
    a.right{float:right}
    td,th{font-family:Menlo,Monaco,Consolas,"Courier New",monospace;}
    @media print {
      td,th {
        font-size:11px;
      }
     table {
       page-break-after: always;
     }
    }
    h2 {
      font-size:20px;
    }
    </style>
  </head>

  <body class="omat">

<?php require_once 'include.header.php'; ?>
<?php require_once 'include.omatheader.php'; ?>

  <h1 class="noprint">Print Data Groups</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li><a href="omat/<?php echo $project ?>/manage">Data</a></li>
    <li class="active">Print</li>
  </ol>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <?php foreach ($list as $row) { ?>
    <h2><?php echo $row['section'] ?>. <?php echo $row['name'] ?></h2>
    <table class="table table-striped">
    <?php foreach ($sublist[$row['id']] as $subrow) { ?>
      <tr>
        <td class="mono"><?php echo $subrow['code'] ?></td>
        <td style="padding-left:<?php echo strlen($subrow['code'])*7 ?>px"><?php echo $subrow['name'] ?></td>
      </tr>
    <?php } ?>
    </table>
  <?php } ?>

<?php require_once 'include.omatfooter.php'; ?>
<?php require_once 'include.footer.php'; ?>

  </body>
</html>
