<?php
require_once '../functions.php';
require_once 'functions.stock.php';
$institutions = true;
$title = "Institutions";
?>
<!doctype html>
<html lang="en">
<head>
    <?php require_once 'include.head.php'; ?>
	<title><?php echo $title ?></title>
    <style type="text/css">
    .largerfont{font-size:145%}
    </style>

</head>
<body>

<?php require_once 'include.header.php'; ?>

        <div class="content">
            <div class="container-fluid">

<div class="row">
                    <div class="col-md-12">

<ul class="largerfont">
<?php foreach ($affiliations as $key => $value) { ?>
<li><a href="institution.php?id=<?php echo $key ?>"><?php echo $value ?></a></li>
<?php } ?>
</ul>

                    </div>



</div>

            </div>
        </div>

<?php require_once 'include.footer.php'; ?>
