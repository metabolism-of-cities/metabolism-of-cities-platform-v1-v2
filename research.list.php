<?php
$show_breadcrumbs = true;
$skip_login = true;
require_once 'functions.php';
require_once 'functions.omat.php';
$section = 4;
$page = 2;

$list = $db->query("SELECT * FROM research WHERE deleted_on IS NULL ORDER BY title");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title><?php echo _('Current urban metabolism research'); ?> | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

<h1>Current Research - Project List</h1>

<p>
In this section we aim to provide a collection of ongoing urban metabolism-related
research. Our website is still relatively young so the list is slowly growing,
but we encourage everyone who works on UM studies to submit their project
here! Projects are listed to improve global knowledge of what is going on in
the world of UM studies, and to facilitate communication between researchers. 
</p>

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

  <p><a href="research/add" class="btn btn-primary">Add your own project</a></p>

  <h2>Map</h2>

  <p>The map below shows the case study location of the different cities that are part of the urban metabolism research projects. </p>

<iframe width="100%" height="520" frameborder="0" src="https://metabolismofcities.carto.com/viz/f86ed1fc-1f49-11e7-9ae0-0ecd1babdde5/embed_map" allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>


<?php require_once 'include.footer.php'; ?>

  </body>
</html>
