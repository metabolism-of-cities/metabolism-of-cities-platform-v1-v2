<?php

// Here we check if the visitors has the right token to view/edit
// the profile.

$profile_id = (int)$_COOKIE['id'];
$hash = $_COOKIE['hash'];

if ($hash != encrypt("PROFILE $profile_id") || !$profile_id) {
  kill("No access after cookie login", "critical");
}

$profile_info = $db->record("SELECT 
people.*, people_access.email AS access_email
FROM people_access 
  JOIN people ON people_access.people = people.id
WHERE people_access.id = $profile_id AND people.active IS TRUE AND people_access.active IS TRUE");

if (!$profile_info->id) {
  kill("Profile not found", "critical");
}

$people_id = $profile_info->id;

$profile_sidebar = true;

if ($profile_sidebar) {
  $header .= '<link rel="stylesheet" href="css/sidebar.css" />';
}

$profile_menu = array(
  1 => array('label' => 'Dashboard', 'url' => "profile/$profile_id/dashboard", 'icon' => 'gear'),
  5 => array('label' => 'Profile', 'url' => "profile/$profile_id/edit", 'icon' => 'pencil'),
  2 => array('label' => 'Your Publications', 'url' => "profile/$profile_id/publications", 'icon' => 'list'),
  3 => array('label' => 'Add Publication', 'url' => "profile/$profile_id/publication", 'icon' => 'book'),
  4 => array('label' => 'Datasets', 'url' => "profile/$profile_id/data", 'icon' => 'database'),
  6 => array('label' => 'Log Out', 'url' => "profile/$profile_id/logout", 'icon' => 'sign-out'),
);
?>
