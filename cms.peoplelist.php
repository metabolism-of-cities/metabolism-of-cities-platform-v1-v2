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
  $sql = "WHERE email != ''";
}

$authors = $db->query("SELECT *,
  (SELECT COUNT(*) FROM people_mails WHERE people_mails.people = people.id) AS total
FROM people $sql ORDER BY firstname, lastname");
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

  <div class="alert alert-info">
    <strong><?php echo count($authors) ?></strong> contacts found. 
    <?php if ($_GET['mail']) { ?>
      <a href="cms/peoplelist">Show all</a>
    <?php } else { ?>
      <a href="cms.peoplelist.php?mail=true">Show mailing list</a>
    <?php } ?>
  </div>

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
      <td><a href="cms/people/<?php echo $row['id'] ?>"><?php echo $row['firstname'] ?> <?php echo $row['lastname'] ?></a></td>
      <td><a href="cms.people.php?id=<?php echo $row['id'] ?>" class="btn btn-info">Edit</a></td>
      <td><a href="cms.peoplelist.php?delete=<?php echo $row['id'] ?>" class="btn btn-danger" onclick="javascript:return confirm('Are you sure?')">Delete</a></td>
      <td>
        <a target="_blank" href="https://www.google.co.za/#q=<?php echo urlencode($row['firstname'] ." " . $row['lastname']) ?>+email">Search</a> | 
        <a target="_blank" href="https://www.google.co.za/#q=<?php echo urlencode($row['firstname'] ." " . $row['lastname']) ?>+urban+metabolism+email">Search +um</a>
      </td>
      <td>
      <a href="cms/mailssent/<?php echo $row['id'] ?>">
        <?php echo $row['total'] ?>
      </a>
      | <a href="cms/mail/<?php echo $row['id'] ?>">preview</a> | 
      <a href="cms/mail/<?php echo $row['id'] ?>/send">send mail</a>
      
      </td>
    </tr>
  <?php } ?>
  </table>

  <p><a href="cms/author" class="btn btn-primary">Add contact</a></p>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
