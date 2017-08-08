<?php
require_once 'functions.php';
$section = 6;
$show_breadcrumbs = true;

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
  </head>

  <body>

<?php require_once 'include.header.php'; ?>


      <div class="container">
        <h2 class="title-divider">
          <span>News</span>
        </h2>
        <p>This page contents news feeds from organization across the political spectrum active in EPR</p>
        <div class="row">
          <!--Blog Roll Content-->
          <div class="col-md-9 blog-roll blog-list">
          <?php foreach ($list as $row) { ?>
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
                    <p><?php echo $row['content'] ?></p>
                    <ul class="list-inline links">
                      <li class="list-inline-item">
                        <a href="<?php echo $row['external_link'] ?>" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-circle-right"></i> Read more</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
          </div>
          <!--Sidebar-->
          <div class="col-md-3 sidebar-right">
            
            <!-- @Element: Search form -->
            <div class="mb-4 hidden">
              <form role="form">
                <div class="input-group">
                  <label class="sr-only" for="search-field">Search</label>
                  <input type="search" class="form-control" id="search-field" placeholder="Search">
                  <span class="input-group-btn">
                    <button class="btn btn-secondary" type="button">Go!</button>
                  </span>
                </div>
              </form>
            </div>
            
            <!-- @Element: Archive -->
            <div class="mb-4">
              <h4 class="title-divider">
                <span>Archive</span>
              </h4>
              <ul class="list-unstyled list-lg tags">
                <?php foreach ($months as $row) { ?>
                  <li><i class="fa fa-angle-right fa-fw"></i> <a href="news/<?php echo $row['year'] ?>/<?php echo $row['month'] ?>">
                  <?php echo format_date("M Y", $row['year']."-".$row['month']."-01") ?></a>
                  (<?php echo $row['total'] ?>)
                  </li>
                <?php } ?>
              </ul>
            </div>
            
            <?php if (ID == 1) { ?>
            <!-- @Element: Subscrive button -->
            <div class="mb-4">
              <a href="#" class="btn btn-warning"><i class="fa fa-rss"></i> Subscribe via RSS feed</a>
            </div>
            
            <!-- Follow Widget -->
            <div class="mb-4">
              <h4 class="title-divider">
                <span>Follow Us On</span>
              </h4>
              <!--@todo: replace with real social media links -->
              <ul class="list-unstyled social-media-branding">
                <li>
                  <a href="#" class="social-link branding-twitter"><i class="fa fa-twitter-square fa-fw"></i> Twitter</a>
                </li>
                <li>
                  <a href="#" class="social-link branding-facebook"><i class="fa fa-facebook-square fa-fw"></i> Facebook</a>
                </li>
                <li>
                  <a href="#" class="social-link branding-linkedin"><i class="fa fa-linkedin-square fa-fw"></i> LinkedIn</a>
                </li>
                <li>
                  <a href="#" class="social-link branding-google-plus"><i class="fa fa-google-plus-square fa-fw"></i> Google+</a>
                </li>
              </ul>
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
      <!--.container-->

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
