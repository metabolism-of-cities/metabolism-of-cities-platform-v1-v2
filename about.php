<?php
require_once 'functions.php';
$section = 2;
$page = 1;
$papers = $db->query("SELECT SQL_CACHE COUNT(*) AS total FROM papers WHERE status = 'active'");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>About MFA Tools | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

<div class="jumbotron">

  <h1>MFA Tools</h1>

  <p>
    MFA Tools is a website that collects information useful to researchers involved in Material Flow Analysis (MFA) research.
    There are three primary sections:
  </p>

</div>

<div class="list-group">
  <a href="research/list" class="list-group-item">
    <h4 class="list-group-item-heading">Current research</h4>
    <p class="list-group-item-text">
      A list of current, ongoing research. Material Flow Analyses is currently undertaken throughout the world
      on many different levels (urban, national, regional), and with very different scopes (economy-wide, particular
      substances, etc.). This section aims to list currently ongoing research with the goal to encourage cooperation
      and to facilitate communication between different groups of researchers. Are you undertaking research? Add
      your project now!
    </p>
  </a>
  <a href="omat/about" class="list-group-item">
    <h4 class="list-group-item-heading">Online Material Flow Analysis Tool (OMAT)</h4>
    <p class="list-group-item-text">
      The <strong>O</strong>nline <strong>M</strong>aterial Flow <strong>A</strong>nalysis <strong>T</strong>ool (OMAT)
      is a free, open source tool that assists researchers in undertaking an MFA. It allows for data to be loaded into 
      an online database, with easy options for collaboration and data management. Data sets can be kept private or 
      can be publicly shared. 
    </p>
  </a>
  <a href="publications/list" class="list-group-item">
    <h4 class="list-group-item-heading">MFA Publications</h4>
    <p class="list-group-item-text">
      We have collected information on a variety of MFA-related publications. These publications
      include methodology research, case studies, handbooks, and more. The database currently 
      includes <strong><?php echo $papers->total ?></strong> publications, and we encourage
      our visitors to add references to missing publications.
    </p>
  </a>
</div>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
