<?php
require_once 'functions.php';
$section = 5;
$show_breadcrumbs = true;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Submit a Reference | <?php echo SITENAME ?></title>
    <style type="text/css">
    .option-message{display:none}
    label{font-weight:bold;margin-top:30px}
    textarea.form-control{height:200px}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

    <h1>Submit a Reference</h1>

    <?php if ($_POST) { ?>

    <div class="alert alert-success">
      Thank you for suggesting a reference to include in the EPR Reference Database.
      The EPR Reference Database team reserves the right to include or exclude suggested
      references in the database based on its suitability. If you have questions, you can 
      send us an e-mail through the <a href="page/contact">Contact Us</a> link.
    </div>

    <?php } else { ?>

    <form method="post" class="form form-horizontal">
    
      <div class="form-group">
        <label>Your Name</label>
        <input class="form-control" type="text" name="name" required />
      </div>

      <div class="form-group">
        <label>E-mail</label>
          <input class="form-control" type="email" name="email" required />
      </div>

      <div class="form-group">
        <label>URL of Reference</label>
        <input class="form-control" type="url" name="url" />
        <p>If the reference is available online, copy and paste the URL here. This link will be made available to our visitors</p>
      </div>

      <div class="form-group">
        <label>How do you want to send us the reference?</label>
          <select name="how" class="form-control">
            <option value="upload">Upload now</option>
            <option value="email">By e-mail</option>
            <option value="mail">By postal mail</option>
            <option value="other">Other</option>
          </select>
      </div>

      <div class="alert alert-info option-message option-email">
        We will send you a message with the email addresses
        where you can send the document.
      </div>

      <div class="alert alert-info option-message option-mail">
        We will send you a message with the postal addresses
        where you can send the document.
      </div>

      <div class="alert alert-info option-message option-other">
        Please indicate in the message field how you would like to send the document
      </div>

      <div class="form-group file">
        <label>File</label>
        <input class="form-control" type="file" name="file" />
      </div>

      <div class="form-group">
        <label>Message</label>
        <textarea class="form-control" name="message" placeholder="You can include additional details in your message but this is not required"></textarea>
      </div>

      <div class="form-group">
          <button type="submit" class="btn btn-primary">Submit</button>
      </div>

    </form>

    <?php } ?>


<?php require_once 'include.footer.php'; ?>
<script type="text/javascript">
$(function(){
  $("select[name='how']").change(function(){
    var option = $(this).val();
    if (option == 'upload') {
      $(".file").show();
      $(".option-message").hide();
    } else {
      $(".file").hide();
      $(".option-message").hide();
      $(".option-"+option).show();
    }
  });
});
</script>

  </body>
</html>
