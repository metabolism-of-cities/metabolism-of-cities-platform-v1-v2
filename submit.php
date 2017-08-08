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
    label{font-weight:bold;}
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

    <div class="container">
    <form method="post" >
    
      <div class="form-group row">
        <label class="col-sm-2 col-form-label">Your Name</label>
        <div class="col-sm-10">
        <input class="form-control" type="text" name="name" required />
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-2 col-form-label">E-mail</label>
        <div class="col-sm-10">
        <input class="form-control" type="text" name="email" required />
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-2 col-form-label">URL of Reference</label>
        <div class="col-sm-10">
        <p>If the reference is available online, copy and paste the URL here. This link will be made available to our visitors</p>
        <input class="form-control" type="url" name="url" />
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-2 col-form-label">Format</label>
        <div class="col-sm-10">
          <p>How do you want to send us the reference?</p>
          <select name="how" class="form-control">
            <option value="upload">Upload now</option>
            <option value="email">By e-mail</option>
            <option value="mail">By postal mail</option>
            <option value="other">Other</option>
          </select>
        </div>
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


      <div class="form-group row file">
        <label class="col-sm-2 col-form-label">URL of Reference</label>
        <div class="col-sm-10">
        <input class="form-control" type="file" name="file" />
        </div>
      </div>


      <div class="form-group row file">
        <label class="col-sm-2 col-form-label">Message</label>
        <div class="col-sm-10">
        <textarea class="form-control" name="message" placeholder="You can include additional details in your message but this is not required"></textarea>
        </div>
      </div>



    <div class="form-group row">
      <div class="offset-sm-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>

    </form>
    </div>

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
