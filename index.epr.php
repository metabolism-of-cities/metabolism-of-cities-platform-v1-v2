<?php
require_once 'functions.php';

$section = 1;
$page = 1;

$papers = $db->record("SELECT SQL_CACHE COUNT(*) AS total FROM papers WHERE status = 'active'");
$collections = $db->record("SELECT SQL_CACHE COUNT(*) AS total FROM tags_parents");
$tags = $db->record("SELECT SQL_CACHE COUNT(*) AS total FROM tags");
$tagsused = $db->record("SELECT SQL_CACHE COUNT(*) AS total FROM tags_papers");
$projects = $db->record("SELECT SQL_CACHE COUNT(*) AS total FROM research WHERE deleted_on IS NULL");

$hide_regular_translate = true;

$today = date("Y-m-d");

$blog = $db->record("SELECT * FROM blog WHERE active = 1 AND date <= '$today' ORDER BY date DESC LIMIT 1");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo SITENAME ?>: Urban Metabolism Research Resources and Tools</title>
    <style type="text/css">
    .floater {
      position:absolute;
      bottom:0;
      right:0;
    }
    .jumbotron{background:#f4f4f4;position:relative;overflow:hidden;}
    @media (min-width:666px){
      .stats{background:url(img/stats.png) no-repeat right top}
    }
    #google_translate_element{position:absolute;top:10px;left:10px}
    .jumbotron h1 img {
      float:left;
      margin:0 60px 10px 0;
    }
    .jumbotron p {
      margin:0 0 6px 0;
    }
    p.constrain img {
      max-width:100%;
    }
    p.constrain {
      height:150px;
      position:relative;
      width:100%;
      overflow:hidden;
    }
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

      <div class="jumbotron">
        <div id="google_translate_element"></div>
        <h1><img src="img/epr.logo.png?refresh" alt="Metabolism of Cities" /></h1>
        <p>
          This website is a central hub for EPR literature, both journal publications and 
          grey literature. 
        </p>
        <div class="list-group" style="margin-top:20px">
          <p><a class="btn btn-lg btn-primary" href="publications/collections" role="button">Publication Collections</a></p>
          <p><a class="btn btn-lg btn-primary" href="publications/list" role="button">Publication Database</a></p>
          <p><a class="btn btn-lg btn-primary" href="publications/search" role="button">Search Publications</a></p>
          <p><a class="btn btn-lg btn-primary" href="people" role="button">Authors</a></p>
        </div>
      </div>


<div class="alert alert-warning">
<span class="pull-right">Dec 5, 2016</span>
<h2>EPR Central co-organises three masterclasses on urban metabolism</h2>

<p>

As part of our <a href="stakeholders">Stakeholders Initiative</a>, we are proud to announce that Metabolism of Cities will be co-organising (with OVAM, .Fabric, Vlaamse Milieumaatschappij (VMM/MIRA), and Team Vlaams Bouwmeester) three masterclasses on urban metabolism. The masterclasses are entitled Designing With Flows: Towards an Urban-Metabolic Agenda for a Circular Future and will take place in Brussels on the 08/12/2016, 12/01/2017, 02/02/2017. 
</p>


<p>For more information and registration <a href="http://www.architectureworkroom.eu/atelierbrussel/masterclass-designing-with-flows">click here</a>.
</p>

<p><a href="http://www.architectureworkroom.eu/atelierbrussel/masterclass-designing-with-flows" class="btn btn-primary">Urban metabolism masterclasses &raquo;</a></p>

</div>


      <h2>New Additions: publication ABC on EPR</h2>

      <p>More details about a recent publication...</p>
      <p><a href="datavisualization" class="btn btn-primary">Data Visualization Project</a></p>

      <div class="panel panel-default" style="margin-top:30px">
        <div class="panel-heading">
          <h3 class="panel-title">Quick Statistics</h3>
        </div>
        <div class="panel-body stats">
          <ul>
            <li><span class="badge"><?php echo $projects->total ?></span> Research projects listed, see the <a href="research/list">current research list</a>.</li>
            <li><span class="badge"><?php echo $papers->total ?></span> Papers and other publications listed in the <a href="publications/list">database</a>.</li>
            <li><span class="badge"><?php echo $collections->total ?></span> Publication <a href="publications/collections">collections</a>.</li>
            <li><span class="badge"><?php echo $tags->total ?></span> Unique tags used for classifying publications.</li>
            <li><span class="badge"><?php echo $tagsused->total ?></span> Tags used in total.</li>
          </ul>
        </div>
      </div>

      <form method="post" class="form form-inline" action="page/mailinglist">
         <input type="email" class="form-control" name="email" placeholder="Sign up for our newsletter" />
         <button type="submit" class="btn btn-primary">Sign Up</button>
      </form>

      <h2>New: Urban Metabolism Map</h2>

      <p><a href="page/map"><img src="img/map.png?refresh" alt="Map" /></a></p>
      <p>
        We have recently created a map to visualize all urban metabolism studies that have been indexed
        in our database with publications. <a href="page/map">Check it out!</a>
      </p>

      <h2>Help us improve! <img src="img/pencil.png" alt="" /></h2>

      <p>You can help us by:</p>
      <ul>
        <li><a href="publications/add">Adding new publications</a> or <a href="publications/list">improving the current database</a>.</li>
        <li><a href="page/contact">Helping us better define</a> how to classify publications that are included in the database.</li>
        <li>Reviewing <a href="omat/about">our online MFA software roadmap</a> and <a href="page/contact">providing feedback</a>.</li>
        <li>Telling others about this website!</li>
      </ul>

    <?php require_once 'include.footer.php'; ?>

  </body>
</html>
