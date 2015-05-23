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
    <h2>Version 1.2</h2>
    <h3>May 23, 2015</h3>
  </hgroup>
  <p>Important updates made to the website, including the following:</p>
  <ul>
    <li>Improvements to OMAT include automatic indicator calculation, indicator customization, time graphs, per-capital calculations and a travel log.</li>
    <li>Option for quick adding of publications by using Bibtex code</li>
    <li>Option to download the publications database as CSV file</li>
  </ul>
  <p>Quick statistics:</p>
  <ul>
    <li>210 publications</li>
    <li>4 public research projects</li>
    <li>9 private datasets | 1 public dataset</li>
  </ul>

  <hgroup>
    <h2>Version 1.1</h2>
    <h3>December 10, 2014</h3>
  </hgroup>
  <p>Important updates made to the website, including the following:</p>
  <ul>
    <li>OMAT now features a first public database: </li>
    <li>Extensive <a href="omat/documentation">documentation</a> has been made available for OMAT.</li>
    <li>Improvements to OMAT include option to work on different scales, to generate data graphs, and to work with uncertainty indicators</li>
  </ul>
  <p>Quick statistics:</p>
  <ul>
    <li>176 publications</li>
    <li>2 public research projects</li>
    <li>2 private dataset | 1 public dataset</li>
  </ul>

  <hgroup>
    <h2>Version 1.0</h2>
    <h3>September 22, 2014</h3>
  </hgroup>
  <p>Important updates made to the website, including the following:</p>
  <ul>
    <li>OMAT is now available for public use! Projects can be created online and users can get to work instantly.</li>
    <li>The following options are available in OMAT:
      <ul>
        <li>Option to load the EUROSTAT framework with a single click</li>
        <li>Option to define an own dataset</li>
        <li>Easy entering of datapoints including the related year, source, and comments</li>
        <li>Option to manage Data Quality Indicators</li>
        <li>Back-end options include management of contacts, data sources, documents, and time logging</li>
        <li>Instant generation of various indicators and their graphs</li>
      </ul>
    </li>
    <li>The website was fully uploaded to github and can be modified by anyone</li>
  </ul>
  <p>Quick statistics:</p>
  <ul>
    <li>174 publications</li>
    <li>2 public research projects</li>
    <li>1 private dataset | 0 public datasets</li>
  </ul>

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
