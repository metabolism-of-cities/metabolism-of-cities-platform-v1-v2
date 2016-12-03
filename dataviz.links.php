<?php
require_once 'functions.php';
$section = 7;
$page = 2;

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Data Visualization Links | <?php echo SITENAME ?></title>

  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <ol class="breadcrumb">
    <li><a href="./">Home</a></li>
    <li><a href="stakeholders">Stakeholders Initiative</a></li>
    <li><a href="datavisualization">Data Visualizations</a></li>
    <li class="active">Links</li>
  </ol>


    <h1>Links</h1>


    <p>Please find below links to useful sites related to data visualizations. This page will
    be updated throughout the data visualization stakeholders initiative (Oct-Dec 2016).</p>

    <h2>General Links</h2>

    <ul>
      <li><a href="http://www.datavizcatalogue.com/search.html">Dataviz Catalogue</a></li>
      <li><a href="https://www.tableau.com/sites/default/files/media/which_chart_v6_final_0.pdf">Which chart or graph is right for you?</a> (PDF)</li>
    </ul>

    <h2>Software</h2>

    <ul>
      <li><a href="https://github.com/d3/d3/wiki">d3</a></li>
      <li><a href="http://raw.densitydesign.org/">RAW</a></li>
      <li><a href="http://sankeymatic.com/">Sankeymatic</a></li>
      <li><a href="https://www.tableau.com/">Tableau</a></li>
    </ul>

    <h2>Inspiring examples</h2>

    <ul>
      <li><a href="http://mfadiagrams.blogspot.fr/">MFA Diagrams Blog</a></li>
      <li><a href="http://tulpinteractive.com/">http://tulpinteractive.com/</a></li>
      <li><a href="http://flowingdata.com/">http://flowingdata.com/</a></li>
      <li><a href="http://alignedleft.com/work">http://alignedleft.com/work</a></li>
      <li><a href="https://github.com/d3/d3/wiki/Gallery">d3 Gallery</a></li>
      <li><a href="http://www.energyatlas.ucla.edu/">Log Angeles County Energy Atlas</a></li>
    </ul>

    <p>Be sure to <a href="page/contact">contact us</a> if you can suggest a useful link
    or other resource to be added to this page.</p>

    <p><a href="datavisualization" class="btn btn-primary">Back to the Data Visualization portal</a></p>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
