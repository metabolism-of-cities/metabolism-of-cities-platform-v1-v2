<?php
header("Content-type: application/json");
$project = (int)$_POST['project'];
require_once 'functions.php';
require_once 'functions.omat.php';

$contact = (int)$_POST['contact'];
$source = (int)$_POST['source'];

if ($_POST['action'] == 'addcontact') {
  $post = array(
    'name' => html($_POST['name']),
    'organization' => (int)$_POST['organization'],
    'dataset' => $project,
    'pending' => 1,
  );
  $db->insert("mfa_contacts",$post);
  $id = $db->lastInsertId();
  $post = array(
    'to_contact' => $id,
  );
  if ($contact) {
    $post['from_contact'] = $contact;
  } else {
    $post['from_source'] = $source;
  }
  $db->insert("mfa_leads",$post);
  $icon = $_POST['organization'] ? 'building-o' : 'user';
  $data['response'] = 'OK';
  $data['message'] = 
    "<a class='list-group-item active' href='omat/{$project}/viewcontact/{$id}'>
      <i class='fa fa-{$icon}'></i> {$_POST['name']}</a>";
}
if ($_POST['action'] == 'addsource') {
  $post = array(
    'name' => html($_POST['name']),
    'dataset' => $project,
    'pending' => 1,
  );
  $db->insert("mfa_sources",$post);
  $id = $db->lastInsertId();
  $post = array(
    'to_source' => $id,
  );
  if ($contact) {
    $post['from_contact'] = $contact;
  } else {
    $post['from_source'] = $source;
  }
  $db->insert("mfa_leads",$post);
  $data['response'] = 'OK';
  $data['message'] = 
    "<a class='list-group-item active' href='omat/{$project}/viewsource/{$id}'>{$_POST['name']}</a>";
}
if ($_POST['action'] == 'addactivity') {
  $explode = explode(":", $_POST['time']);
  if ($explode[1]) {
    $time = $explode[0]*60 + $explode[1];
  } else { 
    $time = (int)$_POST['time'];
  }
  if ($_POST['timer']) {
    $post = array(
      'activity' => (int)$_POST['type'],
      'start' => date("Y-m-d H:i:s"),
    );
    $icon = '<i class="fa fa-clock-o"></i>';
    $min = "<em>ongoing</em>";
  } else {
    $post = array(
      'activity' => (int)$_POST['type'],
      'time' => $time,
      'end' => date("Y-m-d H:i:s"),
    );
    $min = "$time min";
  }
  if ($contact) {
    $post['contact'] = $contact;
  } else {
    $post['source'] = $source;
  }
  $type = (int)$_POST['type'];
  $getname = $db->record("SELECT * FROM mfa_activities WHERE dataset = $project AND id = $type");
  $db->insert("mfa_activities_log",$post);
  $id = $db->lastInsertId();
  $data['response'] = 'OK';
  $data['message'] = 
    "<a class='list-group-item active' href='omat/{$project}/viewactivity/{$id}'>
    $icon
    {$getname->name} ($min)</a>";
}
if (!$data) {
  $data['reponse'] = 'Fail';
}
echo json_encode($data);
?>
