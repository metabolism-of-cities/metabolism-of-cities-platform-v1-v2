<?php
require_once 'functions.php';

$keywords_all = $db->query("SELECT COUNT(*) AS total, keywords.keyword, keywords.id
FROM keywords_papers JOIN keywords ON keywords_papers.keyword = keywords.id 
GROUP BY keywords.keyword
ORDER BY COUNT(*) DESC");

$keywords_alfabet = $db->query("SELECT COUNT(*) AS total, keywords.keyword, keywords.id
FROM keywords_papers JOIN keywords ON keywords_papers.keyword = keywords.id 
GROUP BY keywords.keyword
ORDER BY keywords.keyword");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>JIE Keywords | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Keywords - by occurence</h1>

  <table class="table table-striped">
    <tr>
      <th>Keyword</th>
      <th>Count</th>
      <th>View papers</th>
    </tr>
  <?php foreach ($keywords_all as $row) { ?>
    <tr>
      <td><?php echo $row['keyword'] ?></td>
      <td><?php echo $row['total'] ?></td>
      <td><a href="publications.list.php?keyword=<?php echo $row['id'] ?>">View papers</a></td>
    </tr>
  <?php } ?>
  </table>

  <h1>Keywords - alphabetical list</h1>

  <table class="table table-striped">
    <tr>
      <th>Keyword</th>
      <th>Count</th>
      <th>View papers</th>
    </tr>
  <?php foreach ($keywords_alfabet as $row) { ?>
    <tr>
      <td><?php echo $row['keyword'] ?></td>
      <td><?php echo $row['total'] ?></td>
      <td><a href="publications.list.php?keyword=<?php echo $row['id'] ?>">View papers</a></td>
    </tr>
  <?php } ?>
  </table>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
