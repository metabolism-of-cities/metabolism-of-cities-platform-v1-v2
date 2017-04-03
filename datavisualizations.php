<?php
require_once 'functions.php';
$section = 7;
$page = 2;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Data Visualizations | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <ol class="breadcrumb">
    <li><a href="./">Home</a></li>
    <li><a href="stakeholders">Stakeholders Initiative</a></li>
    <li class="active">Data Visualization</li>
  </ol>

  <h1>
    Data Visualizations in Urban Metabolism Research</h1>

<div class="well">
  This is an <strong>archived</strong> Stakeholders Initiative project.
</div>

    <p>
    There are many ways to visualize your results in urban metabolism research.
    However, it is a challenge to design a map or diagram that is clear and
    appealing at  the same time, and that captures the full extent of your
    dataset. Should you use sankey diagrams, maps, or other visual
    representations? What software to use? How to make it look professional
    without spending a lot of time on it? At the Metabolism of Cities website
    we want to enlist your help to answer those questions! We are setting up a
    Stakeholders Initiative and invite everyone to join the discussion. Data
    Visualizations will be our first topic of discussion, running from
    October-December 2016. In this period, we will publish blog posts (guest
    contributions are welcome), host online discussions, take stock of work in
    this field, and build or expand on open source software that can help
    develop data visualizations. 
    </p>

    <div class="row">
      <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Introduction to Data Visualizations</h3>
            </div>
            <div class="panel-body">
            <p>
              Introduction post on our blog by Aristide Athanassiadis. This post
              discusses what type of visualizations to use, which software to use, and 
              provides some examples of data visualizations. 
            </p>
            <p><a href="blog/2-data-visualizations" class="btn btn-success">Read more &raquo;</a></p>
            </div>
          </div>
      </div>
      <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Data Visualizations Examples</h3>
            </div>
            <div class="panel-body">
              <p>
                In this section we posted around 100 data visualizations! At the end of our 
                Stakeholders Initiative we hosted a voting contest and our visitors selected the 
                best visualization in the list. 
              </p>
              <p><a href="datavisualization/examples" class="btn btn-success">Read more &raquo;</a></p>
            </div>
          </div>
      </div>
    </div>    
    <div class="row">
      <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Creating your own online data visualizations</h3>
            </div>
            <div class="panel-body">
              <p>In this blog post Paul Hoekman describes several tools you can use to develop
              your own online data visualizations. These includes useful how-to's to get started. </p>
              <p><a href="blog/4-creating-your-own-online-data-visualizations" class="btn btn-success">Read more &raquo;</a></p>
            </div>
          </div>
      </div>
      <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Links</h3>
            </div>
            <div class="panel-body">
              <p>A collection of useful links related to data visualization.</p>
              <p><a href="datavisualization/links" class="btn btn-success">Read more &raquo;</a></p>
            </div>
          </div>
      </div>
    </div>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
