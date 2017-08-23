<?php
$show_breadcrumbs = true;
require_once 'functions.php';
$section = 2;
$page = 3;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Team | <?php echo SITENAME ?></title>
    <style type="text/css">
    .jumbotron li a{font-size:140%;display:block}
    .jumbotron li .links a{display:inline}
    .jumbotron img.team {float:left;max-height:100px;margin:0 10px 10px 0;border:1px solid #ccc;padding:3px;}
    .jumbotron li{clear:both;padding-bottom:10px;}
    .jumbotron ul{list-style:none;}
    .jumpdown{clear:both;padding-top:20px}
    .btn-primary{display:block}
    </style>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

      <div class="jumbotron">
        <h1>Team</h1>

        <p>Below in chronological order our team members. Our team is slowly expanding and we welcome new people! When will you join us?!</p>
        <ul>
          <li>
            <img class="team" src="img/team.paul.jpg" alt="" />
            <strong>Paul Hoekman </strong> - 
            Consulting web developer and urban metabolism researcher.
            Initial creator of this website while doing
            research into the feasibility of undertaking an urban-scale Material
            Flow Analysis in a South African context. Paul enjoys combining IT and 
            urban metabolism research. 
          </li>
          <li>
            <img class="team" src="img/team.aris.jpg" alt="" />
            <strong>Aristide Athanassiadis</strong> - 
            Joint-PhD student at the Université Libre
            de Bruxelles (Brussels, Belgium) and University of Melbourne,
            Australia. Through different research projects in Brussels and
            Melbourne, he has been interested in identifying the
            context-specific factors that influence urban metabolisms using a
            temporal and spatial perspective. 
            <a href="https://www.linkedin.com/in/arisatha"><i class="fa fa-linkedin-square"></i></a>
          </li>
          <li>
            <img class="team" src="img/team.gabriela.jpg" alt="" />
            <strong>Gabriela Fernandez</strong> - 
            Ph.D. candidate in Urban Planning, Design
            and Policy in the Department of Architecture and Urban Studies
            at Politecnico di Milano in Milan, Italy. Fernandez is
            interested in urban metabolism ideologies and is undertaking a
            material flow analysis of the Metropolitan City of Milan thus
            identifying urban typologies and socioeconomic indicators in
            the Italian urban context while promoting urban metabolism
            public policy. <a href="https://it.linkedin.com/in/gabriela-fernandez-56873843"><i class="fa fa-linkedin-square"></i></a>
          </li>
          <li>
            <img class="team" src="img/team.rachel.jpg" alt="" />
            <strong>Rachel Spiegel</strong>
            Consulting engineer within energy and environment at Hjellnes
            Consult, based in Oslo, Norway. Spiegel is interested in applying systems
            thinking to our urban and economic structures to integrate environmental and
            social impacts. 
            <a href="https://no.linkedin.com/in/rspiegel"><i class="fa fa-linkedin-square"></i></a>
          </li>
          <li>
            <img class="team" src="img/team.joao.jpg" alt="" />
            <strong>Joao Meirelles</strong> - Phd student at HERUS / EPFL. Holds a Bsc in Water Resources
            and Environmental Engineering and a Msc in Complex Systems Modeling and had
            worked as a Data Scientist at the big data team for the city of Rio de Janeiro.
            Meirelles is interested in applying big data analysis and complex systems
            thinking to the urban metabolism. 
          </li>
          <li>
            <img class="team" src="img/team.yves.jpg" alt="" />
            <strong>Yves Bettignies Cari</strong> is an engineer and PhD candidate at the Building,
            Architecture and Town planning department of the Ecole Polytechnique de
            Bruxelles in Belgium. Yves is interested in the prediction of urban flows and
            in the modelling of their relations to urban systems via computer-assisted data
            analysis.
          </li>
          <li>
            <img class="team" src="img/team.rupert.jpg" alt="" />
             <strong>Dr. Rupert J. Myers</strong> is a Postdoctoral Associate at the Center for Industrial
             Ecology in the Yale School of Forestry &mp; Environmental Studies. He has a Ph.D.
             in Materials Science &mp; Engineering from the University of Sheffield, is a
             chemical engineer by training, and has several years of research and
             consulting experience across industry, academia, and government.  His research
             is focused towards understanding the sustainability, chemistry, durability and
             performance of industrial materials, in particular cement-related materials
             and metals. Dr. Myers has a keen interest in developing more sustainable
             materials science and technology. He is currently researching the life cycles
             and criticality of metals and metalloids, which includes creating tools to
             more effectively analyze and store quantitative materials flow analysis
             information.
             <div class="links">
             <a href="https://environment.yale.edu/profile/rupert-myers/"><i class="fa fa-link"></i></a>
             <a href="https://www.linkedin.com/in/myersrupert/"><i class="fa fa-linkedin-square"></i></a>
             <a href="https://www.researchgate.net/profile/Rupert_Myers"><img src="img/researchgate.png" alt="" /></a>
             </div>
          </li>
        </ul>
        <p class="jumpdown">
          <a class="btn btn-lg btn-primary" href="join" role="button">Join now &raquo;</a>
        </p>
      </div>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
