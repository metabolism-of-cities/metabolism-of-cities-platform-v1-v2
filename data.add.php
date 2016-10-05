<?php
require_once 'functions.php';
$section = 5;
$page = 4;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Data | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Add Data</h1>

  <p>You can help us to set up this global urban metabolism dataset! There are two ways
  to collaborate:</p>

  <h2>Authors</h2>

  <p>If you have undertaken a study that is published in one way or another (this could be in
  an academic journal, thesis, report, etc.) and your study includes a dataset, then we would 
  love to add the numbers to our database! You can do this yourself by following these steps:
  </p>

  <ul>
    <li>First, make sure that your publication exists in our database. Have a look <a href="publications">here</a>
    and make sure your information is there. If it isn't, then you can <a href="publications/add">add the publication yourself</a>.
    </li>
    <li>Once your publication exist, log in as an author to our author section. Each author can log in to this 
    section by following a link that is sent via e-mail. If you haven't received this e-mail or you 
    only added your first publication recently, then go to our <a href="people">database with authors</a>. There, 
    click on your own profile and use the link at the bottom of the page to get access to your profile.
    </li>
    <li>In your author profile there is a section where you can manage your publications as well as your <strong>Datasets</strong>.
    Click on this link in the menu and you can start uploading your data right away!</li>
  </ul>

  <h2>Volunteers</h2>
 
  <p>You do not need to have published your own dataset in order to contribute. We also need volunteers who
  can help with uploading data from existing publications. With our own group of volunteers we are currently
  going through publications one by one and we enter the data in our database. We can use all the help we can
  get. Are you interested? 
  </p>
  <p><a href="page/contact" class="btn btn-primary">Get in touch now!</a></p>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
