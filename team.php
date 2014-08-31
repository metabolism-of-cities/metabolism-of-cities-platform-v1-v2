<?php
require_once 'functions.php';
$section = 2;
$page = 3;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Team | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

      <div class="jumbotron">
        <h1>Team</h1>
        <p>At present, this website is a one-person activity. But hopefully not for long! When will you join in?!</p>
        <ul>
          <li>Paul Hoekman - MPhil Student at the University of Cape Town, South Africa - Initial creator of this website while
          doing research into the feasibility of undertaking an urban-scale Material Flow Analysis in the South African context.</li>
        </ul>
        <p>
          <a class="btn btn-lg btn-primary" href="page/contact" role="button">Join now &raquo;</a>
        </p>
      </div>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
