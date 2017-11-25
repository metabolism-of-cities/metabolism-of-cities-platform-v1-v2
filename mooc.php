<?php
$skip_login = true;
require_once 'functions.php';
require_once 'functions.omat.php';

$id = (int)$_GET['id'];
$id = 1;
$info = $db->record("SELECT * FROM mooc WHERE id = $id");
$list = $db->query("SELECT * FROM mooc_modules ORDER BY title");

if ($_GET['module']) {
  $module = (int)$_GET['module'];
  $module_info = $db->record("SELECT * FROM mooc_modules WHERE id = $module");
  $media = $db->query("SELECT * FROM mooc_media WHERE module = $module ORDER BY position");
} elseif ($_GET['overview']) {
  $overview = true;
  $modules = $db->query("SELECT * FROM mooc_modules WHERE mooc = 1 ORDER BY title");
  $media_list = $db->query("SELECT * FROM mooc_media ORDER BY position");
  foreach ($media_list as $row) { 
    $media[$row['module']][$row['id']] = $row;
  }
}
$sections = array(
  'mooc' => "Homepage",
  'overview' => "Overview",
);

foreach ($list as $row) { 
  $sections[$row['id']] = $row['title'];
}

if ($user_id) {
  $done_list = $db->query("SELECT * FROM mooc_progress WHERE user = $user_id");
  foreach ($done_list as $row) {
    $done[$row['media']] = true;
  }
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
    .green{color:green}
    .fadeout{opacity:0.4;color:#000;cursor:not-allowed}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
      <ul class="nav nav-sidebar">
        <?php foreach ($sections as $key => $value) { ?>
          <li<?php if ($module == $key || $key == 'mooc' && !$module && !$overview || $key == 'overview' && $overview) { ?> class="active"<?php } ?>>
            <?php if (!$user_id && $key != 8 && $key != "mooc") { ?>
            <a href="javascript:void()" class="fadeout"><?php echo $value ?></a>
            <?php } else { ?>
            <a href="mooc/<?php echo $key ?>" class="item-<?php echo $key ?>"><?php echo $value ?>
            </a>
            <?php } ?>
          </li>
        <?php } ?>
      </ul>
    </div>

    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

      <?php if ($overview) { ?>
        <h1>Overview</h1>
        <table class="table table-striped">
          <tr>
            <th>Title</th>
            <th>Duration (approx.)</th>
            <th>Status</th>
          </tr>
          <?php foreach ($modules as $row) { ?>
          <tr>
            <td colspan="3"><em><?php echo $row['title'] ?></em></td>
          </tr>
          <?php if (is_array($media[$row['id']])) { foreach ($media[$row['id']] as $row) { ?>
            <tr>
              <td><a href="mooc/<?php echo $row['module'] ?>"><?php echo $row['title'] ?></a></td>
              <td><?php echo $row['duration'] ?></td>
              <td><?php echo $done[$row['id']] ? "<strong class='green'>Completed</strong>" : "Pending" ?></td>
            </tr>
          <?php } } ?>
        <?php } ?>
        </table>
      <?php } elseif ($module) { ?>
        <h1><?php echo $module_info->title ?>
        <?php if (defined("ADMIN")) { ?>
          <a href="cms.module.php?id=<?php echo $id ?>" class="pull-right"><i class="fa fa-pencil"></i> </a> 
        <?php } ?>
        </h1>
        <?php echo $module_info->instructions ?>

        <?php if (count($media)) { ?>
          <ul class="nav nav-tabs">
          <?php 
          $count_type['video'] = 1;
          $count_type['file'] = 1;
          $count_type['text'] = 1;
          $count = 0; foreach ($media as $row) { $count++; 
          if ($row['type'] == 'youtube' || $row['type'] == 'vimeo') {
            $print_number = $count_type['video']++;
            $label = "Video ";
          } elseif ($row['type'] == 'text') {
            $print_number = $count_type['text']++;
            $label = "Text Box ";
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
                <?php if ($user_id && !$done[$row['id']]) { ?>
                <p class=""><a data-id="<?php echo $count ?>" data-media="<?php echo $row['id'] ?>" href="#" class="nextvideo completed btn btn-warning">
                <i class="fa fa-check"></i> 
                I have <?php echo $row['type'] == 'youtube' ? 'watched' : 'read' ?> this content</a></p>
                <?php } elseif ($done[$row['id']]) { ?>
                  <p><em>You have marked this as completed. Well done!</em></p>
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

        <div class="jumbotron">
          <h1>Sign up now</h1>
          <p>Our MOOC is free, and you can start learning about urban metabolism <em>right now!</em></p>
          <p><a href="mooc.register.php" class="btn btn-lg btn-primary">Register now</a></p>
        </div>
      <?php } ?>

    </div>

  </div>

<?php require_once 'include.footer.php'; ?>

<script type="text/javascript">
$(function(){
  $(".completed").click(function(e) {
    e.preventDefault();
    var button = $(this);
    $.post("ajax.media.php",{
      media: $(this).data("media"),
      dataType: "json"
    }, function(data) {
      if (data.response == "OK") {
        $(button).removeClass("btn-warning");
        $(button).addClass("btn-success");
      } else {
        $(button).removeClass("btn-warning");
        $(button).addClass("btn-danger");
        $(button).val("There was an error");
      }
    },'json')
    .error(function(){
        $(button).removeClass("btn-warning");
        $(button).addClass("btn-danger");
        $(button).val("There was an error sending data to the server");
    });
  });
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
