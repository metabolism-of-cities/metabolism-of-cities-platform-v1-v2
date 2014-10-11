<?php
require_once 'functions.php';
require_once 'functions.omat.php';

$section = 6;
$load_menu = 1;
$sub_page = 2;

$id = $project;

$list = $db->query("SELECT * FROM mfa_contacts WHERE dataset = $id");

foreach ($list as $row) {
  if ($row['belongs_to']) {
    $contacts[$row['belongs_to']][$row['id']] = $row['name'];
  } else {
    $contactnames[$row['id']] = $row['name'];
  }
}
asort($contactnames);

function buildList($id) {
  global $contacts, $project;
  if (is_array($contacts[$id])) {
    asort($contacts[$id]);
    echo '<ul>';
    foreach ($contacts[$id] as $key => $value) {
      echo '<li><a href="omat/'.$project.'/viewcontact/'.$key.'">' . $value . '</a>';
      if (is_array($contacts[$key])) {
        asort($contacts[$key]);
        buildList($key);
      }
      echo '</li>';
    }
    echo '</ul>';
  }
}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $header ?>
    <title>Contact List | <?php echo $info->name ?> | <?php echo SITENAME ?></title>
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

  <a href="omat/<?php echo $project ?>/contact/0" class="btn btn-default pull-right">Add contact</a>
  <a href="omat/<?php echo $project ?>/contacts" class="btn btn-default pull-right">Apply filters</a>

  <h1>Contact List</h1>

  <ol class="breadcrumb">
    <li><a href="omat/<?php echo $project ?>/dashboard">Dashboard</a></li>
    <li class="active">Contacts</li>
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
  </ul>

<?php require_once 'include.footer.php'; ?>

  </body>
</html>
