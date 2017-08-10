<?php
require_once 'functions.php';

$section = 1;
$page = 1;

$papers = $db->record("SELECT SQL_CACHE COUNT(*) AS total FROM papers WHERE status = 'active'");
$collections = $db->record("SELECT SQL_CACHE COUNT(*) AS total FROM tags_parents");
$tags = $db->record("SELECT SQL_CACHE COUNT(*) AS total FROM tags");
$tagsused = $db->record("SELECT SQL_CACHE COUNT(*) AS total FROM tags_papers");
$projects = $db->record("SELECT SQL_CACHE COUNT(*) AS total FROM research WHERE deleted_on IS NULL");

$hide_regular_translate = true;

$today = date("Y-m-d");

$blog = $db->record("SELECT * FROM content WHERE active = 1 AND type = 'blog' AND date <= '$today' ORDER BY date DESC LIMIT 1");

if (ID == 2) {
  $info = $db->record("SELECT * FROM content WHERE id = 1");
}
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
    .movedown {
        margin-top:20px;
    }
    .owl-dots-center {
      margin-top:30px;
    }
    .owl-dots-center img {
      padding:4px;
      border:1px solid #ccc;
    }
    </style>

    <script src="assets/js/script.min.js"></script>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>


</div>


<?php if (ID == 2) { ?>
<div class="container movedown">
  <div class="row">
    <div class="col-7">
      <?php echo $info->content ?>
    </div>
  <div class="col-5">
    <div class="owl-dots-center owl-nav-over owl-nav-over-lg owl-nav-over-hover" data-toggle="owl-carousel" 
      data-owl-carousel-settings='{"items":1, "center":true, "autoplay":true, "loop":true, "dots":true, "nav":true, "animateOut":"fadeOutDown"}'>
        
        <div class="item">
          <img src="img/slider/2/1.jpg" alt="" class="img-fluid" />
        </div>
        <div class="item">
          <img src="img/slider/2/2.jpg" alt="" class="img-fluid" />
        </div>
        <div class="item">
          <img src="img/slider/2/3.jpg" alt="" class="img-fluid" />
        </div>
        <div class="item">
          <img src="img/slider/2/4.jpg" alt="" class="img-fluid" />
        </div>

      </div>
    </div>
  </div>
</div>

            
<?php } ?>
    
    <?php if (ID == 1) { ?>

 <div id="highlighted">
      <!-- Showshow - Slider Revolution see: plugins/slider-revolution/examples&sources for help invoke using data-toggle="slider-rev" options can be passed to the slider via HTML5 data- ie. data-startwidth="960" -->
      <div class="slider-wrapper rev_slider_wrapper fullscreen-container bg-black" data-page-class="slider-appstrap-theme">
        <div class="rev_slider fullscreenbanner" data-toggle="slider-rev" data-settings='{"startwidth":1100, "startheight":520, "delay":10000}'>
          <ul>
            <!-- SLIDE 1 -->
            <li class="slide overlay overlay-op-4 overlay-gradient" data-transition="fadethroughdark" data-slotamount="7" data-hideafterloop="0" data-hideslideonmobile="off" data-easein="default" data-easeout="default" data-masterspeed="default" data-thumb="img/slider/1/1.jpg" data-rotate="0" data-saveperformance="off" data-title="Slide" data-link="shop-list.htm">
              <img src="img/slider/1/1.jpg" data-lazyload="img/slider/1/1.jpg" alt="background image" data-bgposition="center top" data-bgfit="cover" data-bgparallax="off" class="rev-slidebg" data-no-retina />
              <!-- SLIDE 1 Content-->
              <div class="slide-content container" style="z-index: 10;">
                <!--elements within .slide-content are pushed below navbar on "behind"-->
                <!-- Layer 1 -->
                <div class="tp-caption text-grey" data-x="['left','left','left','left']" data-hoffset="['0','0','0','40']" data-y="['bottom','bottom','bottom','bottom']" data-voffset="['120','120','120','120']" data-fontsize="['52','52','52','52']" data-lineheight="['52','52','52','52']" data-width="420" data-height="none" data-whitespace="normal" data-type="text" data-basealign="slide" data-responsive_offset="on" data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1200,"to":"o:1;","delay":1300,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"opacity:0;","ease":"nothing"}]' data-textAlign="left"> <span class="font-weight-bold text-uppercase text-white">Welcome to MoC</span>
</div>
                <!-- Layer 3 -->
                <div class="tp-caption text-grey" data-x="['left','left','left','left']" data-hoffset="['0','0','0','40']" data-y="['bottom','bottom','bottom','bottom']" data-voffset="['60','60','60','60']" data-fontsize="['20','20','20','30']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-basealign="slide" data-responsive_offset="on" data-frames='[{"delay":0,"speed":1200,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","delay":1400},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]' data-textAlign="['center','center','center','center']"> An online hub for urban metabolism information</div>
                <!-- Layer 3 -->
                <div class="tp-caption rs-parallaxlevel-8" data-frames='[{"from":"o:0;sX:3;sY:3;","speed":2000,"to":"o:0.20;sX:3;sY:3;","delay":1800,"ease":"default"},{"delay":"wait","speed":1200,"to":"x:[100%];","ease":"Power3.easeInOut"}]' data-x="left" data-y="bottom" data-hoffset="-200" data-voffset="0" data-width="none" data-height="none" data-type="image" data-basealign="slide"> <i class="fa fa-bullseye icon-20x icon-bg op-1 text-white"></i>
</div>
                <!-- Layer 4 -->
                <div class="tp-caption tp-resizeme bg-primary-bright" data-frames='[{"from":"x:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":500,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"x:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]' data-x="['left','left','left','left']" data-hoffset="['0','0','0','40']" data-y="bottom" data-voffset="100" data-width="380" data-height="4" data-basealign="slide"></div>
              </div>
            </li>
            <!-- SLIDE 2 -->
            <li class="slide" data-transition="fade" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-easein="default" data-easeout="default" data-rotate="0" data-saveperformance="off" data-title="Slide">
              <img src="img/slider/1/2.jpg" class="rev-slidebg bg-white" alt="Background image" data-bgcolor="#ffffff" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="off" data-no-retina />
              <!-- LAYERS -->
              <!-- Layer 2 -->
              <div class="tp-caption tp-resizeme rs-parallaxlevel-9" data-x="['right','right','right','right']" data-hoffset="['-100','-100','-120','-144']" data-y="['top','top','top','top']" data-voffset="['-254','-254','-254','-254']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="image" data-basealign="slide" data-responsive_offset="on" data-frames='[{"from":"x:right;y:-500px;rZ:90deg;","speed":2500,"to":"o:1;","delay":500,"ease":"Power4.easeOut"},{"delay":"wait","speed":300,"to":"opacity:0;","ease":"nothing"}]' data-textAlign="['left','left','left','left']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 6;border-width:0px;">
              </div>
              <!-- Layer 3 -->
              <div class="slide-content">
                <!-- Layer 8 -->
                <div class="tp-caption tp-resizeme rs-parallaxlevel-2" data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" data-y="['middle','middle','middle','middle']" data-voffset="['-70','-70','-70','-90']" data-fontsize="['60','60','60','60']" data-lineheight="['60','60','60','60']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"from":"y:-50px;rX:-45deg;sX:2;sY:2;opacity:0;","speed":1500,"to":"o:1;","delay":510,"ease":"Power4.easeOut"},{"delay":640,"speed":600,"to":"y:30px;rX:45deg;sX:0.8;sY:0.8;opacity:0;","ease":"Power2.easeInOut"}]' data-textAlign="['center','center','center','center']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]">Flexbox Support.</div>
                <!-- Layer 9 -->
                <div class="tp-caption tp-resizeme rs-parallaxlevel-2" data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" data-y="['middle','middle','middle','middle']" data-voffset="['-70','-70','-70','-90']" data-fontsize="['60','60','60','60']" data-lineheight="['60','60','60','60']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"from":"y:-50px;rX:-45deg;sX:2;sY:2;opacity:0;","speed":1500,"to":"o:1;","delay":2940,"ease":"Power4.easeOut"},{"delay":660,"speed":600,"to":"y:30px;rX:45deg;sX:0.8;sY:0.8;opacity:0;","ease":"Power2.easeInOut"}]' data-textAlign="['center','center','center','center']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]">Bootstrap 4 Based.</div>
                <!-- Layer 10 -->
                <div class="tp-caption tp-resizeme rs-parallaxlevel-2" data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" data-y="['middle','middle','middle','middle']" data-voffset="['-70','-70','-70','-90']" data-fontsize="['60','60','60','60']" data-lineheight="['60','60','60','60']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"from":"y:-50px;rX:-45deg;sX:2;sY:2;opacity:0;","speed":1500,"to":"o:1;","delay":5390,"ease":"Power4.easeOut"},{"delay":"690","speed":600,"to":"y:30px;rX:45deg;sX:0.8;sY:0.8;opacity:0;","ease":"Power2.easeInOut"}]' data-textAlign="['center','center','center','center']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]">Fully Responsive.</div>
                <!-- Layer 11 -->
                <div class="tp-caption tp-resizeme rs-parallaxlevel-2" data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" data-y="['middle','middle','middle','middle']" data-voffset="['-70','-70','-70','-90']" data-fontsize="['60','60','60','60']" data-lineheight="['60','60','60','60']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"from":"y:-50px;rX:-45deg;sX:2;sY:2;opacity:0;","speed":1500,"to":"o:1;","delay":7890,"ease":"Power4.easeOut"},{"delay":"wait","speed":600,"to":"y:30px;rX:45deg;sX:0.8;sY:0.8;opacity:0;","ease":"Power2.easeInOut"}]' data-textAlign="['center','center','center','center']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]">AppStrap.</div>
                <!-- Layer 12 -->
                <div class="tp-caption tp-resizeme rs-parallaxlevel-2" data-x="['center','center','center','center']" data-hoffset="['-8','-8','-8','-8']" data-y="['middle','middle','middle','middle']" data-voffset="['-10','-10','-10','-10']" data-fontsize="['20','20','20','25']" data-lineheight="['20','20','20','30']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"from":"y:50px;rX:45deg;sX:2;sY:2;opacity:0;","speed":1500,"to":"o:1;","delay":600,"ease":"Power4.easeOut"},{"delay":"wait","speed":300,"to":"opacity:0;","ease":"nothing"}]' data-textAlign="['center','center','center','center']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]">The ultimate Bootstrap 4 Theme!</div>
                <!-- Layer 13 -->
                <div class="tp-caption rs-parallaxlevel-3" data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" data-y="['middle','middle','middle','middle']" data-voffset="['92','92','92','92']" data-width="none" data-height="none" data-whitespace="nowrap" data-responsive_offset="on" data-responsive="off" data-frames='[{"from":"y:100px;rX:90deg;opacity:0;","speed":1500,"to":"o:1;","delay":700,"ease":"Power4.easeOut"},{"delay":"wait","speed":300,"to":"opacity:0;","ease":"nothing"}]'> <a href="https://wrapbootstrap.com/theme/appstrap-responsive-website-template-WB0C6D0H4?ref=themelizeme" class="btn btn-outline-primary btn-rounded btn-xlg hidden-sm-down">Read more</a> <a href="https://wrapbootstrap.com/theme/appstrap-responsive-website-template-WB0C6D0H4?ref=themelizeme" class="btn btn-outline-primary btn-rounded btn-sm hidden-md-up">Get AppStrap</a>
</div>
              </div>
            </li><li class="slide" data-transition="fade" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-easein="default" data-easeout="default" data-rotate="0" data-saveperformance="off" data-title="Slide">
              <img src="img/slider/1/3.jpg" class="rev-slidebg bg-white" alt="Background image" data-bgcolor="#ffffff" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="off" data-no-retina />
              <!-- LAYERS -->
              <!-- Layer 2 -->
              <div class="tp-caption tp-resizeme rs-parallaxlevel-9" data-x="['right','right','right','right']" data-hoffset="['-100','-100','-120','-144']" data-y="['top','top','top','top']" data-voffset="['-254','-254','-254','-254']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="image" data-basealign="slide" data-responsive_offset="on" data-frames='[{"from":"x:right;y:-500px;rZ:90deg;","speed":2500,"to":"o:1;","delay":500,"ease":"Power4.easeOut"},{"delay":"wait","speed":300,"to":"opacity:0;","ease":"nothing"}]' data-textAlign="['left','left','left','left']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 6;border-width:0px;">
              </div>
              <!-- Layer 3 -->
              <div class="slide-content">
                <!-- Layer 8 -->
                <div class="tp-caption tp-resizeme rs-parallaxlevel-2" data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" data-y="['middle','middle','middle','middle']" data-voffset="['-70','-70','-70','-90']" data-fontsize="['60','60','60','60']" data-lineheight="['60','60','60','60']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"from":"y:-50px;rX:-45deg;sX:2;sY:2;opacity:0;","speed":1500,"to":"o:1;","delay":510,"ease":"Power4.easeOut"},{"delay":640,"speed":600,"to":"y:30px;rX:45deg;sX:0.8;sY:0.8;opacity:0;","ease":"Power2.easeInOut"}]' data-textAlign="['center','center','center','center']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]">Flexbox Support.</div>
                <!-- Layer 9 -->
                <div class="tp-caption tp-resizeme rs-parallaxlevel-2" data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" data-y="['middle','middle','middle','middle']" data-voffset="['-70','-70','-70','-90']" data-fontsize="['60','60','60','60']" data-lineheight="['60','60','60','60']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"from":"y:-50px;rX:-45deg;sX:2;sY:2;opacity:0;","speed":1500,"to":"o:1;","delay":2940,"ease":"Power4.easeOut"},{"delay":660,"speed":600,"to":"y:30px;rX:45deg;sX:0.8;sY:0.8;opacity:0;","ease":"Power2.easeInOut"}]' data-textAlign="['center','center','center','center']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]">Bootstrap 4 Based.</div>
                <!-- Layer 10 -->
                <div class="tp-caption tp-resizeme rs-parallaxlevel-2" data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" data-y="['middle','middle','middle','middle']" data-voffset="['-70','-70','-70','-90']" data-fontsize="['60','60','60','60']" data-lineheight="['60','60','60','60']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"from":"y:-50px;rX:-45deg;sX:2;sY:2;opacity:0;","speed":1500,"to":"o:1;","delay":5390,"ease":"Power4.easeOut"},{"delay":"690","speed":600,"to":"y:30px;rX:45deg;sX:0.8;sY:0.8;opacity:0;","ease":"Power2.easeInOut"}]' data-textAlign="['center','center','center','center']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]">Fully Responsive.</div>
                <!-- Layer 11 -->
                <div class="tp-caption tp-resizeme rs-parallaxlevel-2" data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" data-y="['middle','middle','middle','middle']" data-voffset="['-70','-70','-70','-90']" data-fontsize="['60','60','60','60']" data-lineheight="['60','60','60','60']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"from":"y:-50px;rX:-45deg;sX:2;sY:2;opacity:0;","speed":1500,"to":"o:1;","delay":7890,"ease":"Power4.easeOut"},{"delay":"wait","speed":600,"to":"y:30px;rX:45deg;sX:0.8;sY:0.8;opacity:0;","ease":"Power2.easeInOut"}]' data-textAlign="['center','center','center','center']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]">AppStrap.</div>
                <!-- Layer 12 -->
                <div class="tp-caption tp-resizeme rs-parallaxlevel-2" data-x="['center','center','center','center']" data-hoffset="['-8','-8','-8','-8']" data-y="['middle','middle','middle','middle']" data-voffset="['-10','-10','-10','-10']" data-fontsize="['20','20','20','25']" data-lineheight="['20','20','20','30']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"from":"y:50px;rX:45deg;sX:2;sY:2;opacity:0;","speed":1500,"to":"o:1;","delay":600,"ease":"Power4.easeOut"},{"delay":"wait","speed":300,"to":"opacity:0;","ease":"nothing"}]' data-textAlign="['center','center','center','center']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]">The ultimate Bootstrap 4 Theme!</div>
                <!-- Layer 13 -->
                <div class="tp-caption rs-parallaxlevel-3" data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" data-y="['middle','middle','middle','middle']" data-voffset="['92','92','92','92']" data-width="none" data-height="none" data-whitespace="nowrap" data-responsive_offset="on" data-responsive="off" data-frames='[{"from":"y:100px;rX:90deg;opacity:0;","speed":1500,"to":"o:1;","delay":700,"ease":"Power4.easeOut"},{"delay":"wait","speed":300,"to":"opacity:0;","ease":"nothing"}]'> <a href="https://wrapbootstrap.com/theme/appstrap-responsive-website-template-WB0C6D0H4?ref=themelizeme" class="btn btn-outline-primary btn-rounded btn-xlg hidden-sm-down">Get AppStrap</a> <a href="https://wrapbootstrap.com/theme/appstrap-responsive-website-template-WB0C6D0H4?ref=themelizeme" class="btn btn-outline-primary btn-rounded btn-sm hidden-md-up">Get AppStrap</a>
</div>
              </div>
            </li>
            <!-- SLIDE 3 -->
          </ul>
          <div class="tp-bannertimer tp-bottom"></div>
        </div>
        <!--end of tp-banner-->
      </div>
    </div>


    <div id="mainsection" class="bg-white">

      <div class="container p-4 py-lg-6">
        <div class="row text-center">
          <div class="col-lg-3 py-2">
            <i class="fa fa-pencil icon-3x text-primary animated animated-done" data-animate="fadeIn" data-animate-delay="0.1" style="animation-delay: 0.1s;"></i> 
            <h4 class="mt-2">
              <a href="research/list">
              Research
              </a>
            </h4>
            <p>A list of academic research projects that people in the community are currently undertaking.</p>
          </div>
          <div class="col-lg-3 py-2">
            <i class="fa fa-database icon-3x text-primary animated animated-done" data-animate="fadeIn" data-animate-delay="0.2" style="animation-delay: 0.2s;"></i> 
            <h4 class="mt-2">
              <a href="data">
              Data
              </a>
            </h4>
            <p>We have developed a global urban metabolism dataset containing datapoints from all over the world.</p>
          </div>
          <div class="col-lg-3 py-2">
            <i class="fa fa-users icon-3x text-primary animated animated-done" data-animate="fadeIn" data-animate-delay="0.3" style="animation-delay: 0.3s;"></i> 
            <h4 class="mt-2">
              <a href="stakeholders">
              Stakeholders Initiative
              </a>
            </h4>
            <p>We set up events and activities around particular themes throughout the year. Check out our current and past initiatives.</p>
          </div>
          <div class="col-lg-3 py-2">
            <i class="fa fa-line-chart icon-3x text-primary animated animated-done" data-animate="fadeIn" data-animate-delay="0.4" style="animation-delay: 0.4s;"></i> 
            <h4 class="mt-2">
              <a href="omat">
              OMAT
              </a>
            </h4>
            <p>The Online Material Flow Analysis Tool allows is free, open-source online software to help you do an MFA!</p>
          </div>
        </div>
      </div>
    </div>

    <?php } ?>

    <?php if (!$_GET['hide_boxes']) { ?>
    <div id="publications" class="bg-grey">
    <?php if (ID == 1) { ?>
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
    <?php } ?>

      <div class="bg-<?php echo $color == 'green' ? 'green' : 'blue' ?> text-white p-3 p-lg-4 text-center divider-arrow divider-arrow-b divider-inverse">
        <div class="container">
          <h2 class="text-center text-uppercase font-weight-bold my-0">
            <?php echo ID == 1 ? "Publications" : "Overview" ?>
          </h2>
          <h5 class="text-center font-weight-light mt-2 mb-0 text-white op-5">
            We have indexed <strong><?php echo $papers->total ?></strong> publications and counting!
          </h5>
        </div>
      </div>
    <?php } ?>
      <div class="container p-4 py-lg-6">
        <div class="row text-center">
          <div class="col-lg-3 py-2">
            <i class="fa fa-list icon-3x text-primary animated animated-done" data-animate="fadeIn" data-animate-delay="0.1" style="animation-delay: 0.1s;"></i> 
            <h4 class="mt-2">
              <a href="publications/results">Database</a>
            </h4>
            <p>View our database with <?php echo $papers->total ?> journal articles, books, reports, and other literature.</p>
          </div>
          <div class="col-lg-3 py-2">
            <i class="fa fa-book icon-3x text-primary animated animated-done" data-animate="fadeIn" data-animate-delay="0.2" style="animation-delay: 0.2s;"></i> 
            <h4 class="mt-2">
              <a href="publications/collections">Collections</a>
            </h4>
            <p>Our collections include groups of publications by theme and can be easily browsed and filtered.</p>
          </div>
          <div class="col-lg-3 py-2">
            <i class="fa fa-search icon-3x text-primary animated animated-done" data-animate="fadeIn" data-animate-delay="0.3" style="animation-delay: 0.3s;"></i> 
            <h4 class="mt-2">
              <a href="people">Authors</a>
            </h4>
            <p>View the list of authors and search through or browse their profiles.</p>
          </div>
          <div class="col-lg-3 py-2">
            <i class="fa fa-plus icon-3x text-primary animated animated-done" data-animate="fadeIn" data-animate-delay="0.4" style="animation-delay: 0.4s;"></i> 
            <h4 class="mt-2">
              <a href="page/submit">
              Add
              </a>
            </h4>
            <p>Are we missing records from our database? Please submit any missing references here.</p>
          </div>
        </div>
      </div>
    </div>

    <?php require_once 'include.footer.php'; ?>

  </body>
</html>
