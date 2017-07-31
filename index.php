<?php
require_once 'functions.php';

if (ID == 2) {
  header("Location: " . URL . "index.epr.php");
  exit();
}

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
    .divider-inverse.divider-arrow-b:before {
      border-top-color:#00ADBB!important;
    }
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
    .bg-white i.fa{
      color:#333 !important;
    }
    .bg-grey i.fa {
      color:#00adbb !important;
    }
    .bg-grey .mt-2 {
      color:#00adbb;
    }
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>


</div>

    <div id="features" class="bg-white">

      <div class="container p-4 py-lg-6">
        <div class="row text-center">
          <div class="col-lg-3 py-2">
            <i class="fa fa-pencil icon-3x text-primary animated animated-done" data-animate="fadeIn" data-animate-delay="0.1" style="animation-delay: 0.1s;"></i> 
            <h4 class="mt-2">
              Research
            </h4>
            <p>Consectetuer neo oppeto persto. Abdo bene enim illum paulatim veniam.</p>
          </div>
          <div class="col-lg-3 py-2">
            <i class="fa fa-database icon-3x text-primary animated animated-done" data-animate="fadeIn" data-animate-delay="0.2" style="animation-delay: 0.2s;"></i> 
            <h4 class="mt-2">
              Data
            </h4>
            <p>Euismod exerci hos iriure nimis quae volutpat. Abigo esse facilisi neque oppeto.</p>
          </div>
          <div class="col-lg-3 py-2">
            <i class="fa fa-users icon-3x text-primary animated animated-done" data-animate="fadeIn" data-animate-delay="0.3" style="animation-delay: 0.3s;"></i> 
            <h4 class="mt-2">
              Stakeholders
            </h4>
            <p>Dignissim immitto refero rusticus. Damnum esse hos loquor refoveo secundum.</p>
          </div>
          <div class="col-lg-3 py-2">
            <i class="fa fa-line-chart icon-3x text-primary animated animated-done" data-animate="fadeIn" data-animate-delay="0.4" style="animation-delay: 0.4s;"></i> 
            <h4 class="mt-2">
              OMAT
            </h4>
            <p>Consectetuer dolore duis enim facilisis molior probo quidem saluto.</p>
          </div>
        </div>
      </div>
    </div>

    <div id="features" class="bg-grey">
      <div class="bg-inverse text-white p-3 p-lg-4 text-center divider-arrow divider-arrow-t divider-inverse">
        <div class="container">
          <h2 class="text-center text-uppercase font-weight-bold my-0">
            Main Sections
          </h2>
          <h5 class="text-center font-weight-light mt-2 mb-0 text-white op-5">
            An online hub for urban metabolism
          </h5>
        </div>
      </div>

      <div class="bg-blue text-white p-3 p-lg-4 text-center divider-arrow divider-arrow-b divider-inverse">
        <div class="container">
          <h2 class="text-center text-uppercase font-weight-bold my-0">
            Publications
          </h2>
          <h5 class="text-center font-weight-light mt-2 mb-0 text-white op-5">
            We have indexed <strong><?php echo $papers->total ?></strong> publications and counting!
          </h5>
        </div>
      </div>
      <div class="container p-4 py-lg-6">
        <div class="row text-center">
          <div class="col-lg-3 py-2">
            <i class="fa fa-list icon-3x text-primary animated animated-done" data-animate="fadeIn" data-animate-delay="0.1" style="animation-delay: 0.1s;"></i> 
            <h4 class="mt-2">
              Database
            </h4>
            <p>Consectetuer neo oppeto persto. Abdo bene enim illum paulatim veniam.</p>
          </div>
          <div class="col-lg-3 py-2">
            <i class="fa fa-book icon-3x text-primary animated animated-done" data-animate="fadeIn" data-animate-delay="0.2" style="animation-delay: 0.2s;"></i> 
            <h4 class="mt-2">
              Collections
            </h4>
            <p>Euismod exerci hos iriure nimis quae volutpat. Abigo esse facilisi neque oppeto.</p>
          </div>
          <div class="col-lg-3 py-2">
            <i class="fa fa-search icon-3x text-primary animated animated-done" data-animate="fadeIn" data-animate-delay="0.3" style="animation-delay: 0.3s;"></i> 
            <h4 class="mt-2">
              Search
            </h4>
            <p>Dignissim immitto refero rusticus. Damnum esse hos loquor refoveo secundum.</p>
          </div>
          <div class="col-lg-3 py-2">
            <i class="fa fa-plus icon-3x text-primary animated animated-done" data-animate="fadeIn" data-animate-delay="0.4" style="animation-delay: 0.4s;"></i> 
            <h4 class="mt-2">
              Add
            </h4>
            <p>Consectetuer dolore duis enim facilisis molior probo quidem saluto.</p>
          </div>
        </div>
      </div>
    </div>

    <?php require_once 'include.footer.php'; ?>

  </body>
</html>
