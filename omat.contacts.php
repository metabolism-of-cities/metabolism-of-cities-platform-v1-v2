<?php
require_once 'functions.php';
require_once 'functions.omat.php';

$section = 6;
$load_menu = 1;
$sub_page = 2;

$id = $project;

$list = $db->query("SELECT * FROM mfa_contacts WHERE dataset = $id");

$sourcelist = $db->query("SELECT * FROM mfa_sources WHERE dataset = $id");

foreach ($list as $row) {
  if ($row['belongs_to']) {
    $contacts[$row['belongs_to']][$row['id']] = $row['name'];
  } else {
    $contactnames[$row['id']] = $row['name'];
  }
}

foreach ($sourcelist as $row) {
  if ($row['belongs_to']) {
    $sources[$row['belongs_to']][$row['id']] = $row['name'];
  } else {
    $sourcenames[$row['id']] = $row['name'];
  }
}

asort($contactnames);
asort($sourcenames);

function buildList($id) {
  global $contacts, $project, $sources;
  if (is_array($contacts[$id]) || is_array($sources[$id])) {
    echo '<ul>';
    if (is_array($contacts[$id])) {
      asort($contacts[$id]);
      foreach ($contacts[$id] as $key => $value) {
        echo '<li class="viewcontact"><a href="omat/'.$project.'/viewcontact/'.$key.'">' . $value . '</a>';
        if (is_array($contacts[$key]) || is_array($sources[$key])) {
          buildList($key);
        }
        echo '</li>';
      }
    }
    if (is_array($sources[$id])) {
      asort($sources[$id]);
      foreach ($sources[$id] as $key => $value) {
        echo '<li><i class="fa fa-file-o"></i> <a href="omat/'.$project.'/viewsource/'.$key.'">' . $value . '</a></li>';
      }
    }
    echo '</ul>';
  }
}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Contacts and Sources | <?php echo SITENAME ?></title>
    <style type="text/css">
    #contacts li i{color:#333}
    </style>
    <script type="text/javascript">
    $(function(){
      $(".display a").click(function(e){
        e.preventDefault();
        var level = $(this).data("level");
        if (level == 1) {
          $("#contacts ul").hide();
        } else if (level == 2) {
          $("#contacts ul").show();
          $("#contacts ul ul").hide();
        } else if (level == 3) {
          $("#contacts ul").show();
          $("#contacts ul ul").show();
          $("#contacts ul ul ul").hide();
        } else if (level == 'all') {
          $("#contacts ul").show();
          $("#contacts ul ul").show();
          $("#contacts ul ul ul").show();
        }
        $(".display a").removeClass('btn-primary').addClass('btn-default');
        $(this).addClass('btn-primary').removeClass('btn-default');
      });
    });
    </script>
  </head>

  <body>

<?php require_once 'include.header.php'; ?>

  <a href="omat/<?php echo $project ?>/contact/0" class="btn btn-default pull-right"><i class="fa fa-user"></i> Add contact</a>
  <a href="omat/<?php echo $project ?>/source/0" class="btn btn-default pull-right"><i class="fa fa-file"></i> Add source</a>

  <h1>Manage Resources</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Manage Resources</li>
  </ol>

  <p class="display">
    <a href="#" class="btn btn-default" data-level="1"><strong>1</strong> First level contacts only</a>
    <a href="#" class="btn btn-default" data-level="2"><strong>2</strong> Up to second level</a>
    <a href="#" class="btn btn-default" data-level="3"><strong>3</strong> Up to third level</a>
    <a href="#" class="btn btn-primary" data-level="all">Show all</a>
  </p>

  <ul id="contacts">
    <?php foreach ($contactnames as $key => $value) { ?>
      <li>
        <strong>
          <a href="omat/<?php echo $project ?>/viewcontact/<?php echo $key ?>">
            <?php echo $value ?>
          </a>
         </strong>
        <?php buildList($key) ?>
      </li>
    <?php } ?>
    <?php if (is_array($sourcenames)) { ?>
      <li><strong>Unclassified sources</strong><ul>
    <?php foreach ($sourcenames as $key => $value) { ?>
      <li class="viewsource">
        <i class="fa fa-file-pdf-o"></i> 
        <a href="omat/<?php echo $project ?>/viewsource/<?php echo $key ?>">
          <?php echo $value ?>
        </a>
      </li>
    <?php } ?>
    </ul>
    </li>
    <?php } ?>
  </ul>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
