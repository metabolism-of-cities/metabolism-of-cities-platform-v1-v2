<?php
$options = array(
  2 => array("cms/tagparents", "Tag headers"),
  1 => array("cms/tags", "Tag list"),
  3 => array("cms/masstags", "Mass tag review"),
);
?>
<ul class="nav nav-tabs">
<?php
foreach ($options as $key => $value) {
?>
  <li class="<?php echo $key == $tab ? "active" : "reg"; ?>"><a href="<?php echo $value[0] ?>"><?php echo $value[1] ?></a></li>
<?php } ?>
</ul>
