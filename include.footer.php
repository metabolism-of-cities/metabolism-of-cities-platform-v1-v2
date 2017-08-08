    <?php if ($omat_sidebar) { require_once 'include.omatfooter.php'; } ?>
    <?php if ($profile_sidebar) { require_once 'include.profilefooter.php'; } ?>
    <?php if ($cms_sidebar) { require_once 'include.profilefooter.php'; } ?>
    </div> <!-- /container -->

    <div id="content-below">
      <!-- Awesome features call to action -->
      <div class="bg-primary text-white py-4">
        <div class="container">
          <div class="row text-center text-lg-left">
            <div class="col-12 col-lg-10 py-2">
              <?php if (ID == 1) { ?>
              <h2 class="text-uppercase font-weight-bold mt-0 mb-2">
                <span class="text-shadow">Creative</span> <span class="text-primary-darkend">Commons</span>
              </h2>
              <h5 class="text-faded">
                Unless stated otherwise, all content is licensed under a 
                <a href="https://creativecommons.org/licenses/by/4.0/">
                Creative Commons Attribution 4.0 International license.
                </a>
              </h5>
              <?php } else { ?>
              <h2 class="text-uppercase font-weight-bold mt-0 mb-2">
                <span class="text-primary-darkend">What is</span>
                <span class="text-shadow">EPR?</span>
              </h2>
              <h5 class="text-faded">
                "Extended producer responsibility" (EPR) is an environmental
                policy strategy in which the responsibility of producers for
                their products is extended to include the costs and sometimes
                the management of end-of-life (EoL) waste, especially the
                recycling of their products.
              </h5>
              <?php } ?>
            </div>
            <div class="col-12 col-lg-2 py-2 text-lg-right">
              <?php if (ID == 1) { ?>
              <a href="join" class="btn btn-lg btn-primary btn-invert btn-rounded py-3 px-4">Join Our Team<i class="fa fa-arrow-right ml-2"></i></a>
              <?php } else { ?>
              <a href="content/what-is-epr" class="btn btn-lg btn-primary btn-invert btn-rounded py-3 px-4">Read More<i class="fa fa-arrow-right ml-2"></i></a>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <footer id="footer" class="p-0">
    <?php if (ID == 1) { ?>
      <div class="container pt-6 pb-5">
        <div class="row">
          <div class="col-md-4">
            <!--@todo: replace with company contact details-->
            <h4 class="text-uppercase text-white">
              Contact Us
            </h4>
            <address>
              <ul class="list-unstyled">
                <li>
                  <abbr title="Email"><i class="fa fa-envelope fa-fw"></i></abbr>
                  info@metabolismofcities.org
                </li>
                <li>
                  <abbr title="Address"><i class="fa fa-home fa-fw"></i></abbr>
                  <a href="page/contact">Contact form</a>
                </li>
              </ul>
            </address>
          </div>
          
          <div class="col-md-4">
            <h4 class="text-uppercase text-white">
              About Us
            </h4>
            <p>We are a community-led online hub around urban metabolism.</p>
          </div>
          
          <div class="col-md-4">
            <h4 class="text-uppercase text-white">
              Newsletter
            </h4>
            <p>Stay up to date with our latest news by signing up to our newsletter.</p>
            <!--@todo: replace with mailchimp code-->
            <form>
              <div class="input-group">
                <label class="sr-only" for="email-field">Email</label>
                <input type="text" class="form-control" id="email-field" placeholder="Email">
                <span class="input-group-btn">
                  <button class="btn btn-primary" type="button">Go!</button>
                </span>
              </div>
            </form>
          </div>
        </div>
        <?php } else { ?>
      <div class="container pt-6 pb-5">
        <div class="row">
          <div class="col-md-4">
            <!--@todo: replace with company contact details-->
            <h4 class="text-uppercase text-white">
              Contact Us
            </h4>
            <address>
              <ul class="list-unstyled">
                <li>
                  <abbr title="Email"><i class="fa fa-envelope fa-fw"></i></abbr>
                  info@epr-references.org
                </li>
                <li>
                  <abbr title="Address"><i class="fa fa-home fa-fw"></i></abbr>
                  <a href="page/contact">Contact form</a>
                </li>
              </ul>
            </address>
          </div>
          
          <div class="col-md-8">
            <h4 class="text-uppercase text-white">
              Sponsors
            </h4>
              <div class="sponsorbox">
                <img src="img/sponsors/eur.png" alt="" />
                <img src="img/sponsors/nestle.png" alt="" />
                <img src="img/sponsors/wm.png" alt="" />
                <img src="img/sponsors/tetra.png" alt="" />
              </div>
          </div>
          
        </div>
        <?php } ?>
        
      </div>
      <hr class="my-0 hr-blank op-2">
      <!--@todo: replace with company copyright details-->
      <div class="bg-inverse-dark text-sm py-3">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <p class="mb-0">Site template by <a href="http://appstraptheme.com" class="footer-link">AppStrap</a> 
              | 
              Hosting provided by <a href="http://" class="footer-link">Penguin Protocols</a>
            </p>
            </div>
            <div class="col-md-6">
              <ul class="list-inline footer-links float-md-right mb-0">
                <li class="list-inline-item">Last update: <?php echo format_date("F Y", $update->date_added) ?></li>
              </ul>
            </div>
          </div>
          <a href="#top" class="btn btn-icon btn-inverse pos-fixed pos-b pos-r mr-3 mb-3 hidden-md-down scroll-state-active" title="Back to top" data-scroll="scroll-state"><i class="fa fa-chevron-up"></i></a>
        </div>
      </div>
    </footer>

    <?php if (defined("ADMIN")) { ?>
      <a id="admin" class="btn btn-default" href="cms/index"><i class="fa fa-lock"></i> Admin</a>
    <?php } ?>

    <?php echo $google_translate ?>
