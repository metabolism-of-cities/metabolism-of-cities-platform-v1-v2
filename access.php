<?php
require_once 'functions.php';

$id = (int)$_GET['id'];
$hash = $_GET['hash'];

if ($hash != encrypt("PROFILE $id")) {
  kill("No access", "critical");
}

$info = $db->record("SELECT 
people.*, people_access.active, people_access.email AS access_email
FROM people_access 
  JOIN people ON people_access.people = people.id
WHERE people_access.id = $id AND people.active IS TRUE");

if (!$info->id) {
  kill("Profile not found", "critical");
}

if (!$info->active) {
  $db->query("UPDATE people_access SET active = 1 WHERE id = $id LIMIT 1");
}

setcookie("id", $id, time()+60*60*24*7, "/");
setcookie("hash", $hash, time()+60*60*24*7, "/");

if ($_GET['page'] == 'data') {

  header("Location: " . URL . "profile/$id/data");
  exit();

} else {

  header("Location: " . URL . "profile/$id/dashboard");
  exit();

}

?>
