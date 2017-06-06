<?php
require_once 'functions.php';
$section = 2;
$page = 6;
$get = '492,131,184,223';
$list = $db->query("SELECT * FROM papers WHERE status = 'active' AND id IN ($get) ORDER BY year DESC, title");
$videos = $db->query("SELECT * FROM videos WHERE id IN (4,11,3) ORDER BY title");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Urban Metabolis Starter Pack | <?php echo SITENAME ?></title>
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

  <div class="jumbotron">
    <h1>Starter Pack</h1>
    <p>Are you new to urban metabolism? Your journey starts here! The page below lists useful publications to read
    and videos to watch in order to get a good understanding of urban metabolism.</p>
    <p>
      <a class="btn btn-lg btn-primary" href="starterpack#publications" role="button">Publications</a>
      <a class="btn btn-lg btn-primary" href="starterpack#videos" role="button">Videos</a>
      <a class="btn btn-lg btn-primary" href="starterpack#data" role="button">Data</a>
    </p>
  </div>

  <h1 id="publications">Publications</h1>

  <p>Below we list a few publications that are some of the "best reads" when it comes to urban metabolism. We hand-picked
  these publications because they explain the concept from the ground up, and often review the work that has been done so far
  and explain more about this research field and its future direction.
  </p>

  <table class="table table-striped">
    <thead>
    <tr>
      <th>Title</th>
      <th width="330">Author(s)</th>
      <th width="80">Year</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list as $row) { ?>
      <tr>
        <td><a href="publication/<?php echo $row['id'] ?>"><?php echo $row['title'] ?></a></td>
        <td><?php echo $row['author'] ?></td>
        <td><?php echo $row['year'] ?></td>
      </tr>
    <?php } ?>
    </tbody>
  </table>

  <h1 id="videos">Videos</h1>

  <p>Want to get a crash course into urban metabolism? Have a look at the videos below!</p>

  <ul class="videos">
  <?php foreach ($videos as $row) { ?>
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

  <h1 id="data">Data</h1>

  <p>To understand how urban metabolism research works, explore this online dataset. You can see what kind of information
  is collected when undertaking urban metabolism research, and how it is presented.</p>

  <p><a href="omat/1/projectinfo"><img src="img/datasample.png" alt="" /></a></p>

  <p><a href="omat/1/projectinfo">View dataset for Cape Town</a></p>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
