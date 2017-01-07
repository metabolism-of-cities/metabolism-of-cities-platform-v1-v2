<?php
$show_breadcrumbs = true;
require_once 'functions.php';
$section = 7;
$page = 5;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Global Urban Metabolism Dataset Stakeholders Initiative | <?php echo SITENAME ?></title>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>


  <h1>Global Urban Metabolism Dataset</h1>

  <div class="alert alert-success">
    <i class="fa fa-check-circle"></i>
    This is an <strong>active</strong> Stakeholders Initiative project, running
    from January to March 2017. Go here to view <a href="stakeholders">all current and past projects</a>.
  </div>

  <p>
  At the open source Metabolism of Cities website we want to create a database
  with urban metabolism data and indicators. That is, we plan to examine a
  variety of research studies that have calculated particular values (material
  extraction, emissions, construction material use, imports, exports, etc.) for
  an urban/provincial region. The metabolism indicators will also take into
  account energy, water, air pollution as well as urban characteristics
  indicators. By creating one large masterlist of these values it is much easier
  for other researchers to see what values are out there and to compare their
  own data to other studies. We aim to do this at different spatial scales as
  well: region, city, municipalities, ... With this big masterlist it will
  therefore also become possible to identify indicators for resource use and
  pollution emission. 
  </p>

  <p>Our current work in this project is logged under the <a href="data">Data</a> section
  on our website. The links below all point to the information under that section, because
  we aim for this work to be a long-term, continuous project that will not end 
  when this Stakeholders Initiative ends. 
  
  </p>

    <div class="row">
      <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Project Goals</h3>
            </div>
            <div class="panel-body">
            <p>
              Introduction post on our blog by Paul Hoekman. This post
              discusses what the goals are of this project, why it is useful, and what
              our long-term vision is. 
            </p>
            <p><a href="blog/4-towards-a-global-urban-metabolism-dataset" class="btn btn-success">Read more &raquo;</a></p>
            </div>
          </div>
      </div>
      <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Data Overview</h3>
            </div>
            <div class="panel-body">
              <p>
                We have already registered hundreds of data points. Have a look at what we 
                have so far, and at the publications that we have identified at this point. 
              </p>
              <p><a href="page/casestudies" class="btn btn-success">Read more &raquo;</a></p>
            </div>
          </div>
      </div>
    </div>    
    <div class="row">
      <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Add Data</h3>
            </div>
            <div class="panel-body">
                <p>
                You can help us with the development of this global dataset. Maybe you have done urban
                metabolism research yourself, or maybe you can spend some time going through 
                journal publications to look for data. 
                </p>
              <p><a href="data/add" class="btn btn-success">Read more &raquo;</a></p>
            </div>
          </div>
      </div>
      <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Publications Map</h3>
            </div>
            <div class="panel-body">
              <p>
                One of our earlier tools is a publications map, which shows where in the world 
                urban metabolism research has been done and published, based on our
                <a href="publications/list">publications database</a>.
              </p>
              <p><a href="page/map" class="btn btn-success">Read more &raquo;</a></p>
            </div>
          </div>
      </div>
    </div>

    <h2>Participate!</h2>

    <p>Would you like to participate? Now is the right time to take action! Here is a list of how
    you can collaborate in this stakeholders initiative:</p>

    <ul>
      <li><strong>Contribute data</strong><br />
      We are looking for people who can provide us with data. Have you undertaken urban metabolism
      research? <a href="page/contact">Get in touch</a> to contribute your information and to get
      the data included in our global dataset.
      </li>
      <li><strong>Help extract data</strong><br />
      We are going through hundreds of pages of scientific literature and academic publications in 
      order to find relevant data points. And we need as many people on board to cover as much
      as we can! Are you keen to help? 
      <a href="page/contact">Get in touch now!</a>
      </li>
      <li><strong>Write a guest blog post</strong><br />
      We welcome guest blog posts about any topic related to data visualization. It can be a personal 
      story about how you dealt with data visualizations, or a study of data visualizations in some of
      the recent work done by authors you admire. Or anything else you feel contributes to this topic.
      Interested? <a href="page/contact">Let us know and we can discuss this topic!</a> Contributions 
      are due any time before this stakeholder initiative ends.
      </li>
      <li><strong>Collaborate in research</strong><br />
      We are not just aiming to make this global urban metabolism dataset available, but we also 
      want to analyze this information. Are you interested in participating in this research 
      project? <a href="page/contact">Let us know and we can discuss this topic!</a>
      </li>
      <li><strong>Contribute your own idea</strong><br />
      We are open to any other idea as part of this Stakeholders Initiative. Do you have an activity
      you'd like us to set up? Is there a creative idea you'd like to share with the people on the
      mailing list? Do you have any other idea? <a href="page/contact">Let us know right away!</a>
      </li>
      <li><strong>Sign up to our stakeholders initiative</strong><br />
      If you haven't already, be sure to sign up to our Stakeholders Initiative so that you will 
      receive e-mails throughout the coming months about activities, posts, and other things we 
      organize in relation to this project.
      <a href="stakeholders/subscribe">Subscribe now</a>
      </li>
      <li><strong>Tell a friend</strong><br />
      We would like to reach as many people as possible. So send an e-mail to your friends, put 
      up a notice in the canteen of your faculty, or post this information to social media. 
      Tell your friends and colleagues!
      </li>

    </ul>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
