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
  3 => array('label' => 'Blog Authors', 'url' => "cms/blogauthorlist", 'icon' => 'users'),
  4 => array('label' => 'Add Blog Author', 'url' => "cms/author", 'icon' => 'user'),
  11 => array('label' => 'Dataviz Posts', 'url' => "cms/datavizlist", 'icon' => 'list'),
  12 => array('label' => 'New Dataviz Post', 'url' => "cms/dataviz", 'icon' => 'pencil'),
  15 => array('label' => 'Dataviz Votes', 'url' => "cms/votes", 'icon' => 'check-circle'),
  13 => array('label' => 'Videos', 'url' => "cms/videolist", 'icon' => 'list'),
  14 => array('label' => 'New Video', 'url' => "cms/video", 'icon' => 'play'),
  10 => array('label' => 'Tags', 'url' => "cms/tags", 'icon' => 'tag'),
  7 => array('label' => 'Contacts', 'url' => "cms/peoplelist", 'icon' => 'users'),
  8 => array('label' => 'Add Contact', 'url' => "cms/people", 'icon' => 'user'),
  9 => array('label' => 'View Log', 'url' => "cms/log", 'icon' => 'th-list'),
  5 => array('label' => 'Log Out', 'url' => "login.php?logout=true", 'icon' => 'sign-out'),
);

?>
