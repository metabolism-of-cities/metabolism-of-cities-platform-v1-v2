<?php
require_once 'functions.php';
$section = 2;
$page = 6;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Data Visualizations | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>



  <h1>
    Data Visualizations in Urban Metabolism Research</h1>

    <p>
    There are many ways to visualize your results in urban metabolism research.
    However, it is a challenge to design a map or diagram that is clear and
    appealing at  the same time, and that captures the full extent of your
    dataset. Should you use sankey diagrams, maps, or other visual
    representations? What software to use? How to make it look professional
    without spending a lot of time on it? At the Metabolism of Cities website
    we want to enlist you help to answer those questions! We are setting up a
    Stakeholders Initiative and invite everyone to join the discussion. Data
    Visualizations will be our first topic of discussion, running from
    August-October 2016. In this period, we will publish blog posts (guest
    contributions are welcome), host online discussions, take stock of work in
    this field, and build or expand on open source software that can help
    develop data visualizations. 
    </p>

    <div id="mailchimp">
      <!-- Begin MailChimp Signup Form -->
      <link href="//cdn-images.mailchimp.com/embedcode/classic-081711.css" rel="stylesheet" type="text/css">
      <style type="text/css">
        #mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
        /* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
           We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
      </style>
      <div id="mc_embed_signup">
      <form action="//mfa-tools.us8.list-manage.com/subscribe/post?u=ac623181f98df6ff1b52a1668&amp;id=1b372b3ae4" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
        <h2>Subscribe to the Stakeholders Initiative</h2>
      <div class="mc-field-group">
        <label for="mce-EMAIL">Email Address </label>
        <input type="email" value="<?php echo strip_tags($_POST['email']) ?>" name="EMAIL" class="required email" id="mce-EMAIL">
      </div>
      <div class="mc-field-group">
        <label for="mce-FNAME">First Name </label>
        <input type="text" value="" name="FNAME" class="" id="mce-FNAME">
      </div>
      <div class="mc-field-group">
        <label for="mce-LNAME">Last Name </label>
        <input type="text" value="" name="LNAME" class="" id="mce-LNAME">
      </div>
        <div id="mce-responses" class="clear">
          <div class="response" id="mce-error-response" style="display:none"></div>
          <div class="response" id="mce-success-response" style="display:none"></div>
        </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
          <div style="position: absolute; left: -5000px;"><input type="text" name="b_ac623181f98df6ff1b52a1668_59a1b98376" tabindex="-1" value=""></div>
          <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
      </form>
      </div>
      <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
      <script type='text/javascript'>
      (function($) {
      window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';
      }(jQuery));
      var $mcj = jQuery.noConflict(true);
      </script>
      <!--End mc_embed_signup-->
      </div>




<?php require_once 'include.footer.php'; ?>

  </body>
</html>
