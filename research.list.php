<?php
require_once 'functions.php';
$section = 3;
$page = 1;

$list = $db->query("SELECT * FROM research ORDER BY title");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo _('Current MFA research'); ?> | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

<h1>Current Research - Project List</h1>

<p>In this section we aim to provide a collection of ongoing MFA-related research. We are starting out so there is
not much to see yet, but we encourage everyone who works on MFA studies to submit their project here! Projects are 
listed to improve global knowledge of what is going on in the world of MFA studies, and to facilitate communication 
between researchers. 
</p>

<?php if (count($list)) { ?>

  <table class="table table-striped">
    <tr>
      <th>Project</th>
      <th>Researcher(s)</th>
      <th>Institution</th>
    </tr>
  <?php foreach ($list as $row) { ?>
    <tr>
      <td><a href="research/<?php echo $row['id'] ?>"><?php echo $row['title'] ?></a></td>
      <td><?php echo $row['researcher'] ?></td>
      <td><?php echo $row['institution'] ?></td>
    </tr>
  <?php } ?>
  </table>

<?php } else { ?>

  <div class="alert alert-info">
    We do not have any public dataset online yet! You can be the first by uploading your dataset from a previous project, 
    or by <a href="projects/add">creating a new project</a>. Alternatively, head to the <a href="publications/list">database</a>
    to go through Material Flow Analysis publications. 
  </div>

<?php } ?>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
