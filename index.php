<?php
require_once 'functions.php';

$section = 1;
$page = 1;

$papers = $db->record("SELECT SQL_CACHE COUNT(*) AS total FROM papers WHERE status = 'active'");
$collections = $db->record("SELECT SQL_CACHE COUNT(*) AS total FROM tags_parents");
$tags = $db->record("SELECT SQL_CACHE COUNT(*) AS total FROM tags");
$tagsused = $db->record("SELECT SQL_CACHE COUNT(*) AS total FROM tags_papers");
$projects = $db->record("SELECT SQL_CACHE COUNT(*) AS total FROM research");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Material Flow Analysis Resources and Tools | <?php echo SITENAME ?></title>
    <style type="text/css">
    .jumbotron{background:#f4f4f4 url(img/globe.arrow.png) no-repeat right top;}
    .stats{background:url(img/stats.png) no-repeat right top}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

      <div class="jumbotron">
        <h1>MFA Tools</h1>
        <p>
          This website attempts to group together tools and publications related to 
          Material Flow Analysis (MFA). The principal sections are the following:
        </p>
        <p>
          <a class="btn btn-lg btn-primary" href="publications/collections" role="button">Publication Collections</a>
          <a class="btn btn-lg btn-primary" href="publications/list" role="button">Publication Database</a>
          <a class="btn btn-lg btn-primary" href="research/list" role="button">Current Research</a>
          <a class="btn btn-lg btn-primary" href="omat/about" role="button">Online MFA Tool (OMAT)</a>
        </p>
      </div>

      <h2>About</h2>

      <p>
        This website is an open source initiative launched in August 2014. It was launched with the purpose of:
      </p>
      <ul>
        <li>Making it easier to get an overview of the publications out there related to Material Flow Analysis.</li>
        <li>Allowing researchers to easily (co-) create online Material Flow Analysis projects and share the underlying datasets.</li>
      </ul>

      <p>All code for this website is hosted on <a href="https://github.com/paulhoekman/mfa-tools">github</a> and we invite programmers to help us improve the
      website and the online MFA software. But you don't need to be a programmer to help improve this website (see below!).
      Sign up for <a href="page/mailinglist">our mailing list</a> if you wish to receive updates about changes and additions.
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
