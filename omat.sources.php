<?php
require_once 'functions.php';
require_once 'functions.omat.php';
$project = (int)$_GET['id'];
header("Location: " . URL . "omat/$project/filters/sources");
exit();
?>
