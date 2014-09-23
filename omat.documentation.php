<?php
require_once 'functions.php';
$section = 6;
$page = 5;

$id = (int)$_GET['id'];

$sections = array(
  1 => "Getting Started",
  2 => "Basic MFA",
  3 => "Advanced options",
  4 => "Collecting data",
);

foreach ($sections as $key => $value) {
  $content[$key] = @file_get_contents("documentation.$key.php");
}

$remove_enter = array("\n" => "");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Documentation | Online Material Flow Analysis Tool (OMAT) | <?php echo SITENAME ?></title>
    <link rel="stylesheet" href="css/sidebar.css" />
    <style type="text/css">
    .sidebar img {position:absolute;bottom:60px;left:70px}
    .right{float:right}
    section h1{font-size:1.4em}
    #documentation img{background:#ccc;padding:4px;border:3px solid #999}
    h2{font-size:24px;color:#333}
    .limitwidth{width:95%}
    </style>
    <script type="text/javascript">
    $(function(){
      $("a.scroll").click(function(event){
         event.preventDefault();
         //calculate destination place
         var dest=0;
         if($(this.hash).offset().top > $(document).height()-$(window).height()){
              dest=$(document).height()-$(window).height();
         }else{
              dest=$(this.hash).offset().top;
         }
         dest = dest-60;
         //go to destination
         $('html,body').animate({scrollTop:dest}, 1000,'swing');
       });     
    });
    </script>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
      <ul class="nav nav-sidebar">
        <?php foreach ($sections as $key => $value) { ?>
          <li<?php if ($id == $key) { ?> class="active"<?php } ?>>
            <a href="omat/documentation/<?php echo $key ?>"><?php echo $value ?></span>
            </a>
          </li>
        <?php } ?>
      </ul>
      <img src="img/globe.arrow.png" alt="" />
    </div>

    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

      <h1><i class="fa fa-book"></i> <?php echo $id ? $sections[$id] : 'Documentation OMAT'; ?></h1>

      <?php if ($id) { ?>
      <div id="documentation">
        <?php echo $content[$id] ?>
      </div>
      <?php } else { ?>
      <div class="row">

      <?php foreach ($sections as $key => $value) { ?>

        <div class="col-md-6">
          <section>
            <h1><a href="omat/documentation/<?php echo $key ?>"><?php echo $value ?></a></h1>
            <p>
              <?php echo truncate(strip_tags(strtr($content[$key], $remove_enter)), 200); ?>
            </p>
          </section>
        </div>

      <?php } ?>

      </div>

      <?php } ?>

    </div>

  </div>


<?php require_once 'include.footer.php'; ?>

  </body>
</html> 
