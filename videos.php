<?php
$show_breadcrumbs = true;
require_once 'functions.php';
$section = 8;
$page = 3;
$list = $db->query("SELECT * FROM videos ORDER BY title");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Videos | <?php echo SITENAME ?></title>
  <style type="text/css">
    .videos {
      list-style:none;
      margin:0;
      padding:0;
    }
    .videos li {
      display:inline-block;
      width:320px;
      vertical-align:top;
    }
    .videos img {
      width:320px;
    }
    .videos li a {
      max-height:280px;
      overflow:hidden;
      display:block;
      border:1px solid #ccc;
      padding:2px;
    }
    .panel-body {
      padding:0;
    }
    .panel-default > .panel-heading {
      height:55px;
    }
  </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Videos</h1>

  <p> 
    Please find below a collection of interesting videos about urban metabolism. 
    Do you know of any other interesting video? Please <a href="contact">contact us</a>
    and we will gladly add it to the site!
  </p>

  <ul class="videos">
  <?php foreach ($list as $row) { ?>
    <li>

    <div class="panel panel-default">
      <div class="panel-heading"><?php echo $row['title'] ?></div>
      <div class="panel-body">
        <span>
          <a href="videos/<?php echo $row['id'] ?>-<?php echo flatten($row['title']) ?>">
            <?php if ($row['site'] == 'youtube') { ?>
              <img src="https://img.youtube.com/vi/<?php echo $row['url'] ?>/0.jpg" alt="" />
            <?php } else { ?>
              <img src="media/videothumbs/<?php echo $row['id'] ?>.jpg" alt="" />
            <?php } ?>
          </a>
        </span>
      </div>
    </div>

     </li>
  <?php } ?>
  </ul>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
