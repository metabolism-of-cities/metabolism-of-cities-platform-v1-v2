<?php
require_once 'functions.php';
$section = 5;
$page = 1;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Data | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <h1>Global Urban Metabolism Dataset</h1>
  <p>
     At the open source Metabolism of Cities website we want to create a database with
     urban metabolism data and indicators. That is, we plan to examine a variety of
     research studies that have calculated particular values (material
     extraction, emissions, construction material use, imports, exports, etc.)
     for an urban/provincial region. 
     The metabolism indicators will also take into account energy, water, air
     pollution as well as urban characteristics indicators. 

     By creating one large masterlist of these
     values it is much easier for other researchers to see what values are
     out there and to compare their own data to other studies.
     We aim to do this at different spatial scales as well: region, city,
     municipalities, ... With this big masterlist it will therefore also become
     possible to identify indicators for resource use and pollution emission.
     </p>
     
     <p>
     Our final goal is to
     provide everyone in the community with the following tools:
   </p>

     <ul>
      <li>A centralized overview that makes it easy to explore a variety of studies with one single click</li>
      <li>Maps you can navigate to see where studies have been done and what the outcome was</li>
      <li>A consistently formatted database with research data, which can be viewed online or downloaded in CSV format</li>
      <li>Data visualizations that present the dataset in an attractive, easy-to-understand way</li>
      <li>Perhaps our own interesting insights after we have mined all this data! </li>
     </ul>

   <p>
     We need help identifying suitable studies, extracting relevant numbers,
     and presenting the data! We can use help in many forms, and would hereby
     like to invite anyone to join us. We don't work with strict deadlines and
     you can contribute as much or as little as you are able to. We welcome all
     support and hope to slowly but steadily assemble a global team of urban
     metabolism enthusiasts! 
   </p>

    <p>Specific tasks we need you help with include:</p>
    
    <ul>
      <li>Identification of useful research projects and other studies</li>
      <li>Extraction of relevant data and entry into our system</li>
      <li>Assistance with visualization of data</li>
      <li>Programming work to integrate support of all aforementioned functionality on our website</li>
      <li>Any other relevant task you can think of that enhances our project</li>
    </ul>

    <p>
      Are you interested? 
      <a href="page/contact">Get in touch!</a>
    </p>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
