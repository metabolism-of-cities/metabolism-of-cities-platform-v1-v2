<?php
require_once 'functions.php';
require_once 'functions.profile.php';

$sub_page = 2;
$info = $profile_info;

$papers = $db->query("SELECT 
  papers.*
FROM people_papers
  JOIN papers ON people_papers.paper = papers.id
WHERE people_papers.people = $people_id
ORDER BY papers.year DESC");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Publications | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Publications</h1>

  <div class="alert alert-info">
    Please find below a list with publications that have been linked with your profile.
    A total of <strong><?php echo count($papers) ?></strong> <?php echo count($papers) > 1 ? "publications were found" : "publication was found"; ?>
  </div>

  <table class="table table-striped">
    <tr>
      <th>Publication</th>
      <th>Year</th>
      <th>Edit</th>
    </tr>
    <?php foreach ($papers as $row) { ?>
    <tr>
      <td><a href="publication/<?php echo $row['id'] ?>"><?php echo $row['title'] ?></a></td>
      <td><?php echo $row['year'] ?></td>
      <td><a href="publication.edit.php?id=<?php echo $row['id'] ?>&amp;hash=<?php echo encrypt($row['id'] . $row['title']) ?>&amp;profile=true">Edit</a></td>
    </tr>
  <?php } ?>
  </table>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
