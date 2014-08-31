<?php
header("HTTP/1.0 404 Not Found");
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>404 Page Not Found | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <div class="jumbotron">
    <h1>404 Page Not Found</h1>
    <p>Sorry, the page you requested was not found. Please use our navigation menu to browse the website.</p>
    <p>If you believe this is an error, feel free to <a href="page/contact">contact us</a> and we will look into this.</p>
    <p>
      <a class="btn btn-lg btn-primary" href="./" role="button">Go to our homepage &raquo;</a>
    </p>
  </div>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
