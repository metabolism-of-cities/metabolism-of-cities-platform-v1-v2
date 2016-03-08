<?php
$admin_login = true;

if (defined("SITENAME")) {
  die("This file must be included before the functions.php file");
}

$cms_sidebar = true;

if ($cms_sidebar) {
  $add_to_header .= '<link rel="stylesheet" href="css/sidebar.css" />';
}

$cms_menu = array(
  6 => array('label' => 'Dashboard', 'url' => "cms/index", 'icon' => 'gear'),
  1 => array('label' => 'Blog Posts', 'url' => "cms/bloglist", 'icon' => 'list'),
  2 => array('label' => 'New Blog Post', 'url' => "cms/blog", 'icon' => 'pencil'),
  3 => array('label' => 'Authors', 'url' => "cms/blogauthorlist", 'icon' => 'users'),
  4 => array('label' => 'Add Author', 'url' => "cms/author", 'icon' => 'user'),
  5 => array('label' => 'Log Out', 'url' => "login.php?logout=true", 'icon' => 'sign-out'),
);

?>
