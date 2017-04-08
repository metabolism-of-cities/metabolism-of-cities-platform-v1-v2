<?php
$show_breadcrumbs = true;
require_once 'functions.php';
$section = 2;
$page = 5;

$title = "Contact Us";
$publication = (int)$_GET['publication'];
if ($publication) {
  $info = $db->record("SELECT * FROM papers WHERE id = $publication AND status = 'active'");
  if ($info->id) {
    $title = "Report incorrect information";
    $section = 4;
    $page = 4;
    $this_page = $title;
    $add_page_to_breadcrumbs = '<li><a href="publication/' . $publication . '">Publication #' . $publication . '</a></li>';
  } else {
    $publication = false;
  }
}

if ($_POST) {
  $message = 
"Name: " . mail_clean($_POST['name']) . "
E-mail: " . mail_clean($_POST['email']) . "
Date: " . date("r") . "
IP: " . $_SERVER["REMOTE_ADDR"] . "

-----------------------------------------
                MESSAGE
-----------------------------------------
" . mail_clean($_POST['message'], "box");

if ($publication) {
  $message .= "
-----------------------------------------
This relates to the following publication:
{$info->title}
" . URL . "publication/$publication";
}

  mailadmins($message, "Contact form Metabolism of Cities website", $_POST['email']);
}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo $title ?> | <?php echo SITENAME ?></title>
    <style type="text/css">
    textarea.form-control{height:300px}
    .alert a{font-weight:700}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

      <div class="jumbotron">

        <?php if ($_POST) { ?>

          <p>Thanks, we have received your message!</p>
          <p><a href="./" class="btn btn-primary btn-lg">Back to the homepage</a></p>

        <?php } else { ?>
        <h1><?php echo $title ?></h1>

        <div class="alert alert-info">
        <?php if ($publication) { ?>
          Fill out the form below to report incomplete or missing information for the following 
          publication: 
          <a href="publication/<?php echo $publication ?>"><?php echo $info->title ?></a>
        <?php } else { ?>
          You can contact us at <a href="mailto:<?php echo EMAIL ?>"><?php echo EMAIL ?></a>, 
          or by filling out the form below.
        <?php } ?>
        </div>

        
        <form method="post" class="form form-horizontal">

          <div class="form-group">
            <label class="col-sm-2 control-label">Your Name</label>
            <div class="col-sm-10">
              <input class="form-control" type="text" name="name" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label">E-mail</label>
            <div class="col-sm-10">
              <input class="form-control" type="email" name="email" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label">Comments</label>
            <div class="col-sm-10">
              <textarea class="form-control" name="message"></textarea>
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-primary">Send Message</button>
            </div>
          </div>

        </form>

        <?php } ?>

      </div>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
