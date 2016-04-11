<?php
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Collaborators | <?php echo SITENAME ?></title>
    <style type="text/css">
    ol img{display:block;margin:10px 0;border:1px solid #ccc; padding:4px;}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Collaborators</h1>

  <p>
    Thanks for helping out improving and expanding the Metabolism of Cities website! We are always
    glad to have new collaborators and have made this guide to help you understand some of the main
    tasks. 
  </p>

  <h2>Managing publications</h2>

  <p>
    The <a href="publications/list">publications database</a> is one of the core sections of our website.
    Many other sections are linked to this and we consider it of great importance to keep our publication database
    up-to-date and well-managed. Helping us with this task is often a good way of getting to know the ins and outs
    of our website, so we will often initially task new collaborators with the job of helping us add new publications.
  </p>

  <p>Here is a quick overview of the steps that are involved in adding new papers:</p>

  <ol>
    <li>Add the publication through <a href="publications/add">this form</a>. If the publication in question was added by a visitor, 
    then you can skip this step and you should instead click on the link in the confirmation e-mail to open the publication.
    <br />
    <strong>Tip</strong>: use the Bibtex code (you can copy this from Google scholar or from journal websites) so you don't have to 
    manually fill out all fields in the form.
    </li>
    <li>
      Assuming you are logged in as an admin, you can click the link to activate/edit the paper:
      <img src="img/collaborate.edit.png" alt="Button to edit the publication" />
    <li>Activate the corresponding tags. It is important that you select the appropriate tags for this publication. Have a look
    at other publications to get an idea of how we tag papers. Furthermore, it is important you understand how the tagging
    hierarchy is set up and what each tag means. If in doubt, ask one of the other editors rather than just activing the tag.     
    You can add new tags at the bottom of the page but should only do so for very obvious tags (like countries or cities 
    that are missing). Don't invent
    a new category without discussing it first.
    Note: if you incorrectly marked a tag, you should reload the page and click the tag again to deactivate it. 
    <img src="img/collaborate.tags.png" alt="List of tags to click on" />
    </li>
    <li>The website can take care of linking the publication to our <a href="people">author database</a>. Click the blue button
    that says Classify Authors under the author list and each author will be located and linked. However, it is important to 
    note that this system is not perfect given the inconsistency in formatting the list of authors (and even spelling of author
    names). You should therefore review the linked authors to make sure all existing authors were linked (if the system does not
    recognize a name, it will create a new author profile). You should also make sure all authors are listed. Author names should 
    be separated either by the word 'and' or by a comma, but this format should not be mixed. If the authors are not properly listed,
    then click the Edit button and review the format. You can change it and click the Classify Authors button again until it is fixed.
    If two authors share the last name, then it is important that you spell out the full first name in the author list (to match the
    first name in the author's database). That way the author will be associated correctly.
    <br />
    <strong>Important</strong>: if an author was not recogized properly and a new author profile was created by the system, then you
    should take care to <strong>remove</strong> this 'empty' profile after you have matched the author with the right profile after
    reclassification. To delete a profile, go to the Contacts page in the admin section and click the Delete button.
    <img src="img/collaborate.classify.png" alt="Classify authors" />
    </li>
    <li>If you have marked all tags, classified all the authors, and you feel everything is ready, then it is time to 
    activate the paper. Before doing so, click on 'View as user' to have a look at how it will look like for a regular
    website visitor. If all looks good, click the Activate button and the publication will now be online!</li>
  </ol>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
