<?php
require_once 'functions.cms.php';
require_once 'functions.php';
require_once 'functions.omat.php';
$sub_page = 7;

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $check = $db->record("SELECT id FROM people_papers WHERE people = $delete");
  if ($check->id) {
    die("Sorry, you can not delete an author with publications");
  }
  $db->query("UPDATE people SET active = 0 WHERE id = $delete");
  $print = "Contact deactivated";
}

if ($_GET['mail']) {
  $sql = "AND email != ''";
}

if ($_GET['loggedin']) {
  $sql = "AND EXISTS(SELECT * FROM people_log JOIN people_access ON people_log.people = people_access.id 
  WHERE people_access.people = people.id AND people_log.action = 'User logged in to dashboard')";
}

if ($_GET['notloggedin']) {
  $sql = "AND NOT EXISTS(SELECT * FROM people_log JOIN people_access ON people_log.people = people_access.id 
  WHERE people_access.people = people.id AND people_log.action = 'User logged in to dashboard')";
}

if ($_GET['interacted']) {
  $sql = "AND EXISTS(SELECT * FROM people_log JOIN people_access ON people_log.people = people_access.id 
  WHERE people_access.people = people.id AND people_log.action != 'User logged in to dashboard')";
}

if ($_GET['notinteracted']) {
  $sql = "AND EXISTS(SELECT * FROM people_log JOIN people_access ON people_log.people = people_access.id 
  WHERE people_access.people = people.id AND people_log.action = 'User logged in to dashboard')
  AND NOT EXISTS(SELECT * FROM people_log JOIN people_access ON people_log.people = people_access.id 
  WHERE people_access.people = people.id AND people_log.action != 'User logged in to dashboard')
  ";
}

$authors = $db->query("SELECT *,
  (SELECT COUNT(*) FROM people_mails WHERE people_mails.people = people.id) AS total
FROM people WHERE active IS TRUE $sql ORDER BY firstname, lastname");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Contacts | <?php echo SITENAME ?></title>
  </head>

  <body class="notranslate">

<?php require_once 'include.header.php'; ?>

  <h1>Contacts</h1>

  <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

  <p>
    <a class="btn<?php if ($_GET['loggedin']) { echo ' btn-primary'; } ?>" href="cms/peoplelist/loggedin">Logged in</a>
    <a class="btn<?php if ($_GET['notloggedin']) { echo ' btn-primary'; } ?>" href="cms/peoplelist/notloggedin">Didn't log in</a>
    <a class="btn<?php if ($_GET['interacted']) { echo ' btn-primary'; } ?>" href="cms/peoplelist/interacted">Interacted</a>
    <a class="btn<?php if ($_GET['notinteracted']) { echo ' btn-primary'; } ?>" href="cms/peoplelist/notinteracted">Didn't interact</a>
  </p>

  <div class="alert alert-info">
    <strong><?php echo count($authors) ?></strong> contacts found. 
    <?php if ($_GET['mail']) { ?>
      <a href="cms/peoplelist">Show all</a>
    <?php } else { ?>
      <a href="cms.peoplelist.php?mail=true">Show mailing list</a>
    <?php } ?>
  </div>

  <?php if ($_GET['interacted']) { ?>
    <div class="alert alert-warning">
      <strong>Interacted</strong> means that these people did more than just log in. For instance, they could have updated
      their profile or added/updated a publication.
    </div>
  <?php } elseif ($_GET['notinteracted']) { ?>
    <div class="alert alert-warning">
      <strong>Didn't interact</strong> means that these people logged in, but did nothing more than that.
    </div>
  <?php } ?>

  <table class="table table-striped">
    <tr>
      <th>Mail</th>
      <th>Name</th>
      <th>Edit</th>
      <th>Delete</th>
      <th>Google</th>
      <th>Mails sent</th>
    </tr>
    <?php foreach ($authors as $row) { ?>
    <tr>
      <td>
        <?php if ($_GET['mail']) { ?>
          <?php echo $row['email'] ?>
        <?php } else { ?>
          <?php if ($row['email']) { ?>
            <i class="fa fa-check"></i>
          <?php } ?>
        <?php } ?>
      </td>
      <td><a href="people/<?php echo $row['id'] ?>-<?php echo flatten($row['firstname'] . "-" . $row['lastname']) ?>"><?php echo $row['firstname'] ?> <?php echo $row['lastname'] ?></a></td>
      <td><a href="cms/people/<?php echo $row['id'] ?>" class="btn btn-info">Edit</a></td>
      <td><a href="cms.peoplelist.php?delete=<?php echo $row['id'] ?>" class="btn btn-danger" onclick="javascript:return confirm('Are you sure?')">Delete</a></td>
      <td>
        <a target="_blank" href="https://www.google.co.za/#q=<?php echo urlencode($row['firstname'] ." " . $row['lastname']) ?>+email">Search</a> | 
        <a target="_blank" href="https://www.google.co.za/#q=<?php echo urlencode($row['firstname'] ." " . $row['lastname']) ?>+urban+metabolism+email">Search +um</a>
      </td>
      <td>
      <a href="cms/mailssent/<?php echo $row['id'] ?>">
        <?php echo $row['total'] ?>
      </a>
      | <a href="cms/mail/<?php echo $row['id'] ?>">preview</a>
      | <a href="cms/mail/<?php echo $row['id'] ?>/send?mail=true">send</a>
      
      </td>
    </tr>
  <?php } ?>
  </table>

  <p><a href="cms/author" class="btn btn-primary">Add contact</a></p>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
