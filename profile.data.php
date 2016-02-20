<?php
require_once 'functions.php';
require_once 'functions.profile.php';

$sub_page = 4;

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Datasets | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

<h1>Datasets</h1>

<p>
  At the Metabolism of Cities website we are working hard to make one central, publicly accessible database
  that provides visitors with all the data obtained by urban metabolism research. This dataset can easily 
  be searched, visualized on a map, and downloaded. Our purpose is to make research data more accessible and make
  it easier to see which numbers have been obtained by previous research. All datapoints are linked to the
  original study and full credit is given to the researchers involved.
</p>

<p>
  We would like to ask you to add any data points that you have obtained through urban metabolism research. 
  We are looking for any piece of data including per-capita indicators, total input, output, or data on 
  particular subflows.
</p>

<h2>How to add data?</h2>

<h3>Directly enter the data</h3>

<p>
  You can enter data directly into our system. Entering data is simple and fast, and practical if you have a relatively small 
  list of data points (less than 30). 
</p>

<p>
  <a href="profile/<?php echo $profile_id ?>/data-entry" class="btn btn-primary">Start now</a>
</p>

<h3>E-mail us your data</h3>

<p>
  If you have a large list of data points, then it is much easier to e-mail us your data. An spreadsheet will be most practical
  for this purpose. E-mail your data to <a href="mailto:<?php echo EMAIL ?>"><?php echo EMAIL ?></a> and we'll take it 
  from there.
</p>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
