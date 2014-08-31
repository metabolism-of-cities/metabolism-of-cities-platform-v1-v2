  <div class="row">

    <div class="col-sm-3 col-md-2 sidebar">
      <ul class="nav nav-sidebar">
        <?php foreach ($omat_menu[$load_menu]['menu'] as $key => $value) { ?> 
          <li<?php if ($key == $sub_page) { echo ' class="active"'; } ?>><a href="<?php echo $value['url'] ?>"><i class="fa fa-<?php echo $value['icon'] ?>"></i> <?php echo $value['label'] ?></a></li>
        <?php } ?>
      </ul>
    </div>

    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
