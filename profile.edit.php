<?php
require_once 'functions.php';
require_once 'functions.profile.php';
$sub_page = 5;
$info = $profile_info;

if ($_POST) {
  $post = array(
    'firstname' => html($_POST['firstname']),
    'lastname' => html($_POST['lastname']),
    'email' => html($_POST['email']),
    'email_public' => (int)$_POST['email_public'],
    'affiliation' => html($_POST['affiliation']),
    'city' => html($_POST['city']),
    'country' => html($_POST['country']),
    'profile' => html($_POST['profile']),
    'research_interests' => html($_POST['research_interests']),
    'url' => html($_POST['url']),
  );
  $db->update("people",$post,"id = $people_id");
  $print = "Thanks, your profile has been updated";
  
  require_once 'functions.mail.php';
  pearMail(EMAIL, "Profile updated", "The profile of {$info->firstname} {$info->lastname} was updated. Please visit [" . URL . "profile.php?id=$people_id this page] to review. 

  Technical details included below\n\n" . getinfo());
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Your Profile | <?php echo SITENAME ?></title>
    <style type="text/css">
    textarea.form-control{height:200px}
    </style>
  </head>

  <body class="profile">

<?php require_once 'include.header.php'; ?>

  <h1>Your Profile</h1>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } else { ?>

  <form method="post" class="form form-horizontal">
  
    <div class="form-group">
      <label class="col-sm-2 control-label">First Name</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="firstname" value="<?php echo $info->firstname ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Last Name</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="lastname" value="<?php echo $info->lastname ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">E-mail</label>
      <div class="col-sm-10">
        <input class="form-control" type="email" name="email" value="<?php echo $info->email ?: $info->access_email; ?>" />
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="email_public" value="1" <?php echo $info->email_public ? "checked" : ""; ?> />
            Show my e-mail address on the website
          </label>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Affiliation</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="affiliation" value="<?php echo $info->affiliation ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">City</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="city" value="<?php echo $info->city ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Country</label>
      <div class="col-sm-10">
        <input class="form-control" type="text" name="country" value="<?php echo $info->country ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Your Profile</label>
      <div class="col-sm-10">
        <textarea class="form-control" name="profile"><?php echo br2nl($info->profile) ?></textarea>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Research Interests</label>
      <div class="col-sm-10">
        <textarea class="form-control" name="research_interests"><?php echo br2nl($info->research_interests) ?></textarea>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Website</label>
      <div class="col-sm-10">
        <input class="form-control" type="url" name="url" value="<?php echo $info->url ?>" />
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>

  </form>

  <?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
