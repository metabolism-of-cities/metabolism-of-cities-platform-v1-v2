<?php
require_once 'functions.php';

$id = (int)$_GET['id'];
$info = $db->record("SELECT * FROM mfa_data WHERE id = $id");

if ($info->id) {
  $material = $info->material;
  $db->query("DELETE FROM mfa_data WHERE id = $id LIMIT 1");
  header("Location: " . URL . "omat/data/$material/deleted");
  exit();
} else {
  die("Invalid link");
}
