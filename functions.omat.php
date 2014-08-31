<?php
// This file is to be included on all OMAT pages. 
// It makes sure the user has access to the project that
// is being managed. 
// By default, we assume that $_GET['id'] contains the project ID.
// If that is not the case, we should define $project BEFORE including
// this file. 

if (!$project) {
  $project = $_GET['project'] ? (int)$_GET['project'] : (int)$_GET['id'];
}
if (!$project) {
  die("No project defined");
}

$check = $db->record("SELECT id FROM mfa_dataset WHERE id = $project");
if (!$check->id) {
  kill("Invalid dataset opened");
}

$omat_sidebar = !$disable_sidebar ? true : false;

if ($omat_sidebar) {
  $header .= '<link rel="stylesheet" href="css/sidebar.css" />';
}

// Do an access check here to make sure this user has access. If not, redirect.

$omat_menu = array(
  1 => array(
    'label' => 'Data', 
    'url' => "omat/$project/manage", 
    'menu' => array(
      1 => array('label' => 'Manage Data', 'url' => "omat/$project/manage", 'icon' => 'pencil'),
      2 => array('label' => 'Manage Contacts', 'url' => "omat/$project/contacts", 'icon' => 'user'),
      3 => array('label' => 'Manage Sources', 'url' => "omat/$project/sources", 'icon' => 'link'),
      4 => array('label' => 'Work Sheet', 'url' => "omat/$project/worksheet", 'icon' => 'list'),
    ),
  ),
  // To do:
  // Must set/unset these depending on settings
  2 => array(
    'label' => 'Maintenance', 
    'url' => "omat/$project/dashboard", 
    'menu' => array(
      1 => array('label' => 'Data Quality Indicators', 'url' => "omat/$project/maintenance-dqi", 'icon' => 'star'),
      2 => array('label' => 'Types of Sources', 'url' => "omat/$project/maintenance-sources", 'icon' => 'link'),
      3 => array('label' => 'Types of Contacts', 'url' => "omat/$project/maintenance-contacts", 'icon' => 'user'),
      4 => array('label' => 'Types of Activities', 'url' => "omat/$project/maintenance-activities", 'icon' => 'comment'),
      5 => array('label' => 'Types of Scales', 'url' => "omat/$project/maintenance-scales", 'icon' => 'dot-circle-o'),
    ),
  ),
  3 => array(
    'label' => 'Reports', 
    'url' => "omat/$project/reports", 
    'menu' => array(
      1 => array('label' => 'Data Overview', 'url' => "omat/$project/reports-dataoverview"),
      2 => array('label' => 'Indicators', 'url' => "omat/$project/reports-indicators"),
    ),
  ),
);
?>
