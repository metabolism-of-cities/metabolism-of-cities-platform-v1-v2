<?php
require_once 'functions.php';
require_once 'functions.omat.php';

$section = 6;
$load_menu = 1;
$sub_page = 2;

$id = $project;

$list = $db->query("SELECT * FROM mfa_contacts WHERE dataset = $id");

foreach ($list as $row) {
  if ($row['belongs_to']) {
    $contacts[$row['belongs_to']][$row['id']] = $row['name'];
  } else {
    $contactnames[$row['id']] = $row['name'];
  }
}
asort($contactnames);

function buildList($id) {
  global $contacts, $project;
  if (is_array($contacts[$id])) {
    echo '<ul>';
    foreach ($contacts[$id] as $key => $value) {
      echo '<li><a href="omat/'.$project.'/viewcontact/'.$key.'">' . $value . '</a>';
      if (is_array($contacts[$key])) {
        asort($contacts[$key]);
        buildList($key);
      }
      echo '</li>';
    }
    echo '</ul>';
  }
}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Contact List | <?php echo $info->name ?> | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Contact List</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Contacts</li>
  </ol>

  <ul>
    <?php foreach ($contactnames as $key => $value) { ?>
      <li>
        <?php echo $value ?>
        <?php buildList($key) ?>
      </li>
    <?php } ?>
  </ul>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
