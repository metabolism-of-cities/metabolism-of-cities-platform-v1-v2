<?php
require_once 'functions.php';
$section = 6;
$show_breadcrumbs = true;

if (ID == 1) {
  $section = 8;
  $page = 5;
}

if ($_GET['year']) {
  $year = (int)$_GET['year'];
  $sql .= " AND YEAR(date) = $year";
} 
if ($_GET['month']) {
  $month = (int)$_GET['month'];
  $sql .= " AND MONTH(date) = $month";
} 
$list = $db->query("SELECT * FROM content WHERE type = 'news' AND active = 1 $sql ORDER BY date DESC");
$months = $db->query("SELECT COUNT(*) AS total, MONTH(date) AS month, YEAR(date) AS year FROM content WHERE type = 'news' AND active = 1 
GROUP BY MONTH(date), YEAR(date)
ORDER BY date DESC
");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>News | <?php echo SITENAME ?></title>
    <style type="text/css">
    .date-m{padding:10px}
    .date-d{
    position:relative;
    top:25px;
    padding: 10px;
    background: #f4f4f4;
    border: 1px solid #ccc;
    clear: both;
    font-weight: bold;
    font-size: 20px;
    }
    .blog-post {
      margin-top:20px;
      padding-top:20px;
      border-top:1px dotted #ccc;
    }
    .blog-post .date-md {
      padding-top:10px;
    }
    @media (max-width: 1024px) {
      .blog-post .date-md {
        display:none;
      }
    }

    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>


      <div class="container">
        <h2 class="title-divider">
          <span>News</span>
        </h2>
        <?php if (ID == 2) { ?>
        <p>This page contents news feeds from organization across the political spectrum active in EPR</p>
        <?php } else { ?>
        <p>Please find the latest Metabolism of Cities news in this section.</p>
        <?php } ?>
        <div class="row">
          <!--Blog Roll Content-->
          <div class="col-md-9 blog-roll blog-list">
          <?php foreach ($list as $row) { ?>
          <?php if (ID == 1) { 
            $row['external_link'] = 'news/'.$row['id'].'-'.$row['slug'];  
          }
          ?>
            <div class="row blog-post">
              <div class="col-md-1 date-md">
                <!-- Date desktop -->
                <div class="date-wrapper"> <span class="date-m bg-primary"><?php echo format_date("M", $row['date']) ?></span> <span class="date-d"><?php echo format_date("d", $row['date']) ?></span> </div>
              </div>
              <div class="col-md-11">
                <h4 class="title media-heading">
                  <a href="<?php echo $row['external_link'] ?>"><?php echo $row['title'] ?></a>
                </h4>
                <!-- Meta details mobile -->
                <ul class="list-inline meta text-muted">
                  <li class="list-inline-item"><i class="fa fa-calendar"></i> <?php echo format_date("l M d, Y", $row['date']) ?></li>
                </ul>
                <div class="row">

                  <div class="col-md-12">
                    <p><?php echo smartcut(strip_tags($row['content']), 200) ?></p>
                    <ul class="list-inline links">
                      <li class="list-inline-item">
                        <a href="<?php echo $row['external_link'] ?>" class="btn btn-secondary btn-sm btn-info"><i class="fa fa-arrow-circle-right"></i> Read more</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
          </div>
          <!--Sidebar-->
        <?php require_once 'include.news-aside.php'; ?>
        </div>
      </div>
      <!--.container-->

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
