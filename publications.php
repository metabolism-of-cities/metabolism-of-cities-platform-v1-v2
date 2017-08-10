<?php
$show_breadcrumbs = true;
require_once 'functions.php';
$section = 4;
$page = 1;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Publications and Research | <?php echo SITENAME ?></title>
    <style type="text/css">
    .pubcol a i{font-size:44px;display:block}
    .pubcol a {font-size:26px}
    .pubcol {margin-bottom:44px}
    .pubcol {text-align:center}
    .pubcol a:hover i{text-decoration:none}
    .jumbotron h1 {font-size:36px;padding-bottom:43px}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>


  <div class="jumbotron">
      <h1>Publications</h1>
    <div class="row">

      <?php 
        $list = array(
          //'Search database' => array('file-text-o', 'publications/results'),
          'Search' => array('search', 'publications/results'),
          'Collections' => array('th-list', 'publications/collections'),
          'Authors' => array('users', 'people'),
          'Journals' => array('columns', 'journals'),
          'Add Publication' => array('plus-circle', 'publications/add'),
        );
        if (ID == 2) {
          unset($list['Authors']);
          unset($list['Journals']);
          $list['Add Publication'][1] = 'page/submit';
        }
        else {
          unset($list['Search']);
        }
        $count = 0;
        foreach ($list as $key => $value) { $count++;
      ?>
      <?php if ($count == 5) { ?></div><div class="row pubcol"><?php } ?>

        <div class="col-md-3 pubcol">
          <a href="<?php echo $value[1] ?>">
            <i class="fa fa-<?php echo $value[0] ?>"></i>
            <?php echo $key ?>
          </a>
        </div>

        <?php } ?>

    </div>
  </div>

  <?php if (ID == 1) { ?>

  <div class="jumbotron">
    
    <h1>Research</h1>
    <div class="row">

      <?php 
        $list = array(
          'Research' => array('graduation-cap', 'research/list'),
          'Add Research' => array('plus-circle', 'research/add'),
        );
        $count = 0;
        foreach ($list as $key => $value) { $count++;
      ?>
      <?php if ($count == 5) { ?></div><div class="row pubcol"><?php } ?>

        <div class="col-md-3 pubcol">
          <a href="<?php echo $value[1] ?>">
            <i class="fa fa-<?php echo $value[0] ?>"></i>
            <?php echo $key ?>
          </a>
        </div>

        <?php } ?>

    </div>
  </div>

  <?php } ?>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
