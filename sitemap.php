<?php
require_once 'functions.php';
$section = 8;
$page = 5;
$show_breadcrumbs = true;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Sitemap | <?php echo SITENAME ?></title>
    <style type="text/css">
    h2{font-size:18px}
    .col a.list-group-item{color:#0275d8}
    .col{margin:30px 0}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  
    <h1>Sitemap</h1>

    <div class="row">
    <?php $i = -1; foreach ($menu as $key => $value) { $i++; ?>

    <?php if ($i == 3) { ?>
      <div class="w-100"></div>
    <?php  $i = 0;} ?>

    <div class="col">

      <h2><?php echo $value['label'] ?></h2>
      <?php if (is_array($value['menu'])) { ?>
        <div class="list-group list-group-striped">
        <?php foreach ($value['menu'] as $key => $value) { ?>
          <a class="list-group-item" href="<?php echo $value['url'] ?>"><?php echo $value['label'] ?></a>
        <?php } ?>
        </div>
      <?php } else { ?>
        <p><a href="<?php echo $value['url'] ?>">Go to page</a></p>
      <?php } ?>
    </div>

    <?php } ?>
    </div>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
