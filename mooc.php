<?php
require_once 'functions.php';

$id = (int)$_GET['id'];
$id = 1;
$info = $db->record("SELECT * FROM mooc WHERE id = $id");
$list = $db->query("SELECT * FROM mooc_modules ORDER BY title");

if ($_GET['module']) {
  $module = (int)$_GET['module'];
  $module_info = $db->record("SELECT * FROM mooc_modules WHERE id = $module");
  $media = $db->query("SELECT * FROM mooc_media WHERE module = $module ORDER BY position");
}

$sections = array(
  'mooc' => "Homepage",
);

foreach ($list as $row) { 
  $sections[$row['id']] = $row['title'];
}

foreach ($sections as $key => $value) {
  $content[$key] = @file_get_contents("documentation.$key.php");
}

$remove_enter = array("\n" => "");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $info->name ?> | <?php echo SITENAME ?></title>
    <link rel="stylesheet" href="css/sidebar.css" />
    <style type="text/css">
    .sidebar img {position:absolute;bottom:60px;left:70px}
    .right{float:right}
    section h1{font-size:1.4em}
    #documentation img{background:#ccc;padding:4px;border:3px solid #999}
    h2{font-size:24px;color:#333}
    .limitwidth{width:95%}
  .tab-pane{display:none}
  .tab-pane.active{display:block}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
      <ul class="nav nav-sidebar">
        <?php foreach ($sections as $key => $value) { ?>
          <li<?php if ($module == $key || $key == 'mooc' && !$module) { ?> class="active"<?php } ?>>
            <a href="mooc/<?php echo $key ?>"><?php echo $value ?>
            </a>
          </li>
        <?php } ?>
      </ul>
    </div>

    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

      <?php if ($module) { ?>
        <h1><?php echo $module_info->title ?></h1>
        <?php echo $module_info->instructions ?>

        <?php if (count($media)) { ?>
          <ul class="nav nav-tabs">
          <?php 
          $count_type['video'] = 1;
          $count_type['file'] = 1;
          $count = 0; foreach ($media as $row) { $count++; 
          if ($row['type'] == 'youtube' || $row['type'] == 'vimeo') {
            $print_number = $count_type['video']++;
            $label = "Video ";
          } else {
            $print_number = $count_type['file']++;
            $label = "File ";
          }
          $print = $label . $print_number;
          ?>
            <li role="presentation" id="item-<?php echo $count ?>" class="<?php echo $count == 1 ? "active" : "reg"; ?>" data-id="<?php echo $count ?>"><a href="#" ><?php echo $print ?></a></li>
          <?php } ?>
          </ul>
          <div class="tab-content">
          <?php $count = 1; foreach ($media as $row) { ?>
            <div class="tab-pane<?php if ($count == 1) { echo ' active'; } ?>" id="tab-<?php echo $count++; ?>">
                <h3><?php echo $row['title'] ?></h3>
                <?php echo $row['description'] ?>
                <?php if ($row['type'] == "youtube") { ?>
                  <iframe width="100%" height="480" src="https://www.youtube.com/embed/<?php echo $row['url'] ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                <?php } elseif ($row['type'] == "vimeo") { ?>
                  <iframe src="https://player.vimeo.com/video/<?php echo $row['url'] ?>" width="100%" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                <?php } elseif ($row['type'] == "external_file") { ?>
                  <p><a href="<?php echo $row['url_download'] ?>" target="_blank" class="btn btn-primary btn-lg btn-large">
                  <i class="fa fa-download"></i> Download file
                  </a></p>
                <?php } elseif ($row['type'] == "uploaded_file") { ?>
                  <p><a href="mooc/download/<?php echo $row['id'] ?>" class="btn btn-primary btn-lg btn-large">
                  <i class="fa fa-download"></i> Download file
                  </a></p>
                <?php } ?>
                <?php if (($count-1) != count($media)) { ?>
                  <p class="pull-right"><a data-id="<?php echo $count ?>" href="#" class="nextvideo btn btn-primary">Next <i class="fa fa-arrow-right"></i></a></p>
                <?php } ?>
                  
            </div>
          <?php } ?>
          </div>
        <?php } ?>

      <?php } else { ?>
        <h1><?php echo $info->name ?></h1>
        <?php echo $info->description ?>
      <?php } ?>

    </div>

  </div>

<?php require_once 'include.footer.php'; ?>

<script type="text/javascript">
$(function(){
  $(".nextvideo").click(function(e) {
    e.preventDefault();
    var id = $(this).data("id");
    $("#item-"+id).click();
  });
  $(".nav-tabs li a").click(function(e){
    e.preventDefault();
  });
  $(".nav-tabs li").click(function(e){
    e.preventDefault();
    var id = $(this).data("id");
    $(".nav-tabs li").removeClass("active");
    $(this).addClass("active");
    $(".tab-pane").hide();
    $("#tab-"+id).show();
  });
});
</script>

  </body>
</html> 
