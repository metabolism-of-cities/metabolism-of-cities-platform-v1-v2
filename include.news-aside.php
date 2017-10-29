
          <div class="col-md-3 sidebar-right">
            
            <div class="mb-4">
              <h4 class="title-divider">
                <span>Archive</span>
              </h4>
              <ul class="list-unstyled list-lg tags">
                <?php foreach ($months as $row) { ?>
                  <li><i class="fa fa-angle-right fa-fw"></i> <a href="news/<?php echo $row['year'] ?>/<?php echo $row['month'] ?>">
                  <?php echo format_date("M Y", $row['year']."-".$row['month']."-01") ?></a>
                  (<?php echo $row['total'] ?>)
                  </li>
                <?php } ?>
              </ul>
            </div>
            
            <?php if (ID == 1) { ?>
            <div class="mb-4">
              <a href="page/mailinglist" class="btn btn-warning"><i class="fa fa-rss"></i> Subscribe to our newsletter</a>
            </div>
            
            <div class="mb-4">
              <h4 class="title-divider">
                <span>Follow Us On</span>
              </h4>
              <!--@todo: replace with real social media links -->
              <ul class="list-unstyled social-media-branding">
                <li>
                  <a href="https://twitter.com/CityMetabolism" class="social-link branding-twitter"><i class="fa fa-twitter-square fa-fw"></i> Twitter</a>
                </li>
              </ul>
            </div>
            <?php } ?>
          </div>
