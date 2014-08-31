<?php
require_once 'functions.php';
$section = 6;
$page = 1;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Online Material Flow Analysis Tool (OMAT) | <?php echo SITENAME ?></title>
    <style type="text/css">
    li.done{
      list-style-image:url(img/check.png);
      margin-left:5px;
      font-weight:700;
      color: #86B640;
    }
    .tab-content img {
      max-width:100%;
      border:1px solid #ccc;
      padding:4px;
      background:#f4f4f4;
    }
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

<h1>Online Material Flow Analysis Tool (OMAT)</h1>

<p>
  The Online Material Flow Analysis Tool (OMAT) is a free, open source tool that can be used to undertake
  a Material Flow Analysis (MFA). More specifically, the goals of OMAT are:
</p>

<ul>
  <li>
    To make it easy for researchers to save a dataset either for an Economy-Wide EUROSTAT-based MFA, or for a 
    dataset with datagroups defined by the researcher.
  </li>
  <li>
    To have a practical tool that helps with the logistics behind data collection, including keeping track 
    of sources and providing a space for comments and discussion around each data point.
  </li>
  <li>
    To collaboratively work on the same dataset at the same time. 
  </li>
  <li>
    To make it easy to share datasets with other researchers or with the public. 
  </li>
</ul>

<p>
  OMAT is similar to <a href="http://www.stan2web.net/">STAN</a> in that it is free software and it is available for
  flow analysis. However, OMAT is based on an online platform which means that it is not necessary to install 
  any program on the computer, and it can be used on any type of operating system. OMAT is furthermore focused on 
  EW-MFA rather than Sustance Flow Analysis.
</p>

<h2>A work in progress</h2>

OMAT is currently a work in progress. It is used by the <a href="page/team">team</a> behind this website, and 
in the coming months (August-October 2014) development of a fully functional version is expected to finalize. 
We invite programmers to join the development of this free service, and we invite researchers to <a href="page/contact">get in touch</a>
so that we can tailor the program to the need of people in the industry and get feedback from those who 
help us test the software.

<h2>Development roadmap</h2>

<ul>
  <li>Online creation of projects</li>
  <li class="done">Easy loading of all materials defined in the EUROSTAT method</li>
  <li class="done">Option to load other material groups</li>
  <li>Option to differentiate between urban and national scales</li>
  <li>Data management
    <ul>
      <li class="done">Multiple years</li>
      <li>Comments/discussion options for each data point</li>
      <li>Option to track time it took to get the particular data point</li>
    </ul>
  </li>
  <li>Inclusion of uncertainty</li>
  <li>Multiple users per project</li>
  <li>Option to share dataset with third parties</li>
  <li><a href="page/contact">Propose other features!</a></li>
</ul>

<h2>Screenshots</h2>

<ul class="nav nav-tabs" role="tablist">
  <li class="active"><a href="#screenshot1" role="tab" data-toggle="tab">Dashboard</a></li>
  <li><a href="#screenshot2" role="tab" data-toggle="tab">Data Groups</a></li>
  <li><a href="#screenshot3" role="tab" data-toggle="tab">List of Materials</a></li>
  <li><a href="#screenshot4" role="tab" data-toggle="tab">Specific Material</a></li>
  <li><a href="#screenshot5" role="tab" data-toggle="tab">Add Data Point</a></li>
</ul>

<div class="tab-content">
  <div class="tab-pane active" id="screenshot1"><img src="img/screenshots/screenshot1.png" alt="" /></div>
  <div class="tab-pane" id="screenshot2"><img src="img/screenshots/screenshot2.png" alt="" /></div>
  <div class="tab-pane" id="screenshot3"><img src="img/screenshots/screenshot3.png" alt="" /></div>
  <div class="tab-pane" id="screenshot4"><img src="img/screenshots/screenshot4.png" alt="" /></div>
  <div class="tab-pane" id="screenshot5"><img src="img/screenshots/screenshot5.png" alt="" /></div>
</div>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
