<?php
$admin_login = true;

if (defined("SITENAME")) {
  die("This file must be included before the functions.php file");
}

$cms_sidebar = true;

if ($cms_sidebar) {
  $add_to_header .= '<link rel="stylesheet" href="css/sidebar.css" />';
}

$hide_share_buttons = true;

$cms_menu = array(
  6 => array('label' => 'Dashboard', 'url' => "cms/index", 'icon' => 'gear'),
  16 => array('label' => 'To Do List', 'url' => "cms/wishlist", 'icon' => 'check'),
  1 => array('label' => 'Blog Posts', 'url' => "cms/bloglist", 'icon' => 'list'),
  17 => array('label' => 'News', 'url' => "cms/newslist", 'icon' => 'list'),
  20 => array('label' => 'Site Content', 'url' => "cms/contentlist", 'icon' => 'list'),
  3 => array('label' => 'Website Authors', 'url' => "cms/blogauthorlist", 'icon' => 'users'),
  11 => array('label' => 'Dataviz Posts', 'url' => "cms/datavizlist", 'icon' => 'list'),
  15 => array('label' => 'Dataviz Votes', 'url' => "cms/votes", 'icon' => 'check-circle'),
  13 => array('label' => 'Videos', 'url' => "cms/videolist", 'icon' => 'list'),
  10 => array('label' => 'Tags', 'url' => "cms/tagparents", 'icon' => 'tag'),
  21 => array('label' => 'MOOCs', 'url' => "cms/moocs", 'icon' => 'th-list'),
  7 => array('label' => 'Contacts', 'url' => "cms/peoplelist", 'icon' => 'users'),
  8 => array('label' => 'Add Contact', 'url' => "cms/people", 'icon' => 'user'),
  9 => array('label' => 'View Log', 'url' => "cms/log", 'icon' => 'th-list'),
  5 => array('label' => 'Log Out', 'url' => "login.php?logout=true", 'icon' => 'sign-out'),
);

?>
