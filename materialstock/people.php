<?php
require_once '../functions.php';
require_once 'functions.stock.php';
$people = true;
$title = "People";
$list = $db->query("SELECT * FROM questionnaire WHERE id > 7 ORDER BY firstname");
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
<?php foreach ($list as $row) { ?>
<li><a href="person.php?id=<?php echo $row['id'] ?>"><?php echo $row['firstname'] ?> <?php echo $row['lastname'] ?></a></li>
<?php } ?>
</ul>

                    </div>



</div>

            </div>
        </div>

<?php require_once 'include.footer.php'; ?>
