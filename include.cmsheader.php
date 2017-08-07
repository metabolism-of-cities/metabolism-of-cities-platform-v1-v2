  <div class="row">

    <div class="col-3">
      <ul class="nav nav-section-menu nav-sidebar">
        <?php foreach ($cms_menu as $key => $value) { ?> 
          <li<?php if ($key == $sub_page) { echo ' class="active"'; } ?>><a href="<?php echo $value['url'] ?>" class="nav-link"><i class="fa fa-<?php echo $value['icon'] ?>"></i> <?php echo $value['label'] ?></a></li>
        <?php } ?>
      </ul>
    </div>

    <div class="col-9">
