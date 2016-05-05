    <?php if ($omat_sidebar) { require_once 'include.omatfooter.php'; } ?>
    <?php if ($profile_sidebar) { require_once 'include.profilefooter.php'; } ?>
    <?php if ($cms_sidebar) { require_once 'include.profilefooter.php'; } ?>
    </div> <!-- /container -->

    <div class="footer">
      <div class="container">
        <p class="text-muted">
          <a href="page/contact"><i class="fa fa-envelope"></i></a>
          <a href="https://twitter.com/CityMetabolism"><i class="fa fa-twitter"></i></a>
          <a href="https://github.com/paulhoekman/mfa-tools"><i class="fa fa-github-square"></i></a>
          <a href="https://creativecommons.org/licenses/by/4.0/" class="pull-left">
          Creative Commons Attribution 4.0 International license.
          </a>
          <span class="right">
          Last update: <strong>May 2016</strong>
          </span>
        </p>
      </div>
    </div>

    <?php if (defined("ADMIN")) { ?>
      <a id="admin" class="btn btn-default" href="cms/index"><i class="fa fa-lock"></i> Admin</a>
    <?php } ?>

    <?php echo $google_translate ?>
