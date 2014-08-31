<?php
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Debug | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <div class="jumbotron">
    <h1>Error!</h1>
    <p>Let's try to debug this thing!</p>
    <pre>
      <?php echo $error ?>
    </pre>
  </div>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
