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
$dataviz = $db->record("SELECT * FROM datavisualizations WHERE date <= '$today' ORDER BY date DESC LIMIT 1");

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
      width:55%;
      float:left;
      margin:0 20px 10px 0;
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
        <h1><img src="img/logo.png?refresh" alt="Metabolism of Cities" /></h1>
        <p>
          This website attempts to group together tools and publications related to 
          the metabolism of cities or urban metabolism (UM). The principal sections are the following:
        </p>
        <div class="list-group">
          <p><a class="btn btn-lg btn-primary" href="publications/collections" role="button">Publication Collections</a></p>
          <p><a class="btn btn-lg btn-primary" href="publications/list" role="button">Publication Database</a></p>
          <p><a class="btn btn-lg btn-primary" href="research/list" role="button">Current Research</a></p>
          <p><a class="btn btn-lg btn-primary" href="stakeholders" role="button">Stakeholders Initiative</a></p>
          <p><a class="btn btn-lg btn-primary" href="data" role="button">Data</a></p>
          <p><a class="btn btn-lg btn-primary" href="omat/about" role="button">Online MFA Tool (OMAT)</a></p>
        </div>
      </div>

      <div class="row">
      
        <div class="col-md-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Latest Blog Post <span class="pull-right"><?php echo format_date("M d, Y", $blog->date) ?></span></h3>
            </div>
            <div class="panel-body">
                    <h4><a href="blog/<?php echo $blog->id ?>-<?php echo flatten($blog->title) ?>"><?php echo $blog->title ?></a></h4>
                    <?php echo smartcut($blog->content, 200) ?>
                    <p><a href="blog/<?php echo $blog->id ?>-<?php echo flatten($blog->title) ?>" class="btn btn-primary">Read more</a></p>
            </div>
          </div>
        </div>

        <div class="col-md-6">
       
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Latest Data Visualization <span class="pull-right"><?php echo format_date("M d, Y", $dataviz->date) ?></span></h3>
            </div>
            <div class="panel-body">
              <p class="constrain">
                <a href="datavisualizations/<?php echo $dataviz->id . "-" . flatten($dataviz->title); ?>">
                  <img src="media/dataviz/<?php echo $dataviz->id ?>.jpg" alt="" />
                </a>
                <a class="floater btn btn-primary" href="datavisualizations/<?php echo $dataviz->id . "-" . flatten($dataviz->title); ?>">
                  View visualization
                </a>
              </p>
            </div>
          </div>

        </div>


      </div>

<div class="alert alert-warning">
<span class="pull-right">Dec 5, 2016</span>
<h2>Metabolism of Cities co-organises three masterclasses on urban metabolism</h2>

<p>

As part of our <a href="stakeholders">Stakeholders Initiative</a>, we are proud to announce that Metabolism of Cities will be co-organising (with OVAM, .Fabric, Vlaamse Milieumaatschappij (VMM/MIRA), and Team Vlaams Bouwmeester) three masterclasses on urban metabolism. The masterclasses are entitled Designing With Flows: Towards an Urban-Metabolic Agenda for a Circular Future and will take place in Brussels on the 08/12/2016, 12/01/2017, 02/02/2017. 
</p>


<p>For more information and registration <a href="http://www.architectureworkroom.eu/atelierbrussel/masterclass-designing-with-flows">click here</a>.
</p>

<p><a href="http://www.architectureworkroom.eu/atelierbrussel/masterclass-designing-with-flows" class="btn btn-primary">Urban metabolism masterclasses &raquo;</a></p>

</div>


      <h2>Just Launched: Data Visualizations Project - October-December 2016</h2>

      <p>We have launched our first <a href="stakeholders">Stakeholders Initiative</a>! From October until 
      December 2016 we will focus on Data Visualization, and we have many exciting things planned. 
      And we need YOUR involvement. Check out the new section and start contributing now! 
      </p>
      <p><a href="datavisualization" class="btn btn-primary">Data Visualization Project</a></p>
      <h2>About</h2>

      <p>
        This website is an open source initiative launched in August 2014 as 'MFA Tools'. It was launched with the purpose of:
      </p>
      <ul>
        <li>Making it easier to get an overview of the publications out there related to Material Flow Analysis.</li>
        <li>Allowing researchers to easily (co-) create online Material Flow Analysis projects and share the underlying datasets.</li>
      </ul>

      <p>In January 2016 the website was renamed to <strong>Metabolism of Cities</strong>, to reflect the broadening scope to other
      methodologies. </p>
      <p>All code for this website is hosted on <a href="https://github.com/paulhoekman/mfa-tools">github</a> and we invite
      programmers to help us improve the
      website and the online MFA software. But you don't need to be a programmer to help improve this website (see below!).
      Sign up for <a href="page/mailinglist">our mailing list</a> if you wish to receive updates about changes and additions.
      </p>
      <p>
           You can now also follow us on 
          <a href="https://twitter.com/CityMetabolism"><i class="fa fa-twitter"></i> Twitter</a>
      </p>

      <div class="panel panel-default">
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

      <h2>Acknowledgements</h2>

      <p>
        Hosting and domain registration for our website is sponsored by 
        <a href="https://penguinprotocols.com">Penguin Protocols</a>. We would furthermore like to 
        thanks all individuals contributing content to our website. The website run by a <a href="page/team">small team</a> of 
        volunteers.
      </p>

    <?php require_once 'include.footer.php'; ?>

  </body>
</html>
