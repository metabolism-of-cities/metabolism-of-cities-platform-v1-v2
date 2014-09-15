<?php
require_once 'functions.php';
$section = 2;
$page = 7;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Version History | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <div class="jumbotron">
    <h1>Version History</h1>
    <p>MFA Tools is currently at version <?php echo $version ?>. View the development log below for more details.</p>
  </div>

  <hgroup>
    <h2>Version 0.9</h2>
    <h3>August 12, 2014</h3>
  </hgroup>
  <p>Public launch of the website! The website contains the following functionalities and content:</p>
  <ul>
    <li>165 publications</li>
    <li>1 public research project</li>
    <li>1 private dataset | 0 public datasets</li>
    <li>All publications have been tagged, albeit often after a quick scan.</li>
    <li>OMAT is used in private beta. Screenshots and development roadmap are made publicly available.</li>
    <li>Option to add publications and research projects exist</li>
  </ul>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
