<?php
require_once 'functions.php';
$section = 7;
$page = 3;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Metabolism of Cities Stakeholder Initiative | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <ol class="breadcrumb">
    <li><a href="./">Home</a></li>
    <li><a href="stakeholders">Stakeholders Initiative</a></li>
    <li class="active">Subscribe</li>
  </ol>


  <h1>Metabolism of Cities Stakeholder Initiative</h1>

  <p> 
    We aim to bring together people who operate in the field of urban metabolism.
    Would you like to participate in future discussions, contribute to our forums,
    or help shape ideas around research and data? Register now to become part of
    the Stakeholders Initiative! We will add you to our mailing list and keep you
    informed of upcoming ideas and developments as our network grows.
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
