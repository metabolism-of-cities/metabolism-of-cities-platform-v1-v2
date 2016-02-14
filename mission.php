<?php
require_once 'functions.php';
$section = 2;
$page = 1;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>About The Metabolism of Cities | <?php echo SITENAME ?></title>
    <style type="text/css">
    .jumbotron li{font-size:18px}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <div class="jumbotron">
    <h1>Mission</h1>

    <p> 
    The Metabolism of Cities aims to be a open source platform for urban
    metabolism, bringing together people, <a
    href="publications/list">information</a> and <a
    href="page/casestudies">data</a> in one central place.
    </p>

    <p>We strive to:</p>

    <ul>
      <li>encourage <strong>collaboration</strong> between
      urban metabolism stakeholders (academia, urban administrations, NGOs, local
      inhabitants, etc)</li>
      <li>host and create <a href="page/casestudies"><strong>open data</strong></a> that can be
      used for environmental monitoring or to compare cities</li>
      <li>enable a global <strong>conversation</strong> in the field of
      urban metabolism in order to advance this field</li>
    </ul>

  </div>

  <h2>What is Urban Metabolism?</h2>


  <p>
    The Metabolism of Cities is named after the <a href="publication/138">pioneering 
    study of Abel Wolman</a> that compared urban areas to living organisms requiring
    “materials and commodities [...] to sustain the city’s inhabitants at home, at
    work and at play”. 50 years later, urban metabolism studies are now an
    essential approach to understand and assess input flows (such as energy, water,
    materials) as well as output flows (air pollution, solid waste, wastewater).
    This systemic urban environmental assessment is central in an urban world that
    is facing pressing environmental issues as through its better understanding of
    urban systems it can propose coherent environmental policies, rethink urban
    planning, propose new production/consumption patterns and raise environmental
    awareness.
  </p>

  <p>
    In practice, urban metabolism studies are a “large collection of data
    exercise” (<a href="publication/281">Kennedy and Hoornweg, 2012</a>).
    Therefore, urban metabolism balances are highly dependent on data coming
    from a great number of providers and can be very time consuming. Often
    studies are done in isolation and only once. This is where The Metabolism
    of Cities comes into play.
  </p>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
