<?php
require_once 'functions.php';
$skip_login = true;
require_once 'functions.omat.php';

$permissions = $db->query("SELECT * FROM users_permissions WHERE user = $user_id");

if (!count($permissions)) {
  header("Location: " . URL . "page/login");
  exit();
}

$list = $db->query("SELECT * FROM mfa_dataset WHERE id IN ($authorized)");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Projects | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Projects</h1>

  <p>You have access to several projects. Select the project you want to view below.</p>

  <ul class="nav nav-pills nav-stacked">
    <?php foreach ($list as $row) { ?>
      <li><a href="omat/<?php echo $row['id'] ?>/dashboard"><?php echo $row['name'] ?></a></li>
    <?php } ?>
  </ul>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
