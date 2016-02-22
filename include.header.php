<?php if (PRODUCTION) { ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-53731925-1', 'auto');
  ga('send', 'pageview');

</script>
<?php } ?>
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="./">
            <img src="img/logo.svg" alt="" />
            <?php echo SITENAME ?>
          </a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
          <?php foreach ($menu as $key => $value) { ?>
            <?php if (!is_array($value['menu'])) { ?>
              <li<?php if ($section == $key) { echo ' class="active"'; } ?>><a href="<?php echo $value['url'] ?>"><?php echo $value['label'] ?></a></li>
            <?php } else { ?>
            <li class="dropdown<?php if ($section == $key) { echo ' active'; } ?>">
              <a href="<?php echo $value['url'] ?>" class="dropdown-toggle" data-toggle="dropdown">
              <?php echo $value['label'] ?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
              <?php foreach ($value['menu'] as $subkey => $value) { ?>
                <li<?php if ($page == $subkey && $section == $key) { echo ' class="active"'; } ?>><a href="<?php echo $value['url'] ?>"><?php echo $value['label'] ?></a></li>
              <?php } ?>
              </ul>
            <?php } ?>
          <?php } ?>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <?php if (!$_SESSION['user_id']) { ?>
              <li><a href="page/login">Login</a></li>
            <?php } else { ?>
              <li><a href="login.php?logout">Logout</a></li>
            <?php } ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">

    <?php if (!$hide_regular_translate) { ?>
      <div id="google_translate_element"></div>
    <?php } ?>

    <?php if ($omat_sidebar) { require_once 'include.omatheader.php'; } ?>

    <?php if ($profile_sidebar) { require_once 'include.profileheader.php'; } ?>
