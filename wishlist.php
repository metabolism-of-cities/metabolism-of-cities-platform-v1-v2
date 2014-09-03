<?php
require_once 'functions.php';
$section = 2;
$page = 4;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Wish List | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <div class="jumbotron">
    <h1>Wish List</h1>
    <p>
      This page describes features, content, and other things that we would like to add to this website.
      Some of these things are included on our development roadmap and will likely be developed at some point.
      Other issues will require outside assistance from volunteers, and we hope to find people who can help us out.
      In either way, your support will be appreciated? Can you help?
    </p>
    <p>
      <a class="btn btn-lg btn-primary" href="page/contact" role="button">Let us know &raquo;</a>
      <a class="btn btn-lg btn-primary" href="https://github.com/paulhoekman/mfa-tools">View this project on github</a>
    </p>
  </div>

  <h2>Features</h2>

  <ul>
    <li>Option to download the whole database. This would allow users to do other things with the database, as they please.</li>
    <li>Option to download a dataset as a CSV file.</li>
    <li>Option to download the list of publications as a CSV file.</li>
    <li>A newsletter option that allows visitors to receive monthly updates of new publications or features added.</li>
    <li>Better implementation of uncertainty in the MFA software.</li>
    <li>Creation of beautiful graphs based on the dataset.</li>
    <li>A screen-recorded video that shows users how it all works by viewing a 2-3 minute video.</li>
    <li>Create a map to visually show where the studies have been done.</li>
    <li>Add a field to indicate the language of each publication (there are a few that are not in English).</li>
    <li>Send confirmations through e-mail when adding (or removing) research projects.</li>
  </ul>

  <h2>Content-related</h2>

  <ul>
    <li>We would like all of the publications to have an abstract available. For that to happen, we need to 
    obtain permission from authors or journals.</li>
    <li>More publications. There is much more out there! We are strong in specific areas but particularly lack the following:
      <ul>
        <li>More varied journals. There is a strong focus on a few journals, but there must be more out there. </li>
        <li>Theses and other institutional documents that are not found on journal websites.</li>
        <li>More manuals and handbooks, especially those not related to EUROSTAT-based EW-MFAs.</li>
        <li>More non-English publications.</li>
        <li>Datasets of past MFA studies. Preferably the original spreadsheets that were used, to get a better insight into how 
        things were done by the involved researchers. Several are available from journal sites and research institute websites.</li>
      </ul>
    </li>
    <li>A page for each of the journals that explains to users what the journal focuses on, what to except, how it relates to 
    others, etc.</li>
    <li>For each of the <a href="publications/collections">collections</a>, provide a more detailed overview of what kind of content
    can be found and what the collection means.</li>
    <li>There are some specific lists and websites that can provide many useful references. They are ready to be entered into the system!
    Two useful sources are:
    <ul>
      <li>
        List of papers related to STAN available on the <a
        href="http://stan2web.net/infos/publications">STAN website</a>. It would
        be very useful to create a STAN tag and add all of these publications to
        the database. 
      </li>
        <li>The list of <a href="https://sites.google.com/site/ensap758/tanikawa/paper">
        publications by Hiroki Tanikawa</a> contain many MFA-related resources! It would be an added bonus if someone who speaks Japanese could
        enter the Japanese publications (both in Japanese and English would be great!).</li>
    </ul>
  </li>
  </ul>

  <h2>Quality Control</h2>
  <ul>
    <li>Revision of the MFA software by an MFA expert.</li>
    <li>Revision of the tags and classification of publications by an expert.</li>
    <li>Testing of the MFA software by any type of user.</li>
  </ul>

  Can you help with any of these points? Do you have a feature you would like to request? <a href="page/contact">Let us know!</a>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
