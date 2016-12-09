<?php
require_once 'functions.php';
$section = 2;
$page = 6;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Links | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Links</h1>

  <ul>
    <li><a href="https://isie-sem.blogspot.co.za/">ISIE-SEM: Socio-economic Metabolism Blog</a><br />
    ISIE’s SEM section is the largest global network of researchers employing
    material flow analysis as a tool for environmental sustainability
    assessment. This site aims to keep members up to date with news about the
    section's activities.
    </li>
    <li><a href="http://www.resourceefficientcities.org/">Global Initiative for Resource Efficient Cities</a></li>
    <li><a href="http://rcnsustainablecities.com/">Research Coordination Network: Sustainable Cities - People and Infrastructures at the Water-Energy-Climate Nexus</a></li>
    <li><a href="http://sdg.iisd.org/sdgs/goal-11-sustainable-cities-communities/">SDG Knowledge Hub - Goal 11: Sustainable Cities &amp; Communities</a></li>
  </ul>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
